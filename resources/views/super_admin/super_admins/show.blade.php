@extends('layouts.app')

@section('title', 'عرض تفاصيل السوبرادمن')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h3 mb-4 text-primary fw-bold">تفاصيل السوبرادمن</h1>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0 fw-semibold">معلومات السوبرادمن</h6>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <p class="border-bottom pb-2"><strong>الاسم:</strong> {{ $superAdmin->name }}</p>
                    <p class="border-bottom pb-2"><strong>البريد الإلكتروني:</strong> {{ $superAdmin->email }}</p>
                    <p class="border-bottom pb-2"><strong>الهاتف:</strong> {{ $superAdmin->phone }}</p>
                    <p class="border-bottom pb-2"><strong>الرقم القومي:</strong> {{ $superAdmin->national_id }}</p>
                </div>
                <div class="col-md-6">
                    <p class="border-bottom pb-2"><strong>الحالة:</strong>
                        @if($superAdmin->status)
                            <span class="badge bg-success">نشط</span>
                        @else
                            <span class="badge bg-danger">غير نشط</span>
                        @endif
                    </p>
                    <p class="border-bottom pb-2"><strong>تاريخ الإنشاء:</strong> {{ $superAdmin->created_at->format('Y-m-d H:i') }}</p>
                    <p class="border-bottom pb-2"><strong>تاريخ آخر تحديث:</strong> {{ $superAdmin->updated_at->format('Y-m-d H:i') }}</p>
                    <p class="border-bottom pb-2"><strong>تم الإنشاء بواسطة:</strong> {{ $superAdmin->createdBy->name ?? 'غير متوفر' }}</p>
                    <p class="border-bottom pb-2"><strong>تم التحديث بواسطة:</strong> {{ $superAdmin->updatedBy->name ?? 'غير متوفر' }}</p>
                </div>
            </div>

            <hr>

            <div class="d-flex gap-3">
                <a href="{{ route('super_admin.super_admins.edit', $superAdmin->id) }}" 
                   class="btn btn-warning btn-lg d-flex align-items-center px-4">
                    <i class="bi bi-pencil-square me-2 fs-5"></i> تعديل
                </a>
                <a href="{{ route('super_admin.super_admins.index') }}" 
                   class="btn btn-secondary btn-lg d-flex align-items-center px-4">
                    <i class="bi bi-arrow-left me-2 fs-5"></i> العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        padding-top: 70px; /* ضبط المسافة أسفل النافبار */
    }
</style>
@endsection
