@extends('layouts.app')

@section('title', 'لوحة التحكم - نظام الانتخابات')
@section('page-title', 'لوحة التحكم الرئيسية')
@section('page-description', 'نظرة عامة على إحصائيات النظام')

@section('content')
<div class="row mt-4">
    <!-- إحصائيات سريعة -->
    <div class="col-md-3 mb-4 ">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">السوبرادمن</h5>
                        <h2 class="mb-0">{{ $stats['super_admins_count'] }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-person-badge" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('super_admin.super_admins.index') }}" class="text-white text-decoration-none">
                    <small>عرض التفاصيل <i class="bi bi-arrow-left"></i></small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">الادمن</h5>
                        <h2 class="mb-0">{{ $stats['admins_count'] }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-person-gear" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('super_admin.admins.index') }}" class="text-white text-decoration-none">
                    <small>عرض التفاصيل <i class="bi bi-arrow-left"></i></small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">المساعدين</h5>
                        <h2 class="mb-0">{{ $stats['assistants_count'] }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-person-check" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('super_admin.assistants.index') }}" class="text-white text-decoration-none">
                    <small>عرض التفاصيل <i class="bi bi-arrow-left"></i></small>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">الناخبين</h5>
                        <h2 class="mb-0">{{ $stats['voters_count'] }}</h2>
                    </div>
                    <div>
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('super_admin.voters.index') }}" class="text-white text-decoration-none">
                    <small>عرض التفاصيل <i class="bi bi-arrow-left"></i></small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات الناخبين -->
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-person-check-fill text-success" style="font-size: 3rem;"></i>
                <h3 class="mt-3">{{ $stats['active_voters_count'] }}</h3>
                <p class="text-muted">ناخب نشط</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                <h3 class="mt-3">{{ $stats['eligible_voters_count'] }}</h3>
                <p class="text-muted">مؤهل للتصويت</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-check2-square text-info" style="font-size: 3rem;"></i>
                <h3 class="mt-3">{{ $stats['voted_count'] }}</h3>
                <p class="text-muted">صوت بالفعل</p>
            </div>
        </div>
    </div>
</div>

<!-- الإجراءات السريعة -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning-fill me-2"></i>
                    الإجراءات السريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('super_admin.super_admins.create') }}" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            إضافة سوبرادمن
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('super_admin.admins.create') }}" class="btn btn-success w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            إضافة ادمن
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('super_admin.assistants.create') }}" class="btn btn-info w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            إضافة مساعد
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('super_admin.voters.create') }}" class="btn btn-warning w-100">
                            <i class="bi bi-plus-circle me-2"></i>
                            إضافة ناخب
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('super_admin.notifications.index') }}" class="btn btn-danger w-100">
                            <i class="bi bi-bell me-2"></i>
                            إدارة الإشعارات
                        </a>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الإشعارات الأخيرة -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bell me-2"></i>
                    الإشعارات الأخيرة
                </h5>
            </div>
            <div class="card-body">
                @forelse($notifications as $notification)
                    <div class="alert alert-light border-start border-4 border-primary p-3 mb-2 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-0">
                                <strong>{{ $notification->data['message'] ?? 'New Notification' }}</strong>
                                <br>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </p>
                        </div>
                        <div>
                            @if(isset($notification->data['link']))
                                <a href="{{ $notification->data['link'] }}" class="btn btn-sm btn-primary">View</a>
                            @endif
                            <a href="{{ route('super_admin.notifications.markAsRead', $notification->id) }}" class="btn btn-sm btn-outline-secondary">Mark as Read</a>
                        </div>
                    </div>
                @empty
                    <p class="text-center">لا توجد إشعارات جديدة.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- معلومات النظام -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    معلومات النظام
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>إصدار Laravel:</strong> 10.x</p>
                        <p><strong>قاعدة البيانات:</strong> MySQL</p>
                        <p><strong>آخر تحديث:</strong> {{ date('Y-m-d H:i:s') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>المستخدم الحالي:</strong> {{ auth('super_admin')->user()->name ?? 'غير محدد' }}</p>
                        <p><strong>البريد الإلكتروني:</strong> {{ auth('super_admin')->user()->email ?? 'غير محدد' }}</p>
                        <p><strong>وقت تسجيل الدخول:</strong> {{ date('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
