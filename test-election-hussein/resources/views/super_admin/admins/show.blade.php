@extends("layouts.app")

@section("title", "عرض تفاصيل الأدمن")

@section("content")
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">عرض تفاصيل الأدمن</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">تفاصيل الأدمن</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>الاسم:</strong> {{ $admin->name }}</p>
                    <p><strong>البريد الإلكتروني:</strong> {{ $admin->email }}</p>
                    <p><strong>الهاتف:</strong> {{ $admin->phone }}</p>
                    <p><strong>الرقم القومي:</strong> {{ $admin->national_id }}</p>
                    <p><strong>القسم:</strong> {{ $admin->department ?? "غير محدد" }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>الحالة:</strong>
                        @if($admin->status)
                            <span class="badge bg-success">نشط</span>
                        @else
                            <span class="badge bg-danger">غير نشط</span>
                        @endif
                    </p>
                    <p><strong>الصلاحيات:</strong>
                        @if($admin->permissions && count($admin->permissions) > 0)
                            @foreach($admin->permissions as $permission)
                                <span class="badge bg-info">{{ $permission }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">لا توجد صلاحيات محددة</span>
                        @endif
                    </p>
                    <p><strong>تاريخ الإنشاء:</strong> {{ $admin->created_at->format("Y-m-d H:i:s") }}</p>
                    <p><strong>تاريخ آخر تحديث:</strong> {{ $admin->updated_at->format("Y-m-d H:i:s") }}</p>
                    <p><strong>تم الإنشاء بواسطة:</strong> {{ $admin->createdBy->name ?? "N/A" }}</p>
                    <p><strong>تم التحديث بواسطة:</strong> {{ $admin->updatedBy->name ?? "N/A" }}</p>
                </div>
            </div>
            <hr>
            <a href="{{ route("super_admin.admins.edit", $admin->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> تعديل
            </a>
            <a href="{{ route("super_admin.admins.index") }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> العودة للقائمة
            </a>
        </div>
    </div>
</div>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection

