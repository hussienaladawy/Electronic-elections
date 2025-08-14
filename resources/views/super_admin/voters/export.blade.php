@extends("layouts.app")

@section("title", "تصدير الناخبين")

@section("content")
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تصدير بيانات الناخبين</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("super_admin.dashboard") }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route("super_admin.voters.index") }}">الناخبين</a></li>
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
                    <form action="{{ route("super_admin.voters.export") }}" method="GET">
                        <div class="mb-3">
                            <label for="format" class="form-label">صيغة الملف <span class="text-danger">*</span></label>
                            <select name="format" id="format" class="form-control" required>
                                <option value="csv">CSV (قيم مفصولة بفواصل)</option>
                                <option value="xlsx">Excel (xlsx)</option>
                                <option value="pdf">PDF</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">تصفية حسب الحالة</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="all">جميع الحالات</option>
                                    <option value="1">نشط فقط</option>
                                    <option value="0">غير نشط فقط</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">تصفية حسب النوع</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="all">جميع الأنواع</option>
                                    <option value="male">ذكر فقط</option>
                                    <option value="female">أنثى فقط</option>
                                </select>
                            </div>
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

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="age_from" class="form-label">العمر من</label>
                                <input type="number" name="age_from" id="age_from" class="form-control" min="18" max="100">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="age_to" class="form-label">العمر إلى</label>
                                <input type="number" name="age_to" id="age_to" class="form-control" min="18" max="100">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fields" class="form-label">الحقول المراد تصديرها (اختياري)</label>
                            <div class="row">
                                <div class="col-md-6">
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
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fields[]" value="birth_date" id="field_birth_date">
                                        <label class="form-check-label" for="field_birth_date">تاريخ الميلاد</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fields[]" value="gender" id="field_gender">
                                        <label class="form-check-label" for="field_gender">النوع</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fields[]" value="address" id="field_address">
                                        <label class="form-check-label" for="field_address">العنوان</label>
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
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_voting_history" id="include_voting_history" value="1">
                                <label class="form-check-label" for="include_voting_history">
                                    تضمين تاريخ التصويت
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_statistics" id="include_statistics" value="1">
                                <label class="form-check-label" for="include_statistics">
                                    تضمين الإحصائيات
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route("super_admin.voters.index") }}" class="btn btn-secondary">
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
                            <li>يمكنك تصفية البيانات حسب الحالة، النوع، أو العمر.</li>
                            <li>حدد الحقول التي ترغب في تضمينها في الملف المصدر.</li>
                            <li>يمكنك تضمين تاريخ التصويت والإحصائيات.</li>
                            <li>للحصول على جميع البيانات، اترك خيارات التصفية فارغة.</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading">تنبيه أمني:</h6>
                        <ul class="mb-0 small">
                            <li>تأكد من حماية الملفات المصدرة.</li>
                            <li>لا تشارك البيانات الشخصية مع أطراف غير مخولة.</li>
                            <li>احذف الملفات المؤقتة بعد الانتهاء.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">إحصائيات سريعة</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <strong>إجمالي الناخبين:</strong>
                            <span class="badge bg-primary">{{ $totalVoters ?? 0 }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>الناخبين النشطين:</strong>
                            <span class="badge bg-success">{{ $activeVoters ?? 0 }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>الناخبين غير النشطين:</strong>
                            <span class="badge bg-danger">{{ $inactiveVoters ?? 0 }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>الذكور:</strong>
                            <span class="badge bg-info">{{ $maleVoters ?? 0 }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>الإناث:</strong>
                            <span class="badge bg-pink">{{ $femaleVoters ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Select/Deselect all fields
    const selectAllBtn = document.createElement("button");
    selectAllBtn.type = "button";
    selectAllBtn.className = "btn btn-sm btn-outline-primary me-2";
    selectAllBtn.innerHTML = "تحديد الكل";
    selectAllBtn.onclick = function() {
        const checkboxes = document.querySelectorAll("input[name="fields[]"]");
        checkboxes.forEach(cb => cb.checked = true);
    };

    const deselectAllBtn = document.createElement("button");
    deselectAllBtn.type = "button";
    deselectAllBtn.className = "btn btn-sm btn-outline-secondary";
    deselectAllBtn.innerHTML = "إلغاء تحديد الكل";
    deselectAllBtn.onclick = function() {
        const checkboxes = document.querySelectorAll("input[name="fields[]"]");
        checkboxes.forEach(cb => cb.checked = false);
    };

    const fieldsLabel = document.querySelector("label[for="fields"]");
    const buttonsDiv = document.createElement("div");
    buttonsDiv.className = "mb-2";
    buttonsDiv.appendChild(selectAllBtn);
    buttonsDiv.appendChild(deselectAllBtn);
    fieldsLabel.parentNode.insertBefore(buttonsDiv, fieldsLabel.nextSibling);

    // Age validation
    const ageFromInput = document.getElementById("age_from");
    const ageToInput = document.getElementById("age_to");

    ageFromInput.addEventListener("change", function() {
        if (ageToInput.value && parseInt(this.value) > parseInt(ageToInput.value)) {
            ageToInput.value = this.value;
        }
    });

    ageToInput.addEventListener("change", function() {
        if (ageFromInput.value && parseInt(this.value) < parseInt(ageFromInput.value)) {
            ageFromInput.value = this.value;
        }
    });

    // Date validation
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
});
</script>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection

