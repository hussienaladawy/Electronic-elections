@extends("layouts.app")

@section("title", "إحصائيات المساعد")

@section("content")
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إحصائيات المساعد</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">نظرة عامة على الإحصائيات</h6>
                </div>
                <div class="card-body">
                    <p>هنا ستظهر الإحصائيات المتعلقة بعمل المساعد.</p>
                    <ul>
                        <li>عدد الناخبين الذين تم تسجيلهم</li>
                        <li>عدد الناخبين الذين تم التحقق منهم</li>
                        <li>إحصائيات الأداء اليومية/الأسبوعية</li>
                    </ul>
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> سيتم تطوير هذه الصفحة لتشمل رسومًا بيانية تفاعلية وجداول بيانات مفصلة.
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection


