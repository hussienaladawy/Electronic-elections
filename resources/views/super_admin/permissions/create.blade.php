@extends('layouts.app')

@section('title', 'إضافة صلاحية جديدة')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i> إضافة صلاحية جديدة
                    </h5>
                    <a href="{{ route('super_admin.permissions.index') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left"></i> رجوع
                    </a>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('super_admin.permissions.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">اسم الصلاحية <span class="text-danger">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name" 
                                placeholder="مثال: manage-users" 
                                value="{{ old('name') }}"
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save"></i> حفظ
                            </button>
                        </div>
                    </form>
                </div>
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
