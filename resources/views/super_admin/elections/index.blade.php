@extends("layouts.app")

@section("title", "إدارة الانتخابات")

@section("content")
<div class="container-fluid">
    <!-- Page Heading -->
      
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إدارة الانتخابات</h1>
        <a href="{{ route("super_admin.elections.create") }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> إضافة انتخابات جديدة
        </a>
    </div>
    
    

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الانتخابات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalElections ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-vote-yea fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                الانتخابات النشطة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeElections ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                الانتخابات المكتملة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completedElections ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                إجمالي الأصوات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalVotes ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-poll fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">قائمة الانتخابات</h6>
            <div class="d-flex">
                <a href="{{ route("elections.export") }}" class="btn btn-info btn-sm me-2">
                    <i class="fas fa-download"></i> تصدير
                </a>
                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#bulkActionModal">
                    <i class="fas fa-cogs"></i> إجراءات مجمعة
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAllElections"></th>
                            <th>ID</th>
                            <th>العنوان</th>
                            <th>تاريخ البدء</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الحالة</th>
                            <th>عدد الأصوات</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>العنوان</th>
                            <th>تاريخ البدء</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الحالة</th>
                            <th>عدد الأصوات</th>
                            <th>الإجراءات</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse($elections as $election)
                        <tr>
                            <td><input type="checkbox" class="election-checkbox" value="{{ $election->id }}"></td>
                            <td>{{ $election->id }}</td>
                            <td>{{ $election->title }}</td>
                            <td>{{ $election->start_date ? $election->start_date->format("Y-m-d H:i") : "غير محدد" }}</td>
                            <td>{{ $election->end_date ? $election->end_date->format("Y-m-d H:i") : "غير محدد" }}</td>
                            <td>
                                @switch($election->status)
                                    @case("pending")
                                        <span class="badge bg-warning">معلقة</span>
                                        @break
                                    @case("active")
                                        <span class="badge bg-success">نشطة</span>
                                        @break
                                    @case("completed")
                                        <span class="badge bg-info">مكتملة</span>
                                        @break
                                    @case("cancelled")
                                        <span class="badge bg-danger">ملغاة</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $election->status }}</span>
                                @endswitch
                            </td>
                            <td>{{ $election->votes_count ?? 0 }}</td>
                            <td>
                                <a href="{{ route("super_admin.elections.show", $election->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route("super_admin.elections.edit", $election->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="deleteElection({{ $election->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">لا توجد انتخابات لعرضها.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkActionModalLabel">إجراءات مجمعة على الانتخابات</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="bulkActionType" class="form-label">اختر الإجراء:</label>
                    <select class="form-select" id="bulkActionType">
                        <option value="">-- اختر --</option>
                        <option value="activate">تفعيل</option>
                        <option value="complete">إكمال</option>
                        <option value="cancel">إلغاء</option>
                        <option value="delete">حذف</option>
                    </select>
                </div>
                <p>سيتم تطبيق الإجراء على الانتخابات المحددة.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="performBulkAction()">تطبيق الإجراء</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Select all checkboxes
    document.getElementById("selectAllElections").addEventListener("change", function() {
        const checkboxes = document.querySelectorAll(".election-checkbox");
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
});

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
                location.reload();
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

function performBulkAction() {
    const selectedElections = Array.from(document.querySelectorAll(".election-checkbox:checked")).map(cb => cb.value);
    const actionType = document.getElementById("bulkActionType").value;

    if (selectedElections.length === 0) {
        alert("الرجاء تحديد انتخابات واحدة على الأقل.");
        return;
    }

    if (!actionType) {
        alert("الرجاء اختيار نوع الإجراء.");
        return;
    }

    if (confirm(`هل أنت متأكد من تطبيق إجراء "${actionType}" على ${selectedElections.length} انتخابات؟`)) {
        fetch(`{{ route("elections.bulk-action") }}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector("meta[name="csrf-token"]").getAttribute("content")
            },
            body: JSON.stringify({ ids: selectedElections, action: actionType })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert("حدث خطأ أثناء تطبيق الإجراء المجمع: " + data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("حدث خطأ أثناء تطبيق الإجراء المجمع.");
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

