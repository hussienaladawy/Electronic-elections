@extends("layouts.app")

@section("title", "نتائج الانتخابات")

@section("content")
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">نتائج انتخابات: {{ $election->name }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">النتائج النهائية</h6>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <p><strong>إجمالي الأصوات:</strong> {{ $totalVotes }}</p>
                <p><strong>تاريخ انتهاء الانتخابات:</strong> {{ $election->end_date->format("Y-m-d H:i") }}</p>
            </div>
            
            @if($results->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>المرتبة</th>
                                <th>اسم المرشح</th>
                                <th>الانتماء الحزبي</th>
                                <th>عدد الأصوات</th>
                                <th>النسبة المئوية</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $index => $result)
                                <tr class="{{ $index === 0 ? 'table-success' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $result->name }}</td>
                                    <td>{{ $result->party_affiliation ?: "مستقل" }}</td>
                                    <td>{{ $result->vote_count }}</td>
                                    <td>{{ $result->percentage }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">لا توجد نتائج متاحة.</div>
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

