<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Voter extends Authenticatable
{
    
    use HasFactory, Notifiable, HasRoles;

    protected $table = 'voters';
protected $guard_name = 'voter';


    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'national_id',
        'birth_date',
        'gender',
        'address',
        'city',
        // 'district',
        'voting_center_id',
        'is_eligible',
        'has_voted',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birth_date' => 'date',
        'status' => 'boolean',
        'is_eligible' => 'boolean',
        'has_voted' => 'boolean',
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

    // الدوال المساعدة
    public function isActive()
    {
        return $this->status == 1;
    }

    public function getStatusTextAttribute()
    {
        return $this->status ? 'نشط' : 'غير نشط';
    }

    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    public function canVote()
    {
        return $this->is_eligible && !$this->has_voted && $this->isActive();
    }

    public function getGenderTextAttribute()
    {
        return $this->gender == 'male' ? 'ذكر' : 'أنثى';
    }
}

