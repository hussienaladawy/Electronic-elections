@extends("layouts.app")

@section("title", "تفاصيل الانتخابات")

@section("content")
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تفاصيل الانتخابات: {{ $election->title ?? "غير محدد" }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("super_admin.dashboard") }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route("super_admin.elections.index") }}">الانتخابات</a></li>
                <li class="breadcrumb-item active" aria-current="page">التفاصيل</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Election Details -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات الانتخابات</h6>
                    <div>
                        <a href="{{ route("super_admin.elections.edit", $election->id ?? 1) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="deleteElection({{ $election->id ?? 1 }})">
                            <i class="fas fa-trash"></i> حذف
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">العنوان:</th>
                                    <td>{{ $election->title ?? "غير محدد" }}</td>
                                </tr>
                                <tr>
                                    <th>الوصف:</th>
                                    <td>{{ $election->description ?? "لا يوجد وصف" }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ البدء:</th>
                                    <td>{{ isset($election->start_date) ? $election->start_date->format("Y-m-d H:i") : "غير محدد" }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الانتهاء:</th>
                                    <td>{{ isset($election->end_date) ? $election->end_date->format("Y-m-d H:i") : "غير محدد" }}</td>
                                </tr>
                                <tr>
                                    <th>الحالة:</th>
                                    <td>
                                        @if(isset($election->status))
                                            @switch($election->status)
                                                @case("pending")
                                                    <span class="badge bg-warning">معلقة</span>
                                                    @break
                                                @case("active")
                                                    <span class="badge bg-success">نشطة</span>
                                                    @break
                                                @case("completed")
                                                    <span class="badge bg-info">مكتملة</span>
                                                    @break
                                                @case("cancelled")
                                                    <span class="badge bg-danger">ملغاة</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $election->status }}</span>
                                            @endswitch
                                        @else
                                            <span class="badge bg-secondary">غير محدد</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>نوع الانتخابات:</th>
                                    <td>
                                        @if(isset($election->election_type))
                                            @if($election->election_type == "single_choice")
                                                اختيار واحد
                                            @elseif($election->election_type == "multiple_choice")
                                                اختيار متعدد
                                            @else
                                                {{ $election->election_type }}
                                            @endif
                                        @else
                                            غير محدد
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>طريقة التصويت:</th>
                                    <td>
                                        @if(isset($election->voting_method))
                                            @if($election->voting_method == "online")
                                                إلكتروني (عبر الإنترنت)
                                            @elseif($election->voting_method == "physical")
                                                حضوري (فيزيائي)
                                            @else
                                                {{ $election->voting_method }}
                                            @endif
                                        @else
                                            غير محدد
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء:</th>
                                    <td>{{ isset($election->created_at) ? $election->created_at->format("Y-m-d H:i") : "غير محدد" }}</td>
                                </tr>
                                <tr>
                                    <th>آخر تحديث:</th>
                                    <td>{{ isset($election->updated_at) ? $election->updated_at->format("Y-m-d H:i") : "غير محدد" }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            @if(isset($election->image) && $election->image)
                                <img src="{{ asset("storage/" . $election->image) }}" alt="صورة الانتخابات" class="img-fluid rounded">
                            @else
                                <div class="text-center p-4 bg-light rounded">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                    <p class="text-muted mt-2">لا توجد صورة</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Candidates Section -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">المرشحون</h6>
                    <a href="{{ route("super_admin.elections.candidates", $election->id ?? 1) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> إدارة المرشحين
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($candidates) && $candidates->count() > 0)
                        <div class="row">
                            @foreach($candidates as $candidate)
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $candidate->name ?? "غير محدد" }}</h6>
                                            <p class="card-text small text-muted">{{ $candidate->description ?? "لا يوجد وصف" }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-info">{{ $candidate->votes_count ?? 0 }} صوت</span>
                                                <small class="text-muted">{{ $candidate->party ?? "مستقل" }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users fa-3x text-muted"></i>
                            <p class="text-muted mt-2">لا يوجد مرشحون لهذه الانتخابات</p>
                            <a href="{{ route("super_admin.elections.candidates", $election->id ?? 1) }}" class="btn btn-primary">
                                إضافة مرشحين
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Voting Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">إحصائيات التصويت</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="text-center">
                                <h4 class="text-primary">{{ $election->votes_count ?? 0 }}</h4>
                                <small class="text-muted">إجمالي الأصوات</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center">
                                <h4 class="text-success">{{ $election->valid_votes_count ?? 0 }}</h4>
                                <small class="text-muted">الأصوات الصحيحة</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center">
                                <h4 class="text-warning">{{ $election->invalid_votes_count ?? 0 }}</h4>
                                <small class="text-muted">الأصوات الباطلة</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="text-center">
                                <h4 class="text-info">{{ $election->turnout_percentage ?? 0 }}%</h4>
                                <small class="text-muted">نسبة المشاركة</small>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($election->votes_count) && $election->votes_count > 0)
                        <div class="progress mt-3">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ ($election->valid_votes_count ?? 0) / $election->votes_count * 100 }}%">
                                صحيحة
                            </div>
                            <div class="progress-bar bg-warning" role="progressbar" 
                                 style="width: {{ ($election->invalid_votes_count ?? 0) / $election->votes_count * 100 }}%">
                                باطلة
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">إجراءات سريعة</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(isset($election->status))
                            @if($election->status == "pending")
                                <button class="btn btn-success btn-sm" onclick="changeElectionStatus({{ $election->id ?? 1 }}, "active")">
                                    <i class="fas fa-play mr-1"></i>
                                    بدء الانتخابات
                                </button>
                            @elseif($election->status == "active")
                                <button class="btn btn-warning btn-sm" onclick="changeElectionStatus({{ $election->id ?? 1 }}, "completed")">
                                    <i class="fas fa-stop mr-1"></i>
                                    إنهاء الانتخابات
                                </button>
                            @endif
                            
                            @if($election->status != "cancelled")
                                <button class="btn btn-danger btn-sm" onclick="changeElectionStatus({{ $election->id ?? 1 }}, "cancelled")">
                                    <i class="fas fa-ban mr-1"></i>
                                    إلغاء الانتخابات
                                </button>
                            @endif
                        @endif
                        
                        <a href="{{ route("super_admin.elections.candidates", $election->id ?? 1) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-users mr-1"></i>
                            إدارة المرشحين
                        </a>
                        
                        <a href="{{ route("super_admin.elections.results", $election->id ?? 1) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-chart-bar mr-1"></i>
                            عرض النتائج
                        </a>
                        
                        <a href="{{ route("super_admin.elections.export", $election->id ?? 1) }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-download mr-1"></i>
                            تصدير البيانات
                        </a>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">الجدول الزمني</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">إنشاء الانتخابات</h6>
                                <p class="timeline-text">{{ isset($election->created_at) ? $election->created_at->format("Y-m-d H:i") : "غير محدد" }}</p>
                            </div>
                        </div>
                        
                        @if(isset($election->start_date))
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">بداية التصويت</h6>
                                    <p class="timeline-text">{{ $election->start_date->format("Y-m-d H:i") }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if(isset($election->end_date))
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">نهاية التصويت</h6>
                                    <p class="timeline-text">{{ $election->end_date->format("Y-m-d H:i") }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @if(isset($election->status) && $election->status == "completed")
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">إعلان النتائج</h6>
                                    <p class="timeline-text">{{ isset($election->updated_at) ? $election->updated_at->format("Y-m-d H:i") : "غير محدد" }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">النشاط الأخير</h6>
                </div>
                <div class="card-body">
                    @if(isset($recentVotes) && $recentVotes->count() > 0)
                        @foreach($recentVotes->take(5) as $vote)
                            <div class="d-flex align-items-center mb-2">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-vote-yea text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">{{ $vote->created_at->diffForHumans() }}</div>
                                    <div class="font-weight-bold">صوت جديد من الناخب #{{ $vote->voter_id }}</div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-history fa-2x text-muted"></i>
                            <p class="text-muted mt-2">لا يوجد نشاط حديث</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row">
        <div class="col-12">
            <a href="{{ route("super_admin.elections.index") }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right mr-1"></i>
                العودة إلى قائمة الانتخابات
            </a>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: "";
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
}

.timeline-content {
    padding-left: 20px;
}

.timeline-title {
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 0;
}

.icon-circle {
    height: 2rem;
    width: 2rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script>
function changeElectionStatus(electionId, status) {
    const statusText = {
        "active": "تفعيل",
        "completed": "إكمال",
        "cancelled": "إلغاء"
    };
    
    if (confirm(`هل أنت متأكد من ${statusText[status]} هذه الانتخابات؟`)) {
        fetch(`{{ route("super_admin.elections.change-status", "") }}/${electionId}`, {
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
                alert("حدث خطأ أثناء تحديث حالة الانتخابات");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("حدث خطأ أثناء تحديث حالة الانتخابات");
        });
    }
}

function deleteElection(electionId) {
    if (confirm("هل أنت متأكد من حذف هذه الانتخابات؟ هذا الإجراء لا يمكن التراجع عنه.")) {
        fetch(`{{ route("super_admin.elections.destroy", "") }}/${electionId}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name="csrf-token"]").getAttribute("content")
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = "{{ route("super_admin.elections.index") }}";
            } else {
                alert("حدث خطأ أثناء حذف الانتخابات");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("حدث خطأ أثناء حذف الانتخابات");
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

