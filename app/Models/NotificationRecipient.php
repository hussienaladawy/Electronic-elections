<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'notification_id',
        'recipient_type',
        'recipient_id',
        'preferences',
        'status',
        'read_at',
        'clicked_at'
    ];

    protected $casts = [
        'preferences' => 'array',
        'read_at' => 'datetime',
        'clicked_at' => 'datetime'
    ];

    // العلاقات
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function recipient()
    {
        return $this->morphTo();
    }

    public function deliveries()
    {
        return $this->hasMany(NotificationDelivery::class);
    }

    // الدوال المساعدة
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function isClicked()
    {
        return !is_null($this->clicked_at);
    }

    public function markAsRead()
    {
        if (!$this->isRead()) {
            $this->update(['read_at' => now()]);
        }
        return $this;
    }

    public function markAsClicked()
    {
        if (!$this->isClicked()) {
            $this->update(['clicked_at' => now()]);
        }
        return $this;
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'في الانتظار',
            'delivered' => 'تم التسليم',
            'read' => 'تم القراءة',
            'clicked' => 'تم النقر',
            'failed' => 'فشل'
        ];

        return $statuses[$this->status] ?? 'غير محدد';
    }

    // تحديد القنوات المفضلة للمستلم
    public function getPreferredChannels()
    {
        if ($this->preferences && isset($this->preferences['channels'])) {
            return $this->preferences['channels'];
        }

        // القنوات الافتراضية
        return ['in_app', 'email'];
    }

    // تحديد أفضل وقت للإرسال للمستلم
    public function getPreferredSendTime()
    {
        if ($this->preferences && isset($this->preferences['send_time'])) {
            return $this->preferences['send_time'];
        }

        // الوقت الافتراضي (9 صباحاً)
        return '09:00';
    }

    // تحديد تفضيلات اللغة
    public function getPreferredLanguage()
    {
        if ($this->preferences && isset($this->preferences['language'])) {
            return $this->preferences['language'];
        }

        return 'ar'; // العربية افتراضياً
    }

    // Scopes
    public function scopeForNotification($query, $notificationId)
    {
        return $query->where('notification_id', $notificationId);
    }

    public function scopeByRecipientType($query, $type)
    {
        return $query->where('recipient_type', $type);
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeClicked($query)
    {
        return $query->whereNotNull('clicked_at');
    }
}

