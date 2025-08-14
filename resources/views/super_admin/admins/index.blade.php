@extends('layouts.app')

@section('title', 'إدارة الأدمن')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إدارة الأدمن</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item active" aria-current="page">الأدمن</li>
            </ol>
        </nav>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-primary mb-1">إدارة الأدمن</h6>
                            <p class="text-muted mb-0 small">إضافة وتعديل وحذف الأدمن في النظام</p>
                        </div>
                        <div>
                            <a href="{{ route('super_admin.admins.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i>
                                إضافة أدمن جديد
                            </a>
                            <a href="{{ route('super_admin.admins.export') }}" class="btn btn-success">
                                <i class="fas fa-download mr-1"></i>
                                تصدير
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admins Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                قائمة الأدمن 
                @if(isset($admins) && method_exists($admins, 'total'))
                    ({{ $admins->total() }} أدمن)
                @endif
            </h6>
        </div>
        <div class="card-body">
            @if(isset($admins) && count($admins) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>الأدمن</th>
                                <th>البريد الإلكتروني</th>
                                <th>رقم الهاتف</th>
                                <th>تاريخ التسجيل</th>
                                <th>آخر نشاط</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-warning">
                                                <i class="fas fa-user-tie text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $admin->name }}</div>
                                            <div class="small text-muted">ID: {{ $admin->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->phone ?? 'غير محدد' }}</td>
                                <td>{{ $admin->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if($admin->last_login_at)
                                        <span class="text-success">{{ $admin->last_login_at->diffForHumans() }}</span>
                                    @else
                                        <span class="text-muted">لم يسجل دخول</span>
                                    @endif
                                </td>
                                <td>
                                    @if($admin->status)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-danger">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('super_admin.admins.show', $admin->id) }}" 
                                           class="btn btn-info btn-sm" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('super_admin.admins.edit', $admin->id) }}" 
                                           class="btn btn-warning btn-sm" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm" 
                                                onclick="deleteAdmin({{ $admin->id }})" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-tie fa-4x text-gray-300 mb-3"></i>
                    <h5 class="text-muted">لا يوجد أدمن</h5>
                    <p class="text-muted">لم يتم إضافة أي أدمن بعد</p>
                    <a href="{{ route('super_admin.admins.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        إضافة أدمن جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function deleteAdmin(adminId) {
    if (confirm('هل أنت متأكد من حذف هذا الأدمن؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        fetch(`{{ route('super_admin.admins.destroy', '') }}/${adminId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء حذف الأدمن');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حذف الأدمن');
        });
    }
}
</script>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection

