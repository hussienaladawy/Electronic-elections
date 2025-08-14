@extends("layouts.app")

@section("title", "تفاصيل الناخب")

@section("content")
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تفاصيل الناخب: {{ $voter->name ?? "غير محدد" }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route("admin.voters.index") }}">الناخبين</a></li>
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
                        @if(isset($voter->status) && $voter->status)
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
                            <p class="text-muted">{{ $voter->name ?? "غير محدد" }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>البريد الإلكتروني:</strong>
                            <p class="text-muted">{{ $voter->email ?? "غير محدد" }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>رقم الهاتف:</strong>
                            <p class="text-muted">{{ $voter->phone ?? "غير محدد" }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>رقم الهوية:</strong>
                            <p class="text-muted">{{ $voter->national_id ?? "غير محدد" }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>تاريخ الميلاد:</strong>
                            <p class="text-muted">{{ isset($voter->birth_date) ? $voter->birth_date : "غير محدد" }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>النوع:</strong>
                            <p class="text-muted">
                                @if(isset($voter->gender))
                                    @if($voter->gender == "male")
                                        <span class="badge bg-primary">ذكر</span>
                                    @elseif($voter->gender == "female")
                                        <span class="badge bg-pink">أنثى</span>
                                    @else
                                        {{ $voter->gender }}
                                    @endif
                                @else
                                    غير محدد
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>تاريخ التسجيل:</strong>
                            <p class="text-muted">{{ isset($voter->created_at) ? $voter->created_at->format("Y-m-d H:i") : "غير محدد" }}</p>
                        </div>
                        @if(isset($voter->address) && $voter->address)
                        <div class="col-12 mb-3">
                            <strong>العنوان:</strong>
                            <p class="text-muted">{{ $voter->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Voting History -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تاريخ التصويت</h6>
                </div>
                <div class="card-body">
                    @if(isset($votingHistory) && count($votingHistory) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>الانتخابات</th>
                                        <th>المرشح المختار</th>
                                        <th>تاريخ التصويت</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($votingHistory as $vote)
                                    <tr>
                                        <td>{{ $vote->election->title ?? "غير محدد" }}</td>
                                        <td>{{ $vote->candidate->name ?? "غير محدد" }}</td>
                                        <td>{{ isset($vote->created_at) ? $vote->created_at->format("Y-m-d H:i") : "غير محدد" }}</td>
                                        <td>
                                            @if(isset($vote->is_verified) && $vote->is_verified)
                                                <span class="badge bg-success">محقق</span>
                                            @else
                                                <span class="badge bg-warning">في الانتظار</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-vote-yea fa-3x text-gray-300 mb-3"></i>
                            <h6 class="text-muted">لا يوجد تاريخ تصويت</h6>
                            <p class="text-muted small">لم يقم الناخب بالتصويت في أي انتخابات بعد</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Activity Log -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">سجل النشاط</h6>
                </div>
                <div class="card-body">
                    @if(isset($activityLog) && count($activityLog) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>النشاط</th>
                                        <th>التاريخ</th>
                                        <th>عنوان IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activityLog as $activity)
                                    <tr>
                                        <td>{{ $activity->description ?? "نشاط غير محدد" }}</td>
                                        <td>{{ isset($activity->created_at) ? $activity->created_at->diffForHumans() : "غير محدد" }}</td>
                                        <td>{{ $activity->ip_address ?? "غير محدد" }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history fa-3x text-gray-300 mb-3"></i>
                            <h6 class="text-muted">لا يوجد نشاط مسجل</h6>
                            <p class="text-muted small">لم يقم الناخب بأي نشاط مؤخراً</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Profile Picture -->
            @if(isset($voter->avatar) && $voter->avatar)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">الصورة الشخصية</h6>
                </div>
                <div class="card-body text-center">
                    <img src="{{ asset("storage/" . $voter->avatar) }}" alt="صورة الناخب" class="img-fluid rounded-circle" style="max-width: 200px;">
                </div>
            </div>
            @endif

            <!-- Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">إحصائيات</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="card border-left-primary h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                عدد الأصوات
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $voter->votes_count ?? 0 }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-vote-yea fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="card border-left-success h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                الانتخابات المشارك فيها
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $voter->participated_elections_count ?? 0 }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-poll fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="card border-left-info h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                معدل المشاركة
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $voter->participation_rate ?? 0 }}%
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">إجراءات سريعة</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route("admin.voters.edit", $voter->id ?? 1) }}" class="btn btn-warning">
                            <i class="fas fa-edit mr-1"></i>
                            تعديل المعلومات
                        </a>
                        
                        @if(isset($voter->status) && $voter->status)
                            <button class="btn btn-secondary" onclick="toggleStatus({{ $voter->id ?? 1 }}, 0)">
                                <i class="fas fa-ban mr-1"></i>
                                إلغاء التفعيل
                            </button>
                        @else
                            <button class="btn btn-success" onclick="toggleStatus({{ $voter->id ?? 1 }}, 1)">
                                <i class="fas fa-check mr-1"></i>
                                تفعيل الحساب
                            </button>
                        @endif
                        
                        <button class="btn btn-info" onclick="sendPasswordReset({{ $voter->id ?? 1 }})">
                            <i class="fas fa-key mr-1"></i>
                            إعادة تعيين كلمة المرور
                        </button>
                        
                        <button class="btn btn-primary" onclick="sendMessage({{ $voter->id ?? 1 }})">
                            <i class="fas fa-envelope mr-1"></i>
                            إرسال رسالة
                        </button>
                        
                        <hr>
                        
                        <button class="btn btn-danger" onclick="deleteVoter({{ $voter->id ?? 1 }})">
                            <i class="fas fa-trash mr-1"></i>
                            حذف الناخب
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
                                @if(isset($voter->last_login_at) && $voter->last_login_at)
                                    {{ $voter->last_login_at->diffForHumans() }}
                                @else
                                    لم يسجل دخول بعد
                                @endif
                            </span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>عنوان IP الأخير:</strong>
                            <span class="text-muted d-block">{{ $voter->last_login_ip ?? "غير محدد" }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>المتصفح الأخير:</strong>
                            <span class="text-muted d-block">{{ $voter->last_user_agent ?? "غير محدد" }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>حالة التحقق:</strong>
                            <span class="text-muted d-block">
                                @if(isset($voter->email_verified_at) && $voter->email_verified_at)
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
                        <a href="{{ route("admin.voters.index") }}" class="btn btn-outline-primary">
                            <i class="fas fa-list mr-1"></i>
                            قائمة الناخبين
                        </a>
                        <a href="{{ route("admin.voters.create") }}" class="btn btn-outline-success">
                            <i class="fas fa-plus mr-1"></i>
                            إضافة ناخب جديد
                        </a>
                        <a href="{{ route("admin.dashboard") }}" class="btn btn-outline-secondary">
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
function toggleStatus(voterId, status) {
    const action = status ? "تفعيل" : "إلغاء تفعيل";
    if (confirm(`هل أنت متأكد من ${action} هذا الناخب؟`)) {
        fetch(`{{ route("admin.voters.toggle-status", "") }}/${voterId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name="csrf-token"]").getAttribute("content")
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert("حدث خطأ أثناء تحديث الحالة");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("حدث خطأ أثناء تحديث الحالة");
        });
    }
}

function sendPasswordReset(voterId) {
    if (confirm("هل تريد إرسال رابط إعادة تعيين كلمة المرور للناخب؟")) {
        fetch(`{{ route("admin.voters.send-password-reset", "") }}/${voterId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name="csrf-token"]").getAttribute("content")
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("تم إرسال رابط إعادة تعيين كلمة المرور بنجاح");
            } else {
                alert("حدث خطأ أثناء إرسال الرابط");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("حدث خطأ أثناء إرسال الرابط");
        });
    }
}

function sendMessage(voterId) {
    const message = prompt("اكتب الرسالة التي تريد إرسالها للناخب:");
    if (message && message.trim()) {
        fetch(`{{ route("admin.voters.send-message", "") }}/${voterId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name="csrf-token"]").getAttribute("content")
            },
            body: JSON.stringify({ message: message.trim() })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("تم إرسال الرسالة بنجاح");
            } else {
                alert("حدث خطأ أثناء إرسال الرسالة");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("حدث خطأ أثناء إرسال الرسالة");
        });
    }
}

function deleteVoter(voterId) {
    if (confirm("هل أنت متأكد من حذف هذا الناخب؟ هذا الإجراء لا يمكن التراجع عنه.")) {
        fetch(`{{ route("admin.voters.destroy", "") }}/${voterId}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name="csrf-token"]").getAttribute("content")
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = "{{ route("admin.voters.index") }}";
            } else {
                alert("حدث خطأ أثناء حذف الناخب");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("حدث خطأ أثناء حذف الناخب");
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

