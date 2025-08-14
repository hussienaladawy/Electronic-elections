@extends('layouts.app')

@section('title', 'إضافة سوبرادمن جديد - نظام الانتخابات')
@section('page-title', 'إضافة سوبرادمن جديد')
@section('page-description', 'إنشاء حساب سوبرادمن جديد في النظام')

@section('page-actions')
<a href="{{ route('super_admin.super_admins.index') }}" class="btn btn-secondary">
    <i class="bi bi-arrow-right me-2"></i>
    العودة للقائمة
</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person-plus me-2"></i>
                    بيانات السوبرادمن الجديد
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('super_admin.super_admins.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="national_id" class="form-label">الرقم الوطني <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('national_id') is-invalid @enderror" 
                                   id="national_id" name="national_id" value="{{ old('national_id') }}" required>
                            @error('national_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">يجب أن تكون كلمة المرور 8 أحرف على الأقل</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" 
                                   {{ old('status', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">
                                حساب نشط
                            </label>
                        </div>
                        <div class="form-text">إذا لم يتم تحديد هذا الخيار، سيكون الحساب غير نشط</div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>
                            حفظ السوبرادمن
                        </button>
                        
                        <a href="{{ route('super_admin.super_admins.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-2"></i>
                            إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection

@section('scripts')
<script>
    // تحقق من تطابق كلمة المرور
    document.getElementById('password_confirmation').addEventListener('input', function() {
        var password = document.getElementById('password').value;
        var confirmPassword = this.value;
        
        if (password !== confirmPassword) {
            this.setCustomValidity('كلمة المرور غير متطابقة');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
@endsection

