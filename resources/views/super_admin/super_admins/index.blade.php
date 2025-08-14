@extends('layouts.app')

@section('title', 'إدارة السوبرادمن - نظام الانتخابات')
@section('page-title', 'إدارة السوبرادمن')
@section('page-description', 'عرض وإدارة جميع حسابات السوبرادمن')

@section('page-actions')
<a href="{{ route('super_admin.super_admins.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-2"></i> إضافة سوبرادمن جديد
</a>
@endsection

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-person-badge me-2"></i>
                قائمة السوبرادمن
            </h5>
            <span class="badge bg-light text-dark px-3 py-2">{{ $superAdmins->total() }} سوبرادمن</span>
        </div>
    </div>

    <div class="card-body">
        @if($superAdmins->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>رقم الهاتف</th>
                            <th>الرقم الوطني</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($superAdmins as $superAdmin)
                        <tr>
                            <td>{{ $superAdmin->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                         style="width: 40px; height: 40px;">
                                        {{ mb_substr($superAdmin->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <strong>{{ $superAdmin->name }}</strong>
                                        @if($superAdmin->createdBy)
                                            <br><small class="text-muted">أنشأه: {{ $superAdmin->createdBy->name }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $superAdmin->email }}</td>
                            <td>{{ $superAdmin->phone }}</td>
                            <td>{{ $superAdmin->national_id }}</td>
                            <td>
                                <span class="badge {{ $superAdmin->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $superAdmin->status ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td>{{ $superAdmin->created_at->format('Y-m-d H:i') }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('super_admin.super_admins.show', $superAdmin) }}" 
                                       class="btn btn-sm btn-outline-info" title="عرض">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('super_admin.super_admins.edit', $superAdmin) }}" 
                                       class="btn btn-sm btn-outline-warning" title="تعديل">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($superAdmin->id != auth('super_admin')->id())
                                    <form action="{{ route('super_admin.super_admins.destroy', $superAdmin) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirmDelete('هل أنت متأكد من حذف هذا السوبرادمن؟')" 
                                                title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $superAdmins->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-person-badge text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">لا توجد حسابات سوبرادمن</h4>
                <p class="text-muted">لم يتم إنشاء أي حسابات سوبرادمن بعد</p>
                <a href="{{ route('super_admin.super_admins.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i> إضافة أول سوبرادمن
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    body {
        padding-top: 70px; /* لضبط المسافة أسفل النافبار */
    }
    .avatar {
        font-weight: bold;
        font-size: 1.2rem;
    }
</style>
@endsection
