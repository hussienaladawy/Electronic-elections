@extends("layouts.app")

@section("title", "تقارير المساعد")

@section("content")
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تقارير المساعد</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">تقارير العمل</h6>
                </div>
                <div class="card-body">
                    <p>هنا ستظهر التقارير المتعلقة بعمل المساعد.</p>
                    <ul>
                        <li>تقرير الناخبين المسجلين</li>
                        <li>تقرير الناخبين المحققين</li>
                        <li>تقرير الأنشطة اليومية</li>
                        <li>تقرير الأداء الشهري</li>
                    </ul>
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> سيتم تطوير هذه الصفحة لتشمل تقارير مفصلة قابلة للتصدير.
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

