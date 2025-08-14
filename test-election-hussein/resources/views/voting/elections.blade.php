@extends("layouts.app")

@section("title", "الانتخابات المتاحة للتصويت")

@section("content")
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">الانتخابات المتاحة للتصويت</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">قائمة الانتخابات</h6>
        </div>
        <div class="card-body">
            @if($elections->count() > 0)
                <div class="row">
                    @foreach($elections as $election)
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100 border-left-primary">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $election->name }}</h5>
                                    <p class="card-text">{{ $election->description }}</p>
                                    <p class="card-text"><small class="text-muted">تبدأ: {{ $election->start_date->format("Y-m-d H:i") }}</small></p>
                                    <p class="card-text"><small class="text-muted">تنتهي: {{ $election->end_date->format("Y-m-d H:i") }}</small></p>
                                    <a href="{{ route("voting.show", $election->id) }}" class="btn btn-primary">التصويت الآن</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">لا توجد انتخابات متاحة حالياً.</div>
            @endif
        </div>
    </div>
</div>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection

