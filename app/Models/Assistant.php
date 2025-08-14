<?php

namespace App\Models;


use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Assistant extends Authenticatable
{
    use HasRoles;
    use HasFactory, Notifiable, \Spatie\Permission\Traits\HasRoles;
protected $guard_name = 'assistant';
    protected $table = 'assistants';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'national_id',
        'assigned_admin_id',
        'work_area',
        'shift_time',
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
    public function assignedAdmin()
    {
        return $this->belongsTo(Admin::class, 'assigned_admin_id');
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
        return $this->status == 1;
    }

    public function getStatusTextAttribute()
    {
        return $this->status ? 'نشط' : 'غير نشط';
    }
}

