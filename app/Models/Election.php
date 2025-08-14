<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Election extends Model
{
    use HasFactory;
  protected $table = 'elections';
    protected $fillable = [
        'title',
        'description',
        'type',
        'start_date',
        'end_date',
        'registration_start',
        'registration_end',
        'status',
        'settings',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'registration_start' => 'datetime',
        'registration_end' => 'datetime',
        'settings' => 'array',
        'status' => 'string'
    ];

    // العلاقات
    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(SuperAdmin::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(SuperAdmin::class, 'updated_by');
    }

    // الدوال المساعدة
    public function isActive()
    {
        return $this->status === 'active';
    }

   public function isVotingOpen()
{
    return $this->status === 'active';
}


    public function isRegistrationOpen()
    {
        $now = Carbon::now();
        return $this->isActive() && 
               $now->greaterThanOrEqualTo($this->registration_start) && 
               $now->lessThanOrEqualTo($this->registration_end);
    }

    public function getTotalVotesAttribute()
    {
        return $this->votes()->count();
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'draft' => 'مسودة',
            'active' => 'نشط',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي'
        ];

        return $statuses[$this->status] ?? 'غير محدد';
    }

    public function getTypeTextAttribute()
    {
        $types = [
            'presidential' => 'رئاسية',
            'parliamentary' => 'برلمانية',
            'local' => 'محلية',
            'referendum' => 'استفتاء'
        ];

        return $types[$this->type] ?? 'غير محدد';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', Carbon::now());
    }

    public function scopeOngoing($query)
    {
        $now = Carbon::now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    public function scopeCompleted($query)
    {
        return $query->where('end_date', '<', Carbon::now());
    }
}

