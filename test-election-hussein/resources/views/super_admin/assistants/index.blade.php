@extends('layouts.app')

@section('title', 'إدارة المساعدين')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إدارة المساعدين</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item active" aria-current="page">المساعدين</li>
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
                            <h6 class="text-primary mb-1">إدارة المساعدين</h6>
                            <p class="text-muted mb-0 small">إضافة وتعديل وحذف المساعدين في النظام</p>
                        </div>
                        <div>
                            <a href="{{ route('super_admin.assistants.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i>
                                إضافة مساعد جديد
                            </a>
                            <a href="{{ route('super_admin.assistants.export') }}" class="btn btn-success">
                                <i class="fas fa-download mr-1"></i>
                                تصدير
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">البحث والفلترة</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('super_admin.assistants.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="اسم، بريد إلكتروني، أو رقم هاتف..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">جميع الحالات</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="department" class="form-label">القسم</label>
                        <select name="department" id="department" class="form-control">
                            <option value="">جميع الأقسام</option>
                            <option value="support" {{ request('department') == 'support' ? 'selected' : '' }}>الدعم الفني</option>
                            <option value="registration" {{ request('department') == 'registration' ? 'selected' : '' }}>التسجيل</option>
                            <option value="monitoring" {{ request('department') == 'monitoring' ? 'selected' : '' }}>المراقبة</option>
                            <option value="general" {{ request('department') == 'general' ? 'selected' : '' }}>عام</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_from" class="form-label">من تاريخ</label>
                        <input type="date" name="date_from" id="date_from" class="form-control" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_to" class="form-label">إلى تاريخ</label>
                        <input type="date" name="date_to" id="date_to" class="form-control" 
                               value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                        <a href="{{ route('super_admin.assistants.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> إلغاء الفلترة
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Assistants Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                قائمة المساعدين 
                @if(isset($assistants) && method_exists($assistants, 'total'))
                    ({{ $assistants->total() }} مساعد)
                @endif
            </h6>
            <div>
                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                    <i class="fas fa-tasks"></i> إجراءات متعددة
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(isset($assistants) && count($assistants) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>المساعد</th>
                                <th>البريد الإلكتروني</th>
                                <th>رقم الهاتف</th>
                                <th>القسم</th>
                                <th>تاريخ التسجيل</th>
                                <th>آخر نشاط</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assistants as $assistant)
                            <tr>
                                <td>
                                    <input type="checkbox" name="selected_assistants[]" 
                                           value="{{ $assistant->id }}" class="form-check-input assistant-checkbox">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-info">
                                                <i class="fas fa-user-friends text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $assistant->name }}</div>
                                            <div class="small text-muted">ID: {{ $assistant->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $assistant->email }}</td>
                                <td>{{ $assistant->phone ?? 'غير محدد' }}</td>
                                <td>
                                    @if($assistant->department == 'support')
                                        <span class="badge bg-primary">الدعم الفني</span>
                                    @elseif($assistant->department == 'registration')
                                        <span class="badge bg-success">التسجيل</span>
                                    @elseif($assistant->department == 'monitoring')
                                        <span class="badge bg-warning">المراقبة</span>
                                    @else
                                        <span class="badge bg-secondary">عام</span>
                                    @endif
                                </td>
                                <td>{{ $assistant->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if($assistant->last_login_at)
                                        <span class="text-success">{{ $assistant->last_login_at->diffForHumans() }}</span>
                                    @else
                                        <span class="text-muted">لم يسجل دخول</span>
                                    @endif
                                </td>
                                <td>
                                    @if($assistant->status)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-danger">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('super_admin.assistants.show', $assistant->id) }}" 
                                           class="btn btn-info btn-sm" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('super_admin.assistants.edit', $assistant->id) }}" 
                                           class="btn btn-warning btn-sm" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($assistant->status)
                                            <button class="btn btn-secondary btn-sm" 
                                                    onclick="toggleStatus({{ $assistant->id }}, 0)" title="إلغاء التفعيل">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-success btn-sm" 
                                                    onclick="toggleStatus({{ $assistant->id }}, 1)" title="تفعيل">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-danger btn-sm" 
                                                onclick="deleteAssistant({{ $assistant->id }})" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($assistants, 'links'))
                <div class="d-flex justify-content-center">
                    {{ $assistants->appends(request()->query())->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-friends fa-4x text-gray-300 mb-3"></i>
                    <h5 class="text-muted">لا يوجد مساعدين</h5>
                    <p class="text-muted">لم يتم العثور على مساعدين يطابقون معايير البحث</p>
                    <a href="{{ route('super_admin.assistants.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus mr-1"></i>
                        إضافة مساعد جديد
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي المساعدين
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-friends fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                المساعدين النشطين
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['active'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                متصلين الآن
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['online'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wifi fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                إضافات اليوم
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['today'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1" aria-labelledby="bulkActionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionsModalLabel">إجراءات متعددة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="bulkActionsForm">
                    <div class="mb-3">
                        <label for="bulk_action" class="form-label">اختر الإجراء</label>
                        <select name="bulk_action" id="bulk_action" class="form-control" required>
                            <option value="">-- اختر الإجراء --</option>
                            <option value="activate">تفعيل المحدد</option>
                            <option value="deactivate">إلغاء تفعيل المحدد</option>
                            <option value="delete">حذف المحدد</option>
                            <option value="export">تصدير المحدد</option>
                        </select>
                    </div>
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        تأكد من اختيار المساعدين المطلوبين قبل تنفيذ الإجراء.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">تنفيذ</button>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

@media (max-width: 768px) {
    .btn-group {
        display: flex;
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-right: 0;
        margin-bottom: 2px;
    }
    
    .btn-group .btn:last-child {
        margin-bottom: 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable if available
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json"
            },
            "pageLength": 25,
            "order": [[ 5, "desc" ]],
            "columnDefs": [
                { "orderable": false, "targets": [0, 8] }
            ]
        });
    }

    // Select All functionality
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.assistant-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});

function toggleStatus(assistantId, status) {
    const action = status ? 'تفعيل' : 'إلغاء تفعيل';
    if (confirm(`هل أنت متأكد من ${action} هذا المساعد؟`)) {
        fetch(`{{ route('super_admin.assistants.toggle-status', '') }}/${assistantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء تحديث الحالة');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحديث الحالة');
        });
    }
}

function deleteAssistant(assistantId) {
    if (confirm('هل أنت متأكد من حذف هذا المساعد؟ هذا الإجراء لا يمكن التراجع عنه.')) {
        fetch(`{{ route('super_admin.assistants.destroy', '') }}/${assistantId}`, {
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
                alert('حدث خطأ أثناء حذف المساعد');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حذف المساعد');
        });
    }
}

function executeBulkAction() {
    const selectedAssistants = Array.from(document.querySelectorAll('.assistant-checkbox:checked')).map(cb => cb.value);
    const action = document.getElementById('bulk_action').value;
    
    if (selectedAssistants.length === 0) {
        alert('يرجى اختيار مساعد واحد على الأقل');
        return;
    }
    
    if (!action) {
        alert('يرجى اختيار إجراء');
        return;
    }
    
    if (confirm(`هل أنت متأكد من تنفيذ هذا الإجراء على ${selectedAssistants.length} مساعد؟`)) {
        fetch('{{ route("super_admin.assistants.bulk-action") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                assistants: selectedAssistants,
                action: action
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('حدث خطأ أثناء تنفيذ الإجراء');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تنفيذ الإجراء');
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

