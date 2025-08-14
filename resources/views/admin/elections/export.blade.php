@extends("layouts.app")

@section("title", "تصدير الانتخابات")

@section("content")
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تصدير بيانات الانتخابات</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("admin.dashboard") }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route("admin.elections.index") }}">الانتخابات</a></li>
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
                    <form action="{{ route("admin.elections.export") }}" method="GET">
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
                                    <option value="pending">معلقة فقط</option>
                                    <option value="active">نشطة فقط</option>
                                    <option value="completed">مكتملة فقط</option>
                                    <option value="cancelled">ملغاة فقط</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="election_type" class="form-label">تصفية حسب النوع</label>
                                <select name="election_type" id="election_type" class="form-control">
                                    <option value="all">جميع الأنواع</option>
                                    <option value="single_choice">اختيار واحد فقط</option>
                                    <option value="multiple_choice">اختيار متعدد فقط</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">تاريخ البدء (الإنشاء)</label>
                                <input type="date" name="start_date" id="start_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">تاريخ الانتهاء (الإنشاء)</label>
                                <input type="date" name="end_date" id="end_date" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="voting_start_date" class="form-label">تاريخ بدء التصويت</label>
                                <input type="date" name="voting_start_date" id="voting_start_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="voting_end_date" class="form-label">تاريخ انتهاء التصويت</label>
                                <input type="date" name="voting_end_date" id="voting_end_date" class="form-control">
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
                                        <input class="form-check-input" type="checkbox" name="fields[]" value="title" id="field_title" checked>
                                        <label class="form-check-label" for="field_title">العنوان</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fields[]" value="description" id="field_description">
                                        <label class="form-check-label" for="field_description">الوصف</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fields[]" value="start_date" id="field_start_date" checked>
                                        <label class="form-check-label" for="field_start_date">تاريخ البدء</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fields[]" value="end_date" id="field_end_date" checked>
                                        <label class="form-check-label" for="field_end_date">تاريخ الانتهاء</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fields[]" value="status" id="field_status" checked>
                                        <label class="form-check-label" for="field_status">الحالة</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fields[]" value="election_type" id="field_election_type">
                                        <label class="form-check-label" for="field_election_type">نوع الانتخابات</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fields[]" value="voting_method" id="field_voting_method">
                                        <label class="form-check-label" for="field_voting_method">طريقة التصويت</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fields[]" value="votes_count" id="field_votes_count">
                                        <label class="form-check-label" for="field_votes_count">عدد الأصوات</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="fields[]" value="created_at" id="field_created_at">
                                        <label class="form-check-label" for="field_created_at">تاريخ الإنشاء</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_candidates" id="include_candidates" value="1">
                                <label class="form-check-label" for="include_candidates">
                                    تضمين بيانات المرشحين
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_votes" id="include_votes" value="1">
                                <label class="form-check-label" for="include_votes">
                                    تضمين تفاصيل الأصوات
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_statistics" id="include_statistics" value="1">
                                <label class="form-check-label" for="include_statistics">
                                    تضمين الإحصائيات التفصيلية
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_results" id="include_results" value="1">
                                <label class="form-check-label" for="include_results">
                                    تضمين النتائج النهائية
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route("admin.elections.index") }}" class="btn btn-secondary">
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
                            <li>يمكنك تصفية البيانات حسب الحالة، النوع، أو التاريخ.</li>
                            <li>حدد الحقول التي ترغب في تضمينها في الملف المصدر.</li>
                            <li>يمكنك تضمين بيانات المرشحين والأصوات والإحصائيات.</li>
                            <li>للحصول على جميع البيانات، اترك خيارات التصفية فارغة.</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning" role="alert">
                        <h6 class="alert-heading">تنبيه أمني:</h6>
                        <ul class="mb-0 small">
                            <li>تأكد من حماية الملفات المصدرة.</li>
                            <li>لا تشارك البيانات الحساسة مع أطراف غير مخولة.</li>
                            <li>احذف الملفات المؤقتة بعد الانتهاء.</li>
                            <li>تأكد من صلاحياتك قبل تصدير بيانات الأصوات.</li>
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
                            <strong>إجمالي الانتخابات:</strong>
                            <span class="badge bg-primary">{{ $totalElections ?? 0 }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>الانتخابات النشطة:</strong>
                            <span class="badge bg-success">{{ $activeElections ?? 0 }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>الانتخابات المكتملة:</strong>
                            <span class="badge bg-info">{{ $completedElections ?? 0 }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>الانتخابات الملغاة:</strong>
                            <span class="badge bg-danger">{{ $cancelledElections ?? 0 }}</span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>إجمالي الأصوات:</strong>
                            <span class="badge bg-warning">{{ $totalVotes ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تصدير سريع</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route("admin.elections.export") }}?format=csv&quick=all" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-file-csv mr-1"></i>
                            تصدير جميع الانتخابات (CSV)
                        </a>
                        <a href="{{ route("admin.elections.export") }}?format=xlsx&quick=active" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-file-excel mr-1"></i>
                            تصدير الانتخابات النشطة (Excel)
                        </a>
                        <a href="{{ route("admin.elections.export") }}?format=pdf&quick=completed" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-file-pdf mr-1"></i>
                            تصدير الانتخابات المكتملة (PDF)
                        </a>
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

    // Date validation
    const startDateInput = document.getElementById("start_date");
    const endDateInput = document.getElementById("end_date");
    const votingStartDateInput = document.getElementById("voting_start_date");
    const votingEndDateInput = document.getElementById("voting_end_date");

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

    votingStartDateInput.addEventListener("change", function() {
        if (votingEndDateInput.value && this.value > votingEndDateInput.value) {
            votingEndDateInput.value = this.value;
        }
    });

    votingEndDateInput.addEventListener("change", function() {
        if (votingStartDateInput.value && this.value < votingStartDateInput.value) {
            votingStartDateInput.value = this.value;
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

