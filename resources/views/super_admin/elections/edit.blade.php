@extends("layouts.app")

@section("title", "تعديل الانتخابات")

@section("content")
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تعديل الانتخابات: {{ $election->title ?? "غير محدد" }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("super_admin.dashboard") }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route("super_admin.elections.index") }}">الانتخابات</a></li>
                <li class="breadcrumb-item active" aria-current="page">تعديل</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات الانتخابات</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route("super_admin.elections.update", $election->id ?? 1) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">عنوان الانتخابات <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error("title") is-invalid @enderror" 
                                   value="{{ old("title", $election->title ?? "") }}" required>
                            @error("title")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea name="description" id="description" class="form-control @error("description") is-invalid @enderror" rows="4">{{ old("description", $election->description ?? "") }}</textarea>
                            @error("description")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="registration_start" class="form-label">بدء التسجيل <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="registration_start" id="start_date" class="form-control @error("start_date") is-invalid @enderror" 
                                       value="{{ old("registration_start", isset($election->registration_start) ? $election->registration_start->format("Y-m-d\TH:i") : "") }}" required>
                                @error("start_date")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="registration_end" class="form-label"> انتهاء التسجيل  <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="registration_end" id="registration_end" class="form-control @error("registration_end") is-invalid @enderror" 
                                       value="{{ old("registration_end", isset($election->registration_end) ? $election->registration_end->format("Y-m-d\TH:i") : "") }}" required>
                                @error("end_date")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                            <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">تاريخ البدء <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control @error("start_date") is-invalid @enderror" 
                                       value="{{ old("start_date", isset($election->start_date) ? $election->start_date->format("Y-m-d\TH:i") : "") }}" required>
                                @error("start_date")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">تاريخ الانتهاء <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control @error("end_date") is-invalid @enderror" 
                                       value="{{ old("end_date", isset($election->end_date) ? $election->end_date->format("Y-m-d\TH:i") : "") }}" required>
                                @error("end_date")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
    <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
        <option value="pending" {{ old('status', $election->status ?? '') == 'pending' ? 'selected' : '' }}>معلقة</option>
        <option value="active" {{ old('status', $election->status ?? '') == 'active' ? 'selected' : '' }}>نشطة</option>
        <option value="completed" {{ old('status', $election->status ?? '') == 'completed' ? 'selected' : '' }}>مكتملة</option>
        <option value="cancelled" {{ old('status', $election->status ?? '') == 'cancelled' ? 'selected' : '' }}>ملغاة</option>
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="type" class="form-label">النوع <span class="text-danger">*</span></label>
    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
        <option value="presidential" {{ old('type', $election->type ?? '') == 'presidential' ? 'selected' : '' }}>رئاسية</option>
        <option value="parliamentary" {{ old('type', $election->type ?? '') == 'parliamentary' ? 'selected' : '' }}>برلمانية</option>
        <option value="local" {{ old('type', $election->type ?? '') == 'local' ? 'selected' : '' }}>محلية</option>
        <option value="referendum" {{ old('type', $election->type ?? '') == 'referendum' ? 'selected' : '' }}>استفتاء</option>
    </select>
    @error('type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>



                        <div class="mb-3">
                            <label for="election_type" class="form-label">نوع الانتخابات <span class="text-danger">*</span></label>
                            <select name="election_type" id="election_type" class="form-control @error("election_type") is-invalid @enderror" required>
                                <option value="single_choice" {{ old("election_type", $election->election_type ?? "") == "single_choice" ? "selected" : "" }}>اختيار واحد</option>
                                <option value="multiple_choice" {{ old("election_type", $election->election_type ?? "") == "multiple_choice" ? "selected" : "" }}>اختيار متعدد</option>
                            </select>
                            @error("election_type")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="voting_method" class="form-label">طريقة التصويت <span class="text-danger">*</span></label>
                            <select name="voting_method" id="voting_method" class="form-control @error("voting_method") is-invalid @enderror" required>
                                <option value="online" {{ old("voting_method", $election->voting_method ?? "") == "online" ? "selected" : "" }}>إلكتروني (عبر الإنترنت)</option>
                                <option value="physical" {{ old("voting_method", $election->voting_method ?? "") == "physical" ? "selected" : "" }}>حضوري (فيزيائي)</option>
                            </select>
                            @error("voting_method")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">صورة الانتخابات</label>
                            @if(isset($election->image) && $election->image)
                                <div class="mb-2">
                                    <img src="{{ asset("storage/" . $election->image) }}" alt="الصورة الحالية" class="img-thumbnail" style="max-width: 200px;">
                                    <small class="d-block text-muted">الصورة الحالية</small>
                                </div>
                            @endif
                            <input type="file" name="image" id="image" class="form-control @error("image") is-invalid @enderror" accept="image/*">
                            @error("image")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الحد الأقصى: 5MB، الأنواع المدعومة: JPG, PNG, GIF</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route("super_admin.elections.index") }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right mr-1"></i>
                                العودة
                            </a>
                            <div>
                                <a href="{{ route("super_admin.elections.show", $election->id ?? 1) }}" class="btn btn-info">
                                    <i class="fas fa-eye mr-1"></i>
                                    عرض
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-1"></i>
                                    حفظ التغييرات
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات إضافية</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <strong>تاريخ الإنشاء:</strong>
                            <span class="text-muted">{{ isset($election->created_at) ? $election->created_at->format("Y-m-d H:i") : "غير محدد" }}</span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>آخر تحديث:</strong>
                            <span class="text-muted">{{ isset($election->updated_at) ? $election->updated_at->format("Y-m-d H:i") : "غير محدد" }}</span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>عدد الأصوات:</strong>
                            <span class="badge bg-info">{{ $election->votes_count ?? 0 }}</span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>عدد المرشحين:</strong>
                            <span class="badge bg-success">{{ $election->candidates_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

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
                        
                        <hr>
                        
                        <button class="btn btn-danger btn-sm" onclick="deleteElection({{ $election->id ?? 1 }})">
                            <i class="fas fa-trash mr-1"></i>
                            حذف الانتخابات
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");

    startDateInput.addEventListener("change", function() {
        if (endDateInput.value && this.value > endDateInput.value) {
            endDateInput.value = this.value;
        }
    });

    endDateInput.addEventListener("change", function() {
        if (startDateInput.value && this.value < startDateInput.value) {
            startDateInput.value = this.value;
        }
    });

    const imageInput = document.getElementById("image");
    imageInput.addEventListener("change", function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById("image-preview");
                if (!preview) {
                    preview = document.createElement("img");
                    preview.id = "image-preview";
                    preview.className = "img-thumbnail mt-2";
                    preview.style.maxWidth = "150px";
                    imageInput.parentNode.appendChild(preview);
                }
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});

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