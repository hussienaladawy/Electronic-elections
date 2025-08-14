@extends('layouts.app')

@section('title', 'لوحة المساعد الإداري')

@section('content')
<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0 text-gray-800">لوحة تحكم المساعد</h1>
            <p class="mb-0 text-muted">مرحباً {{ auth('assistant')->user()->name }}</p>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-users fa-3x me-3"></i>
                    <div>
                        <h5 class="card-title">إجمالي الناخبين</h5>
                        <h3>{{ $stats['voters_count'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-user-tie fa-3x me-3"></i>
                    <div>
                        <h5 class="card-title">إجمالي المرشحين</h5>
                        <h3>{{ $stats['candidates_count'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-poll fa-3x me-3"></i>
                    <div>
                        <h5 class="card-title">الانتخابات النشطة</h5>
                        <h3>{{ $stats['available_elections_count'] ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-link me-2"></i>روابط سريعة</h5>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('assistant.voters.import') }}" class="btn btn-outline-success">
                    <i class="fas fa-upload me-2"></i>رفع بيانات الناخبين
                </a>
                <a href="{{ route('assistant.voters.export') }}" class="btn btn-outline-primary">
                    <i class="fas fa-download me-2"></i>تقرير الناخبين
                </a>
                <a href="#" class="btn btn-outline-warning">
                    <i class="fas fa-envelope me-2"></i>مراسلة الناخبين
                </a>
            </div>
        </div>
    </div>

    {{-- Recent Elections Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>أحدث الانتخابات النشطة</h5>
        </div>
        <div class="card-body">
            @if(isset($recentElections) && count($recentElections) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>اسم الانتخاب</th>
                                <th>النوع</th>
                                <th>تاريخ البدء</th>
                                <th>تاريخ الانتهاء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentElections as $election)
                                <tr>
                                    <td>{{ $election->title }}</td>
                                    <td>{{ $election->type }}</td>
                                    <td>{{ $election->start_date }}</td>
                                    <td>{{ $election->end_date }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-muted">لا توجد انتخابات نشطة حالياً</p>
            @endif
        </div>
    </div>

</div>
<style>
    body {
    padding-top: 70px; /* Adjust based on Navbar height */
}
</style>
@endsection