@extends("layouts.app")

@section("title", "تصدير المساعدين")

@section("content")
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تصدير بيانات المساعدين</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("super_admin.dashboard") }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route("super_admin.assistants.index") }}">المساعدين</a></li>
                <li class="breadcrumb-item active" aria-current="page">تصدير</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">خيارات التصدير</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route("super_admin.assistants.export") }}" method="GET">
                        <div class="mb-3">
                            <label for="format" class="form-label">صيغة الملف <span class="text-danger">*</span></label>
                            <select name="format" id="format" class="form-control" required>
                                <option value="csv">CSV (قيم مفصولة بفواصل)</option>
                                <option value="xlsx">Excel (xlsx)</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">تصفية حسب الحالة</label>
                            <select name="status" id="status" class="form-control">
                                <option value="all">جميع الحالات</option>
                                <option value="1">نشط فقط</option>
                                <option value="0">غير نشط فقط</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">تاريخ البدء (التسجيل)</label>
                                <input type="date" name="start_date" id="start_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">تاريخ الانتهاء (التسجيل)</label>
                                <input type="date" name="end_date" id="end_date" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fields" class="form-label">الحقول المراد تصديرها (اختياري)</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fields[]" value="id" id="field_id" checked>
                                <label class="form-check-label" for="field_id">ID</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fields[]" value="name" id="field_name" checked>
                                <label class="form-check-label" for="field_name">الاسم</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fields[]" value="email" id="field_email" checked>
                                <label class="form-check-label" for="field_email">البريد الإلكتروني</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fields[]" value="phone" id="field_phone">
                                <label class="form-check-label" for="field_phone">رقم الهاتف</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fields[]" value="national_id" id="field_national_id">
                                <label class="form-check-label" for="field_national_id">رقم الهوية</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fields[]" value="department" id="field_department">
                                <label class="form-check-label" for="field_department">القسم</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fields[]" value="status" id="field_status">
                                <label class="form-check-label" for="field_status">الحالة</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="fields[]" value="created_at" id="field_created_at">
                                <label class="form-check-label" for="field_created_at">تاريخ التسجيل</label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route("super_admin.assistants.index") }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right mr-1"></i>
                                العودة
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-download mr-1"></i>
                                تصدير البيانات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">ملاحظات هامة</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <h6 class="alert-heading">نصائح للتصدير:</h6>
                        <ul class="mb-0 small">
                            <li>اختر الصيغة المناسبة لاحتياجاتك.</li>
                            <li>يمكنك تصفية البيانات حسب الحالة أو نطاق التاريخ.</li>
                            <li>حدد الحقول التي ترغب في تضمينها في الملف المصدر.</li>
                            <li>للحصول على جميع البيانات، اترك خيارات التصفية فارغة وحدد جميع الحقول.</li>
                        </ul>
                    </div>
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


