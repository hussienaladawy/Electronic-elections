@extends('layouts.app')

@section('title', 'إدارة الناخبين')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إدارة الناخبين</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item active" aria-current="page">الناخبين</li>
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
                            <h6 class="text-primary mb-1">إدارة الناخبين</h6>
                            <p class="text-muted mb-0 small">إضافة وتعديل وحذف الناخبين في النظام</p>
                        </div>
                        <div>
                            <a href="{{ route('super_admin.voters.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i>
                                إضافة ناخب جديد
                            </a>
                            <a href="{{ route('super_admin.voters.export') }}" class="btn btn-success">
                                <i class="fas fa-download mr-1"></i>
                                تصدير
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Voters Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                قائمة الناخبين 
                @if(isset($voters) && method_exists($voters, 'total'))
                    ({{ $voters->total() }} ناخب)
                @endif
            </h6>
        </div>
        <div class="card-body">
            @if(isset($voters) && count($voters) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>الناخب</th>
                                <th>البريد الإلكتروني</th>
                                <th>رقم الهوية</th>
                                <th>تاريخ التسجيل</th>
                                <th>عدد الأصوات</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($voters as $voter)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-success">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $voter->name }}</div>
                                            <div class="small text-muted">ID: {{ $voter->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $voter->email }}</td>
                                <td>{{ $voter->national_id ?? 'غير محدد' }}</td>
                                <td>{{ $voter->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $voter->votes_count ?? 0 }}</span>
                                </td>
                                <td>
                                    @if($voter->status)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-danger">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('super_admin.voters.show', $voter->id) }}" 
                                           class="btn btn-info btn-sm" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('super_admin.voters.edit', $voter->id) }}" 
                                           class="btn btn-warning btn-sm" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm" 
                                                onclick="deleteVoter({{ $voter->id }})" title="حذف">
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
                    <i class="fas fa-users fa-4x text-gray-300 mb-3"></i>
                    <h5 class="text-muted">لا يوجد ناخبين</h5>
                    <p class="text-muted">لم يتم تسجيل أي ناخبين بعد</p>
                    <a href="{{ route('super_admin.voters.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        إضافة ناخب جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function deleteVoter(voterId) {
    if (confirm('هل أنت متأكد من حذف هذا الناخب؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        fetch(`{{ route('super_admin.voters.destroy', '') }}/${voterId}`, {
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
                alert('حدث خطأ أثناء حذف الناخب');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حذف الناخب');
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

