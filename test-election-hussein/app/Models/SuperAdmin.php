<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles; // استدعاء الترايت


class SuperAdmin extends Authenticatable
{


    use HasFactory, Notifiable, HasRoles; // تفعيل الترايت

protected $guard_name = 'super_admin';

    protected $table = 'super_admins';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'national_id',
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
        'status' => 'boolean',
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
}

