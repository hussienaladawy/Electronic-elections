@extends("layouts.app")

@section("title", "نتائج الانتخابات")

@section("content")
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">نتائج الانتخابات: {{ $election->title }}</div>

                <div class="card-body">
                    @if(count($results) > 0)
                        <h5 class="mb-3">إجمالي الأصوات: {{ $totalVotes }}</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th>المرشح</th>
                                        <th>الانتماء الحزبي</th>
                                        <th>عدد الأصوات</th>
                                        <th>النسبة المئوية</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($results as $result)
                                        <tr>
                                            <td>
                                                @if($result->image)
                                                    <img src="{{ asset("storage/candidates/" . $result->image) }}" alt="{{ $result->name }}" class="img-thumbnail rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                @endif
                                                {{ $result->name }}
                                            </td>
                                            <td>{{ $result->party_affiliation ?: 'مستقل' }}</td>
                                            <td>{{ $result->vote_count }}</td>
                                            <td>
                                                <div class="progress" style="height: 25px;">
                                                    <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: {{ $result->percentage }}%;" aria-valuenow="{{ $result->percentage }}" aria-valuemin="0" aria-valuemax="100">{{ $result->percentage }}%</div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info text-center" role="alert">
                            <i class="fas fa-info-circle"></i> لا توجد نتائج متاحة لهذه الانتخابات بعد.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


