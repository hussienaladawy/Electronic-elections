@extends("layouts.app")

@section("title", "تأكيد التصويت")

@section("content")
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">تأكيد التصويت</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-success text-white">
            <h6 class="m-0 font-weight-bold">تم تسجيل صوتك بنجاح</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                تم تسجيل صوتك بنجاح في انتخابات: <strong>{{ $vote->election->name }}</strong>
            </div>
            
            <div class="row">
                <div class="col-md-6">
    <p><strong>رمز التحقق:</strong> <code>{{ $vote->vote_hash }}</code></p>
    <p><strong>رمز التصويت (التحقق):</strong> <code>{{ $vote->vote_code }}</code></p>
    <p><strong>تاريخ التصويت:</strong> {{ $vote->created_at->format("Y-m-d H:i:s") }}</p>
</div>

                <div class="col-md-6">
                    <p><strong>الانتخابات:</strong> {{ $vote->election->name }}</p>
                    <p><strong>المرشح:</strong> {{ $vote->candidate->name }}</p>
                </div>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                احتفظ برمز التحقق للتأكد من صحة صوتك لاحقاً.
            </div>
            
            <a href="{{ route("voting.elections") }}" class="btn btn-primary">العودة للانتخابات</a>
            <a href="{{ route("public.verify") }}" class="btn btn-secondary">التحقق من الصوت</a>
        </div>
    </div>
</div>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection

