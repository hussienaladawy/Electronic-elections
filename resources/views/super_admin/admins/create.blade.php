@extends("layouts.app")

@section("title", "إضافة أدمن جديد")

@section("content")
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">إضافة أدمن جديد</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">بيانات الأدمن الجديد</h6>
        </div>
        <div class="card-body">
            <form action="{{ route("super_admin.admins.store") }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control @error("name") is-invalid @enderror" id="name" name="name" value="{{ old("name") }}" required>
                    @error("name")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control @error("email") is-invalid @enderror" id="email" name="email" value="{{ old("email") }}" required>
                    @error("email")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <input type="password" class="form-control @error("password") is-invalid @enderror" id="password" name="password" required>
                    @error("password")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">رقم الهاتف</label>
                    <input type="text" class="form-control @error("phone") is-invalid @enderror" id="phone" name="phone" value="{{ old("phone") }}" required>
                    @error("phone")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="national_id" class="form-label">الرقم القومي</label>
                    <input type="text" class="form-control @error("national_id") is-invalid @enderror" id="national_id" name="national_id" value="{{ old("national_id") }}" required>
                    @error("national_id")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="department" class="form-label">القسم (اختياري)</label>
                    <input type="text" class="form-control @error("department") is-invalid @enderror" id="department" name="department" value="{{ old("department") }}">
                    @error("department")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="permissions" class="form-label">الصلاحيات (اختياري)</label>
                    <select class="form-control @error("permissions") is-invalid @enderror" id="permissions" name="permissions[]" multiple>
                        <option value="manage_users">إدارة المستخدمين</option>
                        <option value="manage_elections">إدارة الانتخابات</option>
                        <option value="view_reports">عرض التقارير</option>
                    </select>
                    @error("permissions")
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old("status", true) ? "checked" : "" }}>
                    <label class="form-check-label" for="status">نشط</label>
                </div>
                <button type="submit" class="btn btn-primary">إضافة</button>
                <a href="{{ route("super_admin.admins.index") }}" class="btn btn-secondary">إلغاء</a>
            </form>
        </div>
    </div>
</div>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection


