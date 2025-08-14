<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'admin';
    protected $table = 'admins';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'national_id',
        'department',
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
