@extends('layouts.app')

@section('title', 'تفاصيل المساعد')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تفاصيل المساعد: {{ $assistant->name ?? 'غير محدد' }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route('super_admin.assistants.index') }}">المساعدين</a></li>
                <li class="breadcrumb-item active" aria-current="page">التفاصيل</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">المعلومات الأساسية</h6>
                    <div>
                        @if(isset($assistant->status) && $assistant->status)
                            <span class="badge bg-success">نشط</span>
                        @else
                            <span class="badge bg-danger">غير نشط</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>الاسم الكامل:</strong>
                            <p class="text-muted">{{ $assistant->name ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>البريد الإلكتروني:</strong>
                            <p class="text-muted">{{ $assistant->email ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>رقم الهاتف:</strong>
                            <p class="text-muted">{{ $assistant->phone ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>رقم الهوية:</strong>
                            <p class="text-muted">{{ $assistant->national_id ?? 'غير محدد' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>القسم:</strong>
                            <p class="text-muted">
                                @if(isset($assistant->department))
                                    @switch($assistant->department)
                                        @case('support')
                                            <span class="badge bg-primary">الدعم الفني</span>
                                            @break
                                        @case('registration')
                                            <span class="badge bg-success">التسجيل</span>
                                            @break
                                        @case('monitoring')
                                            <span class="badge bg-warning">المراقبة</span>
                                            @break
                                        @case('general')
                                            <span class="badge bg-secondary">عام</span>
                                            @break
                                        @default
                                            {{ $assistant->department }}
                                    @endswitch
                                @else
                                    غير محدد
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>تاريخ التسجيل:</strong>
                            <p class="text-muted">{{ isset($assistant->created_at) ? $assistant->created_at->format('Y-m-d H:i') : 'غير محدد' }}</p>
                        </div>
                        @if(isset($assistant->address) && $assistant->address)
                        <div class="col-12 mb-3">
                            <strong>العنوان:</strong>
                            <p class="text-muted">{{ $assistant->address }}</p>
                        </div>
                        @endif
                        @if(isset($assistant->notes) && $assistant->notes)
                        <div class="col-12 mb-3">
                            <strong>ملاحظات:</strong>
                            <p class="text-muted">{{ $assistant->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Activity Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">إحصائيات النشاط</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card border-left-primary h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                طلبات المساعدة
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $assistant->help_requests_count ?? 0 }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-question-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card border-left-success h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                طلبات مكتملة
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $assistant->completed_requests_count ?? 0 }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card border-left-info h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                متوسط وقت الاستجابة
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $assistant->avg_response_time ?? '0' }} دقيقة
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="card border-left-warning h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                تقييم الأداء
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $assistant->performance_rating ?? '0' }}/5
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-star fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">النشاط الأخير</h6>
                </div>
                <div class="card-body">
                    @if(isset($recentActivities) && count($recentActivities) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>النشاط</th>
                                        <th>التاريخ</th>
                                        <th>التفاصيل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivities as $activity)
                                    <tr>
                                        <td>{{ $activity->description ?? 'نشاط غير محدد' }}</td>
                                        <td>{{ isset($activity->created_at) ? $activity->created_at->diffForHumans() : 'غير محدد' }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm" onclick="showActivityDetails({{ $activity->id ?? 0 }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-gray-300 mb-3"></i>
                            <h6 class="text-muted">لا يوجد نشاط حديث</h6>
                            <p class="text-muted small">لم يقم المساعد بأي نشاط مؤخراً</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Profile Picture -->
            @if(isset($assistant->avatar) && $assistant->avatar)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">الصورة الشخصية</h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset('storage/' . $assistant->avatar) }}" alt="صورة المساعد" class="img-fluid rounded-circle" style="max-width: 200px;">
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">إجراءات سريعة</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('super_admin.assistants.edit', $assistant->id ?? 1) }}" class="btn btn-warning">
                            <i class="fas fa-edit mr-1"></i>
                            تعديل المعلومات
                        </a>
                        
                        @if(isset($assistant->status) && $assistant->status)
                            <button class="btn btn-secondary" onclick="toggleStatus({{ $assistant->id ?? 1 }}, 0)">
                                <i class="fas fa-ban mr-1"></i>
                                إلغاء التفعيل
                            </button>
                        @else
                            <button class="btn btn-success" onclick="toggleStatus({{ $assistant->id ?? 1 }}, 1)">
                                <i class="fas fa-check mr-1"></i>
                                تفعيل الحساب
                            </button>
                        @endif
                        
                        <button class="btn btn-info" onclick="sendPasswordReset({{ $assistant->id ?? 1 }})">
                            <i class="fas fa-key mr-1"></i>
                            إعادة تعيين كلمة المرور
                        </button>
                        
                        <button class="btn btn-primary" onclick="sendMessage({{ $assistant->id ?? 1 }})">
                            <i class="fas fa-envelope mr-1"></i>
                            إرسال رسالة
                        </button>
                        
                        <hr>
                        
                        <button class="btn btn-danger" onclick="deleteAssistant({{ $assistant->id ?? 1 }})">
                            <i class="fas fa-trash mr-1"></i>
                            حذف المساعد
                        </button>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات إضافية</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <strong>آخر تسجيل دخول:</strong>
                            <span class="text-muted d-block">
                                @if(isset($assistant->last_login_at) && $assistant->last_login_at)
                                    {{ $assistant->last_login_at->diffForHumans() }}
                                @else
                                    لم يسجل دخول بعد
                                @endif
                            </span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>عنوان IP الأخير:</strong>
                            <span class="text-muted d-block">{{ $assistant->last_login_ip ?? 'غير محدد' }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>المتصفح الأخير:</strong>
                            <span class="text-muted d-block">{{ $assistant->last_user_agent ?? 'غير محدد' }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>حالة التحقق:</strong>
                            <span class="text-muted d-block">
                                @if(isset($assistant->email_verified_at) && $assistant->email_verified_at)
                                    <span class="badge bg-success">محقق</span>
                                @else
                                    <span class="badge bg-warning">غير محقق</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">التنقل</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('super_admin.assistants.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list mr-1"></i>
                            قائمة المساعدين
                        </a>
                        <a href="{{ route('super_admin.assistants.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-plus mr-1"></i>
                            إضافة مساعد جديد
                        </a>
                        <a href="{{ route('super_admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-tachometer-alt mr-1"></i>
                            لوحة التحكم
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

function sendPasswordReset(assistantId) {
    if (confirm('هل تريد إرسال رابط إعادة تعيين كلمة المرور للمساعد؟')) {
        fetch(`{{ route('super_admin.assistants.send-password-reset', '') }}/${assistantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إرسال رابط إعادة تعيين كلمة المرور بنجاح');
            } else {
                alert('حدث خطأ أثناء إرسال الرابط');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إرسال الرابط');
        });
    }
}

function sendMessage(assistantId) {
    const message = prompt('اكتب الرسالة التي تريد إرسالها للمساعد:');
    if (message && message.trim()) {
        fetch(`{{ route('super_admin.assistants.send-message', '') }}/${assistantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: message.trim() })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إرسال الرسالة بنجاح');
            } else {
                alert('حدث خطأ أثناء إرسال الرسالة');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إرسال الرسالة');
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
                window.location.href = '{{ route("super_admin.assistants.index") }}';
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

function showActivityDetails(activityId) {
    // This would typically open a modal or navigate to a detailed view
    alert('عرض تفاصيل النشاط رقم: ' + activityId);
}
</script>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection

