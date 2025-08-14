@extends("layouts.app")

@section("title", "الانتخابات المتاحة")

@section("content")
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">الانتخابات المتاحة</div>

                <div class="card-body">
                    @if(session("error"))
                        <div class="alert alert-danger" role="alert">
                            {{ session("error") }}
                        </div>
                    @endif

                    @if(count($elections) > 0)
                        <div class="row">
                            @foreach($elections as $election)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title text-primary">{{ $election->title }}</h5>
                                            <p class="card-text text-muted">{{ $election->description }}</p>
                                            <ul class="list-group list-group-flush mb-3">
                                                <li class="list-group-item">
                                                    <strong>تاريخ البدء:</strong> {{ $election->start_date->format("Y-m-d H:i") }}
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>تاريخ الانتهاء:</strong> {{ $election->end_date->format("Y-m-d H:i") }}
                                                </li>
                                                <li class="list-group-item">
                                                    <strong>الحالة:</strong> 
                                                    @if($election->status == "active" && $election->start_date->isPast() && $election->end_date->isFuture())
                                                        <span class="badge bg-success">مفتوحة للتصويت</span>
                                                    @elseif($election->status == "active" && $election->start_date->isFuture())
                                                        <span class="badge bg-info">قادمة</span>
                                                    @elseif($election->status == "completed" || $election->end_date->isPast())
                                                        <span class="badge bg-secondary">منتهية</span>
                                                    @else
                                                        <span class="badge bg-warning">{{ $election->status }}</span>
                                                    @endif
                                                </li>
                                            </ul>
                                            <div class="mt-auto">
                                                @if($election->isVotingOpen())
                                                    <a href="{{ route("voting.show", $election->id) }}" class="btn btn-primary btn-block">صوت الآن</a>
                                                @elseif($election->status == "completed" || $election->end_date->isPast())
                                                    <a href="{{ route("voting.results", $election->id) }}" class="btn btn-info btn-block">عرض النتائج</a>
                                                @else
                                                    <button class="btn btn-secondary btn-block" disabled>غير متاحة حالياً</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info text-center" role="alert">
                            <i class="fas fa-info-circle"></i> لا توجد انتخابات متاحة حالياً.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


