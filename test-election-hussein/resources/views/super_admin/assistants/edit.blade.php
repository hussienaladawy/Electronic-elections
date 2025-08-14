@extends('layouts.app')

@section('title', 'تعديل المساعد')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تعديل المساعد: {{ $assistant->name ?? 'غير محدد' }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('super_admin.dashboard') }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route('super_admin.assistants.index') }}">المساعدين</a></li>
                <li class="breadcrumb-item active" aria-current="page">تعديل</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات المساعد</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('super_admin.assistants.update', $assistant->id ?? 1) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $assistant->name ?? '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $assistant->email ?? '') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone', $assistant->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="national_id" class="form-label">رقم الهوية</label>
                                <input type="text" name="national_id" id="national_id" class="form-control @error('national_id') is-invalid @enderror" 
                                       value="{{ old('national_id', $assistant->national_id ?? '') }}">
                                @error('national_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
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
                                <label for="department" class="form-label">القسم <span class="text-danger">*</span></label>
                                <select name="department" id="department" class="form-control @error('department') is-invalid @enderror" required>
                                    <option value="">-- اختر القسم --</option>
                                    <option value="support" {{ old('department', $assistant->department ?? '') == 'support' ? 'selected' : '' }}>الدعم الفني</option>
                                    <option value="registration" {{ old('department', $assistant->department ?? '') == 'registration' ? 'selected' : '' }}>التسجيل</option>
                                    <option value="monitoring" {{ old('department', $assistant->department ?? '') == 'monitoring' ? 'selected' : '' }}>المراقبة</option>
                                    <option value="general" {{ old('department', $assistant->department ?? '') == 'general' ? 'selected' : '' }}>عام</option>
                                </select>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">الحالة</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="1" {{ old('status', $assistant->status ?? '1') == '1' ? 'selected' : '' }}>نشط</option>
                                    <option value="0" {{ old('status', $assistant->status ?? '') == '0' ? 'selected' : '' }}>غير نشط</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان</label>
                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $assistant->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $assistant->notes ?? '') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">الصورة الشخصية</label>
                            @if(isset($assistant->avatar) && $assistant->avatar)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $assistant->avatar) }}" alt="الصورة الحالية" class="img-thumbnail" style="max-width: 150px;">
                                    <small class="d-block text-muted">الصورة الحالية</small>
                                </div>
                            @endif
                            <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الحد الأقصى: 2MB، الأنواع المدعومة: JPG, PNG, GIF</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('super_admin.assistants.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right mr-1"></i>
                                العودة
                            </a>
                            <div>
                                <a href="{{ route('super_admin.assistants.show', $assistant->id ?? 1) }}" class="btn btn-info">
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
                            <span class="text-muted">{{ isset($assistant->created_at) ? $assistant->created_at->format('Y-m-d H:i') : 'غير محدد' }}</span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>آخر تحديث:</strong>
                            <span class="text-muted">{{ isset($assistant->updated_at) ? $assistant->updated_at->format('Y-m-d H:i') : 'غير محدد' }}</span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>آخر تسجيل دخول:</strong>
                            <span class="text-muted">
                                @if(isset($assistant->last_login_at) && $assistant->last_login_at)
                                    {{ $assistant->last_login_at->diffForHumans() }}
                                @else
                                    لم يسجل دخول بعد
                                @endif
                            </span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>عدد المساعدات:</strong>
                            <span class="badge bg-info">{{ $assistant->help_requests_count ?? 0 }}</span>
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
                        @if(isset($assistant->status) && $assistant->status)
                            <button class="btn btn-warning btn-sm" onclick="toggleStatus({{ $assistant->id ?? 1 }}, 0)">
                                <i class="fas fa-ban mr-1"></i>
                                إلغاء التفعيل
                            </button>
                        @else
                            <button class="btn btn-success btn-sm" onclick="toggleStatus({{ $assistant->id ?? 1 }}, 1)">
                                <i class="fas fa-check mr-1"></i>
                                تفعيل الحساب
                            </button>
                        @endif
                        
                        <button class="btn btn-info btn-sm" onclick="sendPasswordReset({{ $assistant->id ?? 1 }})">
                            <i class="fas fa-key mr-1"></i>
                            إرسال رابط إعادة تعيين كلمة المرور
                        </button>
                        
                        <button class="btn btn-secondary btn-sm" onclick="sendWelcomeEmail({{ $assistant->id ?? 1 }})">
                            <i class="fas fa-envelope mr-1"></i>
                            إرسال بريد ترحيبي
                        </button>
                        
                        <hr>
                        
                        <button class="btn btn-danger btn-sm" onclick="deleteAssistant({{ $assistant->id ?? 1 }})">
                            <i class="fas fa-trash mr-1"></i>
                            حذف المساعد
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password confirmation validation
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;
        
        if (password && password !== confirmPassword) {
            this.setCustomValidity('كلمات المرور غير متطابقة');
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    avatarInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create preview if doesn't exist
                let preview = document.getElementById('avatar-preview');
                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = 'avatar-preview';
                    preview.className = 'img-thumbnail mt-2';
                    preview.style.maxWidth = '150px';
                    avatarInput.parentNode.appendChild(preview);
                }
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
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

function sendWelcomeEmail(assistantId) {
    if (confirm('هل تريد إرسال بريد ترحيبي للمساعد؟')) {
        fetch(`{{ route('super_admin.assistants.send-welcome-email', '') }}/${assistantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إرسال البريد الترحيبي بنجاح');
            } else {
                alert('حدث خطأ أثناء إرسال البريد');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إرسال البريد');
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
</script>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection

