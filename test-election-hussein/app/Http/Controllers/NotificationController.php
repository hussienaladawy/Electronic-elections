<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use App\Models\NotificationDelivery;
use App\Models\Voter;
use App\Models\SuperAdmin;
use App\Models\Admin;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * عرض قائمة الإشعارات
     */
    public function index()
    {
        $notifications = Notification::with(['createdBy', 'updatedBy'])
            ->withCount(['recipients', 'deliveries'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('super_admin.notifications.index', compact('notifications'));
    }

    /**
     * عرض نموذج إنشاء إشعار جديد
     */
    public function create()
    {
        return view('super_admin.notifications.create');
    }

    /**
     * حفظ إشعار جديد
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:administrative,educational,security,reminder,announcement,alert',
            'priority' => 'required|in:low,normal,high,urgent',
            'channels' => 'required|array|min:1',
            'channels.*' => 'in:in_app,email,sms,push,voice',
            'target_audience' => 'required|array|min:1',
            'scheduled_at' => 'nullable|date|after:now',
            'metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $notification = Notification::create([
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'priority' => $request->priority,
                'channels' => $request->channels,
                'target_audience' => $request->target_audience,
                'scheduled_at' => $request->scheduled_at,
                'status' => $request->scheduled_at ? 'scheduled' : 'pending',
                'metadata' => $request->metadata,
                'created_by' => auth('super_admin')->id()
            ]);

            // تحديد المستلمين
            $recipients = $notification->determineRecipients();
            
            foreach ($recipients as $recipient) {
                NotificationRecipient::create([
                    'notification_id' => $notification->id,
                    'recipient_type' => get_class($recipient),
                    'recipient_id' => $recipient->id,
                    'status' => 'pending'
                ]);
            }

            // إرسال فوري إذا لم يكن مجدولاً
            if (!$request->scheduled_at) {
                $this->sendNotification($notification);
            }

            DB::commit();

            return redirect()->route('super_admin.notifications.show', $notification)
                ->with('success', 'تم إنشاء الإشعار بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create notification', [
                'error' => $e->getMessage(),
                'user_id' => auth('super_admin')->id()
            ]);

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إنشاء الإشعار')
                ->withInput();
        }
    }

    /**
     * عرض تفاصيل إشعار محدد
     */
    public function show(Notification $notification)
    {
        $notification->load(['createdBy', 'updatedBy', 'recipients.recipient', 'deliveries']);

        $stats = [
            'total_recipients' => $notification->recipients()->count(),
            'delivered_count' => $notification->deliveries()->where('status', 'delivered')->count(),
            'failed_count' => $notification->deliveries()->where('status', 'failed')->count(),
            'read_count' => $notification->recipients()->whereNotNull('read_at')->count(),
            'clicked_count' => $notification->recipients()->whereNotNull('clicked_at')->count(),
        ];

        $deliveryStats = DB::table('notification_deliveries')
            ->where('notification_id', $notification->id)
            ->select('channel', 'status', DB::raw('COUNT(*) as count'))
            ->groupBy('channel', 'status')
            ->get();

        return view('super_admin.notifications.show', compact('notification', 'stats', 'deliveryStats'));
    }

    /**
     * عرض نموذج تعديل الإشعار
     */
    public function edit(Notification $notification)
    {
        // منع تعديل الإشعارات المرسلة
        if ($notification->isSent()) {
            return redirect()->back()
                ->with('error', 'لا يمكن تعديل الإشعارات المرسلة');
        }

        return view('super_admin.notifications.edit', compact('notification'));
    }

    /**
     * تحديث بيانات الإشعار
     */
    public function update(Request $request, Notification $notification)
    {
        // منع تعديل الإشعارات المرسلة
        if ($notification->isSent()) {
            return redirect()->back()
                ->with('error', 'لا يمكن تعديل الإشعارات المرسلة');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:administrative,educational,security,reminder,announcement,alert',
            'priority' => 'required|in:low,normal,high,urgent',
            'channels' => 'required|array|min:1',
            'channels.*' => 'in:in_app,email,sms,push,voice',
            'target_audience' => 'required|array|min:1',
            'scheduled_at' => 'nullable|date|after:now',
            'metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $notification->update([
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'priority' => $request->priority,
                'channels' => $request->channels,
                'target_audience' => $request->target_audience,
                'scheduled_at' => $request->scheduled_at,
                'status' => $request->scheduled_at ? 'scheduled' : 'pending',
                'metadata' => $request->metadata,
                'updated_by' => auth('super_admin')->id()
            ]);

            // إعادة تحديد المستلمين
            $notification->recipients()->delete();
            $notification->deliveries()->delete();

            $recipients = $notification->determineRecipients();
            
            foreach ($recipients as $recipient) {
                NotificationRecipient::create([
                    'notification_id' => $notification->id,
                    'recipient_type' => get_class($recipient),
                    'recipient_id' => $recipient->id,
                    'status' => 'pending'
                ]);
            }

            DB::commit();

            return redirect()->route('super_admin.notifications.show', $notification)
                ->with('success', 'تم تحديث الإشعار بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update notification', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
                'user_id' => auth('super_admin')->id()
            ]);

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث الإشعار')
                ->withInput();
        }
    }

    /**
     * حذف الإشعار
     */
    public function destroy(Notification $notification)
    {
        // منع حذف الإشعارات المرسلة
        if ($notification->isSent()) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف الإشعارات المرسلة');
        }

        $notification->delete();

        return redirect()->route('super_admin.notifications.index')
            ->with('success', 'تم حذف الإشعار بنجاح');
    }

    /**
     * إرسال الإشعار فوراً
     */
    public function send(Notification $notification)
    {
        if ($notification->isSent()) {
            return redirect()->back()
                ->with('error', 'تم إرسال هذا الإشعار من قبل');
        }

        try {
            $this->sendNotification($notification);

            return redirect()->back()
                ->with('success', 'تم إرسال الإشعار بنجاح');

        } catch (\Exception $e) {
            Log::error('Failed to send notification', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إرسال الإشعار');
        }
    }

    /**
     * إلغاء الإشعار المجدول
     */
    public function cancel(Notification $notification)
    {
        if (!$notification->isScheduled()) {
            return redirect()->back()
                ->with('error', 'لا يمكن إلغاء هذا الإشعار');
        }

        $notification->update([
            'status' => 'cancelled',
            'updated_by' => auth('super_admin')->id()
        ]);

        return redirect()->back()
            ->with('success', 'تم إلغاء الإشعار بنجاح');
    }

    /**
     * إرسال الإشعار (الدالة الأساسية)
     */
    private function sendNotification(Notification $notification)
    {
        $notification->update(['status' => 'sending']);

        $recipients = $notification->recipients()->with('recipient')->get();

        foreach ($recipients as $recipient) {
            $channels = array_intersect(
                $notification->channels,
                $recipient->getPreferredChannels()
            );

            foreach ($channels as $channel) {
                $delivery = NotificationDelivery::create([
                    'notification_id' => $notification->id,
                    'recipient_id' => $recipient->id,
                    'channel' => $channel,
                    'status' => 'pending'
                ]);

                $this->deliverToChannel($delivery, $notification, $recipient, $channel);
            }
        }

        $notification->update([
            'status' => 'sent',
            'sent_at' => now()
        ]);
    }

    /**
     * تسليم الإشعار عبر قناة محددة
     */
    private function deliverToChannel($delivery, $notification, $recipient, $channel)
    {
        try {
            $delivery->markAsSending();

            switch ($channel) {
                case 'in_app':
                    $this->deliverInApp($notification, $recipient);
                    break;
                
                case 'email':
                    $this->deliverEmail($notification, $recipient);
                    break;
                
                case 'sms':
                    $this->deliverSMS($notification, $recipient);
                    break;
                
                case 'push':
                    $this->deliverPush($notification, $recipient);
                    break;
                
                case 'voice':
                    $this->deliverVoice($notification, $recipient);
                    break;
            }

            $delivery->markAsDelivered();

        } catch (\Exception $e) {
            $delivery->markAsFailed($e->getMessage());
            
            Log::error('Failed to deliver notification', [
                'delivery_id' => $delivery->id,
                'channel' => $channel,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * تسليم الإشعار داخل التطبيق
     */
    private function deliverInApp($notification, $recipient)
    {
        // تحديث حالة المستلم
        $recipient->update(['status' => 'delivered']);
        
        // يمكن إضافة منطق إضافي هنا مثل WebSocket للإشعارات الفورية
        return true;
    }

    /**
     * تسليم الإشعار عبر البريد الإلكتروني
     */
    private function deliverEmail($notification, $recipient)
    {
        // منطق إرسال البريد الإلكتروني
        // يمكن استخدام Laravel Mail أو خدمات خارجية
        
        $recipientUser = $recipient->recipient;
        if (!$recipientUser->email) {
            throw new \Exception('عنوان البريد الإلكتروني غير متوفر');
        }

        // محاكاة إرسال البريد الإلكتروني
        Log::info('Email notification sent', [
            'to' => $recipientUser->email,
            'subject' => $notification->title,
            'message' => $notification->message
        ]);

        return true;
    }

    /**
     * تسليم الإشعار عبر الرسائل النصية
     */
    private function deliverSMS($notification, $recipient)
    {
        $recipientUser = $recipient->recipient;
        if (!$recipientUser->phone) {
            throw new \Exception('رقم الهاتف غير متوفر');
        }

        // محاكاة إرسال الرسالة النصية
        Log::info('SMS notification sent', [
            'to' => $recipientUser->phone,
            'message' => $notification->message
        ]);

        return true;
    }

    /**
     * تسليم الإشعار الفوري
     */
    private function deliverPush($notification, $recipient)
    {
        // منطق الإشعارات الفورية للهواتف الذكية
        Log::info('Push notification sent', [
            'recipient_id' => $recipient->recipient_id,
            'title' => $notification->title,
            'message' => $notification->message
        ]);

        return true;
    }

    /**
     * تسليم الإشعار الصوتي
     */
    private function deliverVoice($notification, $recipient)
    {
        $recipientUser = $recipient->recipient;
        if (!$recipientUser->phone) {
            throw new \Exception('رقم الهاتف غير متوفر');
        }

        // محاكاة المكالمة الصوتية
        Log::info('Voice notification sent', [
            'to' => $recipientUser->phone,
            'message' => $notification->message
        ]);

        return true;
    }

    /**
     * معالجة الإشعارات المجدولة
     */
    public function processScheduledNotifications()
    {
        $dueNotifications = Notification::dueForSending()->get();

        foreach ($dueNotifications as $notification) {
            try {
                $this->sendNotification($notification);
                
                Log::info('Scheduled notification sent', [
                    'notification_id' => $notification->id
                ]);

            } catch (\Exception $e) {
                Log::error('Failed to send scheduled notification', [
                    'notification_id' => $notification->id,
                    'error' => $e->getMessage()
                ]);

                $notification->update(['status' => 'failed']);
            }
        }

        return response()->json([
            'processed' => $dueNotifications->count(),
            'message' => 'تم معالجة الإشعارات المجدولة'
        ]);
    }

    /**
     * إحصائيات الإشعارات
     */
    public function statistics(Request $request)
    {
        $period = $request->get('period', '30'); // آخر 30 يوم افتراضياً
        $startDate = Carbon::now()->subDays($period);

        $stats = [
            'total_notifications' => Notification::where('created_at', '>=', $startDate)->count(),
            'sent_notifications' => Notification::where('status', 'sent')
                ->where('created_at', '>=', $startDate)->count(),
            'scheduled_notifications' => Notification::where('status', 'scheduled')->count(),
            'total_deliveries' => NotificationDelivery::where('created_at', '>=', $startDate)->count(),
            'successful_deliveries' => NotificationDelivery::where('status', 'delivered')
                ->where('created_at', '>=', $startDate)->count(),
            'failed_deliveries' => NotificationDelivery::where('status', 'failed')
                ->where('created_at', '>=', $startDate)->count(),
        ];

        // إحصائيات حسب النوع
        $typeStats = Notification::where('created_at', '>=', $startDate)
            ->select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->get();

        // إحصائيات حسب القناة
        $channelStats = NotificationDelivery::where('created_at', '>=', $startDate)
            ->select('channel', 'status', DB::raw('COUNT(*) as count'))
            ->groupBy('channel', 'status')
            ->get();

        // الإشعارات اليومية
        $dailyStats = Notification::where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return view('super_admin.notifications.statistics', compact(
            'stats',
            'typeStats',
            'channelStats',
            'dailyStats',
            'period',
            'startDate'
        ));
    }

    /**
     * تصدير تقرير الإشعارات
     */
    public function exportReport(Request $request)
    {
        $format = $request->get('format', 'csv');
        $period = $request->get('period', '30');
        $startDate = Carbon::now()->subDays($period);

        $notifications = Notification::with(['createdBy'])
            ->withCount(['recipients', 'deliveries'])
            ->where('created_at', '>=', $startDate)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($format === 'csv') {
            $filename = "notifications_report_" . date('Y-m-d_H-i-s') . ".csv";
            
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($notifications) {
                $file = fopen('php://output', 'w');
                
                // إضافة BOM للدعم العربي
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // العناوين
                fputcsv($file, [
                    'العنوان', 'النوع', 'الأولوية', 'الحالة', 
                    'عدد المستلمين', 'عدد التسليمات', 'تاريخ الإنشاء'
                ]);
                
                foreach ($notifications as $notification) {
                    fputcsv($file, [
                        $notification->title,
                        $notification->type_text,
                        $notification->priority_text,
                        $notification->status_text,
                        $notification->recipients_count,
                        $notification->deliveries_count,
                        $notification->created_at->format('Y-m-d H:i:s')
                    ]);
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return response()->json($notifications);
    }
}

