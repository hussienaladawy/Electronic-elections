@extends('layouts.app')

@section('title', 'الانتخابات المتاحة')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">الانتخابات المتاحة</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('voter.dashboard') }}">لوحة التحكم</a></li>
                <li class="breadcrumb-item active" aria-current="page">الانتخابات</li>
            </ol>
        </nav>
    </div>

    <!-- Alert for Important Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>معلومات مهمة:</strong> يمكنك التصويت في كل انتخابات مرة واحدة فقط. تأكد من اختيارك قبل التأكيد.
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs" id="electionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" 
                            type="button" role="tab" aria-controls="active" aria-selected="true">
                        <i class="fas fa-play-circle mr-1"></i>
                        الانتخابات النشطة ({{ $stats['active'] ?? 0 }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" 
                            type="button" role="tab" aria-controls="upcoming" aria-selected="false">
                        <i class="fas fa-clock mr-1"></i>
                        الانتخابات القادمة ({{ $stats['upcoming'] ?? 0 }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" 
                            type="button" role="tab" aria-controls="completed" aria-selected="false">
                        <i class="fas fa-check-circle mr-1"></i>
                        الانتخابات المكتملة ({{ $stats['completed'] ?? 0 }})
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="electionTabsContent">
                <!-- Active Elections -->
                <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                    @if(isset($active_elections) && count($active_elections) > 0)
                        <div class="row">
                            @foreach($active_elections as $election)
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100 border-left-success">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title text-success">{{ $election->name }}</h5>
                                            <span class="badge bg-success">نشطة</span>
                                        </div>
                                        
                                        <p class="card-text text-muted">{{ $election->description }}</p>
                                        
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    بدأت: {{ $election->start_date->format('Y-m-d') }}
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-times mr-1"></i>
                                                    تنتهي: {{ $election->end_date->format('Y-m-d') }}
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-users mr-1"></i>
                                                    المرشحين: {{ $election->candidates_count ?? 0 }}
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-vote-yea mr-1"></i>
                                                    الأصوات: {{ $election->votes_count ?? 0 }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Time Remaining -->
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">الوقت المتبقي:</small>
                                                <span class="badge bg-warning" id="countdown-{{ $election->id }}">
                                                    {{ $election->end_date->diffForHumans() }}
                                                </span>
                                            </div>
                                            <div class="progress mt-2" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ $election->progress_percentage ?? 50 }}%" 
                                                     aria-valuenow="{{ $election->progress_percentage ?? 50 }}" 
                                                     aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if($election->user_has_voted ?? false)
                                                <div>
                                                    <span class="badge bg-success mb-2">
                                                        <i class="fas fa-check mr-1"></i>
                                                        تم التصويت
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        رمز التحقق: <code>{{ $election->user_vote_code ?? 'N/A' }}</code>
                                                    </small>
                                                </div>
                                                <div>
                                                    <a href="{{ route('voter.elections.show', $election->id) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        عرض التفاصيل
                                                    </a>
                                                    <a href="{{ route('voter.votes.verify', $election->id) }}" 
                                                       class="btn btn-outline-success btn-sm">
                                                        التحقق من الصوت
                                                    </a>
                                                </div>
                                            @else
                                                <div>
                                                    <a href="{{ route('voter.elections.show', $election->id) }}" 
                                                       class="btn btn-outline-primary btn-sm">
                                                        عرض المرشحين
                                                    </a>
                                                </div>
                                                <div>
                                                    <a href="{{ route('voter.elections.vote', $election->id) }}" 
                                                       class="btn btn-success">
                                                        <i class="fas fa-vote-yea mr-1"></i>
                                                        صوت الآن
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-play-circle fa-4x text-gray-300 mb-3"></i>
                            <h5 class="text-muted">لا توجد انتخابات نشطة حالياً</h5>
                            <p class="text-muted">سيتم إشعارك عند بدء انتخابات جديدة</p>
                        </div>
                    @endif
                </div>

                <!-- Upcoming Elections -->
                <div class="tab-pane fade" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                    @if(isset($upcoming_elections) && count($upcoming_elections) > 0)
                        <div class="row">
                            @foreach($upcoming_elections as $election)
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100 border-left-warning">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title text-warning">{{ $election->name }}</h5>
                                            <span class="badge bg-warning">قادمة</span>
                                        </div>
                                        
                                        <p class="card-text text-muted">{{ $election->description }}</p>
                                        
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-plus mr-1"></i>
                                                    تبدأ: {{ $election->start_date->format('Y-m-d H:i') }}
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-times mr-1"></i>
                                                    تنتهي: {{ $election->end_date->format('Y-m-d H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <small class="text-muted">
                                                    <i class="fas fa-users mr-1"></i>
                                                    عدد المرشحين: {{ $election->candidates_count ?? 0 }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Time Until Start -->
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">تبدأ خلال:</small>
                                                <span class="badge bg-info">
                                                    {{ $election->start_date->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <a href="{{ route('voter.elections.show', $election->id) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    عرض التفاصيل
                                                </a>
                                            </div>
                                            <div>
                                                <button class="btn btn-outline-warning btn-sm" disabled>
                                                    <i class="fas fa-clock mr-1"></i>
                                                    لم تبدأ بعد
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clock fa-4x text-gray-300 mb-3"></i>
                            <h5 class="text-muted">لا توجد انتخابات قادمة</h5>
                            <p class="text-muted">سيتم إشعارك عند جدولة انتخابات جديدة</p>
                        </div>
                    @endif
                </div>

                <!-- Completed Elections -->
                <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                    @if(isset($completed_elections) && count($completed_elections) > 0)
                        <div class="row">
                            @foreach($completed_elections as $election)
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100 border-left-primary">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title text-primary">{{ $election->name }}</h5>
                                            <span class="badge bg-primary">مكتملة</span>
                                        </div>
                                        
                                        <p class="card-text text-muted">{{ $election->description }}</p>
                                        
                                        <div class="row mb-3">
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-check mr-1"></i>
                                                    انتهت: {{ $election->end_date->format('Y-m-d') }}
                                                </small>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-vote-yea mr-1"></i>
                                                    إجمالي الأصوات: {{ $election->votes_count ?? 0 }}
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($election->user_has_voted ?? false)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check mr-1"></i>
                                                        شاركت في التصويت
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-times mr-1"></i>
                                                        لم تشارك
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <a href="{{ route('voter.elections.results', $election->id) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    عرض النتائج
                                                </a>
                                                @if($election->user_has_voted ?? false)
                                                <a href="{{ route('voter.votes.verify', $election->id) }}" 
                                                   class="btn btn-outline-success btn-sm">
                                                    التحقق من صوتي
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-4x text-gray-300 mb-3"></i>
                            <h5 class="text-muted">لا توجد انتخابات مكتملة</h5>
                            <p class="text-muted">ستظهر هنا الانتخابات التي انتهت</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                أصواتي
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['my_votes'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-square fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                معدل المشاركة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['participation_rate'] ?? 0 }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                انتخابات فائتة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['missed_elections'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الانتخابات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_elections'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-vote-yea fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.card-header-tabs .nav-link {
    border: none;
    color: #6c757d;
}

.card-header-tabs .nav-link.active {
    background-color: transparent;
    border-bottom: 2px solid #4e73df;
    color: #4e73df;
}

@media (max-width: 768px) {
    .card-header-tabs {
        flex-direction: column;
    }
    
    .card-header-tabs .nav-item {
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update countdowns for active elections
    function updateCountdowns() {
        const countdownElements = document.querySelectorAll('[id^="countdown-"]');
        countdownElements.forEach(function(element) {
            const electionId = element.id.split('-')[1];
            // Here you would typically make an AJAX call to get updated time
            // For now, we'll just update the display
        });
    }

    // Update countdowns every minute
    setInterval(updateCountdowns, 60000);
});
</script>
@endsection

