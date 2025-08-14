@extends('layouts.app')

@section('title', 'قائمة الناخبين')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">قائمة الناخبين</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('assistant.dashboard') }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item active" aria-current="page">الناخبين</li>
            </ol>
        </nav>
    </div>

    <!-- Search and Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">البحث والفلترة</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('assistant.voters.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="اسم، بريد إلكتروني، أو رقم هوية..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>معلق</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="gender" class="form-label">الجنس</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="">الكل</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
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
                        <a href="{{ route('assistant.voters.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> إلغاء الفلترة
                        </a>
                        <a href="{{ route('voters.export') }}" class="btn btn-success">
                            <i class="fas fa-download"></i> تصدير
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Voters Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                الناخبين 
                @if(isset($voters) && method_exists($voters, 'total'))
                    ({{ $voters->total() }} ناخب)
                @endif
            </h6>
            <div>
                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#helpModal">
                    <i class="fas fa-question-circle"></i> مساعدة
                </button>
            </div>
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
                                <th>الجنس</th>
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
                                            <div class="icon-circle bg-primary">
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
                                <td>
                                    <code class="small">{{ $voter->national_id ?? 'غير محدد' }}</code>
                                </td>
                                <td>
                                    @if($voter->gender == 'male')
                                        <span class="badge bg-primary">ذكر</span>
                                    @elseif($voter->gender == 'female')
                                        <span class="badge bg-pink">أنثى</span>
                                    @else
                                        <span class="badge bg-secondary">غير محدد</span>
                                    @endif
                                </td>
                                <td>{{ $voter->created_at->format('Y-m-d') }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $voter->votes_count ?? 0 }}</span>
                                </td>
                                <td>
                                    @if($voter->status == 'active')
                                        <span class="badge bg-success">نشط</span>
                                    @elseif($voter->status == 'inactive')
                                        <span class="badge bg-secondary">غير نشط</span>
                                    @else
                                        <span class="badge bg-warning">معلق</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('assistant.voters.show', $voter->id) }}" 
                                           class="btn btn-info btn-sm" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-success btn-sm" 
                                                onclick="sendHelp({{ $voter->id }})" title="إرسال مساعدة">
                                            <i class="fas fa-hands-helping"></i>
                                        </button>
                                        <button class="btn btn-primary btn-sm" 
                                                onclick="sendMessage({{ $voter->id }})" title="إرسال رسالة">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(method_exists($voters, 'links'))
                <div class="d-flex justify-content-center">
                    {{ $voters->appends(request()->query())->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-gray-300 mb-3"></i>
                    <h5 class="text-muted">لا يوجد ناخبين</h5>
                    <p class="text-muted">لم يتم العثور على ناخبين يطابقون معايير البحث</p>
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
                                إجمالي الناخبين
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                الناخبين النشطين
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
                                تسجيلات اليوم
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

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                يحتاجون مساعدة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['need_help'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-question-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="helpModalLabel">دليل مساعدة الناخبين</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">كيفية مساعدة الناخبين:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success mr-2"></i>
                                البحث عن الناخب باستخدام الاسم أو البريد الإلكتروني
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success mr-2"></i>
                                عرض تفاصيل الناخب والتحقق من حالته
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success mr-2"></i>
                                إرسال رسائل المساعدة والإرشادات
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success mr-2"></i>
                                متابعة طلبات المساعدة والرد عليها
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-primary">المشاكل الشائعة:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                نسيان كلمة المرور
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                مشاكل في التحقق من البريد الإلكتروني
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                صعوبة في عملية التصويت
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-exclamation-triangle text-warning mr-2"></i>
                                أسئلة حول النتائج والتحقق
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<!-- Send Message Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">إرسال رسالة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="messageForm">
                <div class="modal-body">
                    <input type="hidden" id="voter_id" name="voter_id">
                    <div class="mb-3">
                        <label for="message_subject" class="form-label">الموضوع</label>
                        <input type="text" class="form-control" id="message_subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="message_content" class="form-label">الرسالة</label>
                        <textarea class="form-control" id="message_content" name="content" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إرسال</button>
                </div>
            </form>
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

.bg-pink {
    background-color: #e83e8c !important;
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
            "order": [[ 4, "desc" ]],
            "columnDefs": [
                { "orderable": false, "targets": [7] }
            ]
        });
    }
});

function sendHelp(voterId) {
    // Here you would typically show a help modal or redirect to help page
    alert('سيتم إرسال رسالة مساعدة للناخب رقم: ' + voterId);
}

function sendMessage(voterId) {
    document.getElementById('voter_id').value = voterId;
    const messageModal = new bootstrap.Modal(document.getElementById('messageModal'));
    messageModal.show();
}

// Handle message form submission
document.getElementById('messageForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Here you would typically send the data via AJAX
    fetch('#', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إرسال الرسالة بنجاح');
            const messageModal = bootstrap.Modal.getInstance(document.getElementById('messageModal'));
            messageModal.hide();
            this.reset();
        } else {
            alert('حدث خطأ أثناء إرسال الرسالة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إرسال الرسالة');
    });
});
</script>
@endsection

