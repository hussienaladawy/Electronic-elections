@extends('layouts.app')

@section('title', 'إضافة مساعد جديد')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إضافة مساعد جديد</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.assistants.index') }}">المساعدين</a></li>
                <li class="breadcrumb-item active" aria-current="page">إضافة جديد</li>
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
                    <form action="{{ route('admin.assistants.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف</label>
                                <input type="tel" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="national_id" class="form-label">رقم الهوية</label>
                                <input type="text" name="national_id" id="national_id" class="form-control @error('national_id') is-invalid @enderror" 
                                       value="{{ old('national_id') }}">
                                @error('national_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">القسم <span class="text-danger">*</span></label>
                                <select name="department" id="department" class="form-control @error('department') is-invalid @enderror" required>
                                    <option value="">-- اختر القسم --</option>
                                    <option value="support" {{ old('department') == 'support' ? 'selected' : '' }}>الدعم الفني</option>
                                    <option value="registration" {{ old('department') == 'registration' ? 'selected' : '' }}>التسجيل</option>
                                    <option value="monitoring" {{ old('department') == 'monitoring' ? 'selected' : '' }}>المراقبة</option>
                                    <option value="general" {{ old('department') == 'general' ? 'selected' : '' }}>عام</option>
                                </select>
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">الحالة</label>
                                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>نشط</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>غير نشط</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان</label>
                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">الصورة الشخصية</label>
                            <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                            @error('avatar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">الحد الأقصى: 2MB، الأنواع المدعومة: JPG, PNG, GIF</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.assistants.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right mr-1"></i>
                                العودة
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                حفظ المساعد
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">إرشادات</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <h6 class="alert-heading">معلومات مهمة:</h6>
                        <ul class="mb-0 small">
                            <li>الحقول المميزة بـ <span class="text-danger">*</span> مطلوبة</li>
                            <li>كلمة المرور يجب أن تكون 8 أحرف على الأقل</li>
                            <li>البريد الإلكتروني يجب أن يكون فريد</li>
                            <li>سيتم إرسال بيانات الدخول للمساعد عبر البريد الإلكتروني</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading">صلاحيات المساعد:</h6>
                        <ul class="mb-0 small">
                            <li>مساعدة الناخبين في التسجيل</li>
                            <li>الرد على الاستفسارات</li>
                            <li>مراقبة العملية الانتخابية</li>
                            <li>إعداد التقارير الأولية</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">الأقسام المتاحة</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <span class="badge bg-primary">الدعم الفني</span>
                            <small class="text-muted d-block">مساعدة المستخدمين تقنياً</small>
                        </div>
                        <div class="col-12 mb-2">
                            <span class="badge bg-success">التسجيل</span>
                            <small class="text-muted d-block">مساعدة في تسجيل الناخبين</small>
                        </div>
                        <div class="col-12 mb-2">
                            <span class="badge bg-warning">المراقبة</span>
                            <small class="text-muted d-block">مراقبة العملية الانتخابية</small>
                        </div>
                        <div class="col-12 mb-2">
                            <span class="badge bg-secondary">عام</span>
                            <small class="text-muted d-block">مهام عامة متنوعة</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = getPasswordStrength(password);
        updatePasswordStrengthIndicator(strength);
    });
    
    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;
        
        if (password !== confirmPassword) {
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

function getPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    return strength;
}

function updatePasswordStrengthIndicator(strength) {
    // Remove existing indicator
    const existingIndicator = document.getElementById('password-strength');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    // Create new indicator
    const indicator = document.createElement('div');
    indicator.id = 'password-strength';
    indicator.className = 'mt-1';
    
    let strengthText = '';
    let strengthClass = '';
    
    switch (strength) {
        case 0:
        case 1:
            strengthText = 'ضعيف جداً';
            strengthClass = 'text-danger';
            break;
        case 2:
            strengthText = 'ضعيف';
            strengthClass = 'text-warning';
            break;
        case 3:
            strengthText = 'متوسط';
            strengthClass = 'text-info';
            break;
        case 4:
            strengthText = 'قوي';
            strengthClass = 'text-success';
            break;
        case 5:
            strengthText = 'قوي جداً';
            strengthClass = 'text-success font-weight-bold';
            break;
    }
    
    indicator.innerHTML = `<small class="${strengthClass}">قوة كلمة المرور: ${strengthText}</small>`;
    document.getElementById('password').parentNode.appendChild(indicator);
}
</script>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection

