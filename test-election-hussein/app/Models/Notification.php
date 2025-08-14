<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'type',
        'priority',
        'target_audience',
        'channels',
        'scheduled_at',
        'sent_at',
        'status',
        'metadata',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'channels' => 'array',
        'target_audience' => 'array',
        'metadata' => 'array',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'status' => 'string'
    ];

    // العلاقات
    public function createdBy()
    {
        return $this->belongsTo(SuperAdmin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(SuperAdmin::class, 'updated_by');
    }

    public function recipients()
    {
        return $this->hasMany(NotificationRecipient::class);
    }

    public function deliveries()
    {
        return $this->hasMany(NotificationDelivery::class);
    }

    // الدوال المساعدة
    public function isScheduled()
    {
        return $this->status === 'scheduled';
    }

    public function isSent()
    {
        return $this->status === 'sent';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'draft' => 'مسودة',
            'scheduled' => 'مجدول',
            'pending' => 'في الانتظار',
            'sending' => 'جاري الإرسال',
            'sent' => 'تم الإرسال',
            'failed' => 'فشل',
            'cancelled' => 'ملغي'
        ];

        return $statuses[$this->status] ?? 'غير محدد';
    }

    public function getTypeTextAttribute()
    {
        $types = [
            'administrative' => 'إداري',
            'educational' => 'تعليمي',
            'security' => 'أمني',
            'reminder' => 'تذكير',
            'announcement' => 'إعلان',
            'alert' => 'تنبيه'
        ];

        return $types[$this->type] ?? 'غير محدد';
    }

    public function getPriorityTextAttribute()
    {
        $priorities = [
            'low' => 'منخفض',
            'normal' => 'عادي',
            'high' => 'عالي',
            'urgent' => 'عاجل'
        ];

        return $priorities[$this->priority] ?? 'عادي';
    }

    public function getTotalRecipientsAttribute()
    {
        return $this->recipients()->count();
    }

    public function getDeliveredCountAttribute()
    {
        return $this->deliveries()->where('status', 'delivered')->count();
    }

    public function getFailedCountAttribute()
    {
        return $this->deliveries()->where('status', 'failed')->count();
    }

    public function getDeliveryRateAttribute()
    {
        $total = $this->total_recipients;
        if ($total == 0) {
            return 0;
        }
        return round(($this->delivered_count / $total) * 100, 2);
    }

    // تحديد المستلمين بناءً على الجمهور المستهدف
    public function determineRecipients()
    {
        $recipients = collect();
        
        if (!$this->target_audience) {
            return $recipients;
        }

        foreach ($this->target_audience as $audience) {
            switch ($audience['type']) {
                case 'all_voters':
                    $recipients = $recipients->merge(
                        Voter::where('status', true)->get()
                    );
                    break;
                
                case 'all_admins':
                    $recipients = $recipients->merge(SuperAdmin::all());
                    $recipients = $recipients->merge(Admin::where('status', true)->get());
                    break;
                
                case 'by_province':
                    $recipients = $recipients->merge(
                        Voter::where('province', $audience['value'])
                              ->where('status', true)
                              ->get()
                    );
                    break;
                
                case 'by_age_group':
                    $ageRange = explode('-', $audience['value']);
                    if (count($ageRange) == 2) {
                        $minAge = (int)$ageRange[0];
                        $maxAge = (int)$ageRange[1];
                        $minDate = Carbon::now()->subYears($maxAge + 1);
                        $maxDate = Carbon::now()->subYears($minAge);
                        
                        $recipients = $recipients->merge(
                            Voter::whereBetween('date_of_birth', [$minDate, $maxDate])
                                  ->where('status', true)
                                  ->get()
                        );
                    }
                    break;
                
                case 'by_gender':
                    $recipients = $recipients->merge(
                        Voter::where('gender', $audience['value'])
                              ->where('status', true)
                              ->get()
                    );
                    break;
                
                case 'specific_users':
                    if (isset($audience['user_ids'])) {
                        $recipients = $recipients->merge(
                            Voter::whereIn('id', $audience['user_ids'])
                                  ->where('status', true)
                                  ->get()
                        );
                    }
                    break;
            }
        }

        return $recipients->unique('id');
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeDueForSending($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('scheduled_at', '<=', Carbon::now());
    }
}

