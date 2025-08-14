@extends("layouts.app")

@section("title", "سجل التصويت")

@section("content")
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">سجل التصويت</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">الانتخابات التي صوت فيها</h6>
        </div>
        <div class="card-body">
            @if(isset($votedElections) && count($votedElections) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>اسم الانتخابات</th>
                                <th>تاريخ التصويت</th>
                                <th>المرشح الذي تم التصويت له</th>
                                <th>رمز التحقق</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($votedElections as $vote)
                                <tr>
                                    <td>{{ $vote->election->name ?? 'N/A' }}</td>
                                    <td>{{ $vote->created_at->format('Y-m-d H:i:s') }}</td>
                                    <td>{{ $vote->candidate->name ?? 'N/A' }}</td>
                                    <td><code>{{ $vote->vote_hash }}</code></td>
                                    <td>
                                        <a href="{{ route('public.results', ['election' => $vote->election->id]) }}" class="btn btn-info btn-sm">
                                            عرض النتائج
                                        </a>
                                        <a href="{{ route('public.verify', ['vote_code' => $vote->vote_hash]) }}" class="btn btn-secondary btn-sm">
                                            التحقق من الصوت
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> لا توجد انتخابات قمت بالتصويت فيها حتى الآن.
                </div>
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

