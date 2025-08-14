@extends("layouts.app")

@section("title", "تعديل الناخب")

@section("content")
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تعديل الناخب: {{ $voter->name ?? "غير محدد" }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route("admin.voters.index") }}">الناخبين</a></li>
                <li class="breadcrumb-item active" aria-current="page">تعديل</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات الناخب</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route("admin.voters.update", $voter->id ?? 1) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error("name") is-invalid @enderror" 
                                       value="{{ old("name", $voter->name ?? "") }}" required>
                                @error("name")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error("email") is-invalid @enderror" 
                                       value="{{ old("email", $voter->email ?? "") }}" required>
                                @error("email")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="tel" name="phone" id="phone" class="form-control @error("phone") is-invalid @enderror" 
                                       value="{{ old("phone", $voter->phone ?? "") }}">
                                @error("phone")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="national_id" class="form-label">رقم الهوية <span class="text-danger">*</span></label>
                                <input type="text" name="national_id" id="national_id" class="form-control @error("national_id") is-invalid @enderror" 
                                       value="{{ old("national_id", $voter->national_id ?? "") }}" required>
                                @error("national_id")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                <input type="password" name="password" id="password" class="form-control @error("password") is-invalid @enderror">
                                @error("password")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">تاريخ الميلاد</label>
                                <input type="date" name="birth_date" id="birth_date" class="form-control @error("birth_date") is-invalid @enderror" 
                                       value="{{ old("birth_date", $voter->birth_date ?? "") }}">
                                @error("birth_date")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">النوع</label>
                                <select name="gender" id="gender" class="form-control @error("gender") is-invalid @enderror">
                                    <option value="">-- اختر النوع --</option>
                                    <option value="male" {{ old("gender", $voter->gender ?? "") == "male" ? "selected" : "" }}>ذكر</option>
                                    <option value="female" {{ old("gender", $voter->gender ?? "") == "female" ? "selected" : "" }}>أنثى</option>
                                </select>
                                @error("gender")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان</label>
                            <textarea name="address" id="address" class="form-control @error("address") is-invalid @enderror" rows="3">{{ old("address", $voter->address ?? "") }}</textarea>
                            @error("address")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">الحالة</label>
                            <select name="status" id="status" class="form-control @error("status") is-invalid @enderror">
                                <option value="1" {{ old("status", $voter->status ?? "1") == "1" ? "selected" : "" }}>نشط</option>
                                <option value="0" {{ old("status", $voter->status ?? "") == "0" ? "selected" : "" }}>غير نشط</option>
                            </select>
                            @error("status")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                                                <div class="mb-3">
    <label for="city" class="form-label fw-bold">
        <i class="fas fa-city me-2"></i>
        المدينة *
    </label>
    <select class="form-select" id="city" name="city" required>
        <option value="">اختر المدينة</option>
        @php
            $cities = [
              "القاهرة","الإسكندرية","الجيزة","شبرا الخيمة","المحلة الكبرى","بورسعيد","السويس",
              "المنصورة","طنطا","أسيوط","الفيوم","الزقازيق","الإسماعيلية","أسوان",
              "دمنهور","دمياط","المنيا","بني سويف","الأقصر","شبين الكوم",
              "سوهاج","قنا","الغردقة","العريش","6 أكتوبر","العاصمة الإدارية الجديدة",
              "مدينة نصر","10 رمضان","شرم الشيخ","الطور"
            ];
        @endphp
        @foreach($cities as $city)
            <option value="{{ $city }}" {{ old('city') == $city ? 'selected' : '' }}>
                {{ $city }}
            </option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="district" class="form-label fw-bold">
        <i class="fas fa-map-marker-alt me-2"></i>
        الحي / المنطقة *
    </label>
    <input type="text" 
           name="district" 
           id="district" 
           class="form-control @error('district') is-invalid @enderror" 
           placeholder="اكتب اسم الحي أو المنطقة" 
           value="{{ old('district') }}" 
           required>
    @error('district')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">الصورة الشخصية</label>
                            @if(isset($voter->avatar) && $voter->avatar)
                                <div class="mb-2">
                                    <img src="{{ asset("storage/" . $voter->avatar) }}" alt="الصورة الحالية" class="img-thumbnail" style="max-width: 150px;">
                                    <small class="d-block text-muted">الصورة الحالية</small>
                                </div>
                            @endif
                            <input type="file" name="avatar" id="avatar" class="form-control @error("avatar") is-invalid @enderror" accept="image/*">
                            @error("avatar")
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الحد الأقصى: 2MB، الأنواع المدعومة: JPG, PNG, GIF</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route("admin.voters.index") }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right mr-1"></i>
                                العودة
                            </a>
                            <div>
                                <a href="{{ route("admin.voters.show", $voter->id ?? 1) }}" class="btn btn-info">
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
                            <strong>تاريخ التسجيل:</strong>
                            <span class="text-muted">{{ isset($voter->created_at) ? $voter->created_at->format("Y-m-d H:i") : "غير محدد" }}</span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>آخر تحديث:</strong>
                            <span class="text-muted">{{ isset($voter->updated_at) ? $voter->updated_at->format("Y-m-d H:i") : "غير محدد" }}</span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>آخر تسجيل دخول:</strong>
                            <span class="text-muted">
                                @if(isset($voter->last_login_at) && $voter->last_login_at)
                                    {{ $voter->last_login_at->diffForHumans() }}
                                @else
                                    لم يسجل دخول بعد
                                @endif
                            </span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>عدد الأصوات:</strong>
                            <span class="badge bg-info">{{ $voter->votes_count ?? 0 }}</span>
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
                        @if(isset($voter->status) && $voter->status)
                            <button class="btn btn-warning btn-sm" onclick="toggleStatus({{ $voter->id ?? 1 }}, 0)">
                                <i class="fas fa-ban mr-1"></i>
                                إلغاء التفعيل
                            </button>
                        @else
                            <button class="btn btn-success btn-sm" onclick="toggleStatus({{ $voter->id ?? 1 }}, 1)">
                                <i class="fas fa-check mr-1"></i>
                                تفعيل الحساب
                            </button>
                        @endif
                        
                        <button class="btn btn-info btn-sm" onclick="sendPasswordReset({{ $voter->id ?? 1 }})">
                            <i class="fas fa-key mr-1"></i>
                            إرسال رابط إعادة تعيين كلمة المرور
                        </button>
                        
                        <button class="btn btn-secondary btn-sm" onclick="sendWelcomeEmail({{ $voter->id ?? 1 }})">
                            <i class="fas fa-envelope mr-1"></i>
                            إرسال بريد ترحيبي
                        </button>
                        
                        <hr>
                        
                        <button class="btn btn-danger btn-sm" onclick="deleteVoter({{ $voter->id ?? 1 }})">
                            <i class="fas fa-trash mr-1"></i>
                            حذف الناخب
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Password confirmation validation
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("password_confirmation");
    
    confirmPasswordInput.addEventListener("input", function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;
        
        if (password && password !== confirmPassword) {
            this.setCustomValidity("كلمات المرور غير متطابقة");
        } else {
            this.setCustomValidity("");
        }
    });
    
    // Avatar preview
    const avatarInput = document.getElementById("avatar");
    avatarInput.addEventListener("change", function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById("avatar-preview");
                if (!preview) {
                    preview = document.createElement("img");
                    preview.id = "avatar-preview";
                    preview.className = "img-thumbnail mt-2";
                    preview.style.maxWidth = "150px";
                    avatarInput.parentNode.appendChild(preview);
                }
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});

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

function sendWelcomeEmail(voterId) {
    if (confirm("هل تريد إرسال بريد ترحيبي للناخب؟")) {
        fetch(`{{ route("admin.voters.send-welcome-email", "") }}/${voterId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name="csrf-token"]").getAttribute("content")
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("تم إرسال البريد الترحيبي بنجاح");
            } else {
                alert("حدث خطأ أثناء إرسال البريد");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("حدث خطأ أثناء إرسال البريد");
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

