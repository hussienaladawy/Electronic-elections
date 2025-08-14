<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationDelivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_id',
        'recipient_id',
        'channel',
        'status',
        'sent_at',
        'delivered_at',
        'failed_at',
        'error_message',
        'metadata'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'failed_at' => 'datetime',
        'metadata' => 'array'
    ];

    // العلاقات
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function recipient()
    {
        return $this->belongsTo(NotificationRecipient::class);
    }

    // الدوال المساعدة
    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    public function isFailed()
    {
        return $this->status === 'failed';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isSending()
    {
        return $this->status === 'sending';
    }

    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now()
        ]);
        return $this;
    }

    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'status' => 'failed',
            'failed_at' => now(),
            'error_message' => $errorMessage
        ]);
        return $this;
    }

    public function markAsSending()
    {
        $this->update([
            'status' => 'sending',
            'sent_at' => now()
        ]);
        return $this;
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'في الانتظار',
            'sending' => 'جاري الإرسال',
            'delivered' => 'تم التسليم',
            'failed' => 'فشل',
            'bounced' => 'مرتد',
            'rejected' => 'مرفوض'
        ];

        return $statuses[$this->status] ?? 'غير محدد';
    }

    public function getChannelTextAttribute()
    {
        $channels = [
            'in_app' => 'داخل التطبيق',
            'email' => 'البريد الإلكتروني',
            'sms' => 'رسالة نصية',
            'push' => 'إشعار فوري',
            'voice' => 'مكالمة صوتية'
        ];

        return $channels[$this->channel] ?? 'غير محدد';
    }

    public function getDeliveryTimeAttribute()
    {
        if ($this->delivered_at) {
            return $this->delivered_at->diffInSeconds($this->sent_at ?? $this->created_at);
        }
        return null;
    }

    // Scopes
    public function scopeForNotification($query, $notificationId)
    {
        return $query->where('notification_id', $notificationId);
    }

    public function scopeByChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}

