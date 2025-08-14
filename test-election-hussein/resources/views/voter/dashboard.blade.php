@extends('layouts.app')

@section('title', 'لوحة تحكم الناخب')

@section('content')

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">لوحة تحكم الناخب</h1>
                    <p class="mb-0 text-muted">مرحباً {{ auth('voter')->user()->name }}</p>
                </div>
                <div>
                    <span class="badge bg-success">مسجل</span>
                    <small class="text-muted ms-2">{{ now()->format('Y-m-d H:i') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert for Active Elections -->
    @if(isset($stats['available_elections']) && $stats['available_elections'] > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-vote-yea md-2"></i>
                <strong>انتخابات نشطة!</strong> يوجد {{ $stats['available_elections'] }} انتخابات متاحة للتصويت.
                <a href="#available-elections" class="alert-link">انقر هنا للمشاركة</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Available Elections -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col md-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                الانتخابات المتاحة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                
                               {{ $stats['available_elections'] ?? 0 }}

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-vote-yea fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- My Votes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col md-2">
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

        <!-- Pending Elections -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col md-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                انتخابات قادمة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pending_elections'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col md-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                الإشعارات الجديدة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['new_notifications'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bell fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Available Elections -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow mb-4" id="available-elections">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">الانتخابات المتاحة للتصويت</h6>
                    <a href="{{ route('voter.elections.index') }}" class="btn btn-sm btn-primary">
                        عرض الكل
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recentElections) && count($recentElections) > 0)
                        @foreach($recentElections as $election)
                        <div class="card mb-3 border-left-primary">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="card-title mb-2">{{ $election->name }}</h5>
                                        <p class="card-text text-muted">{{ Str::limit($election->description, 100) }}</p>
                                        <div class="d-flex align-items-center">
                                            <small class="text-muted md-3">
                                                <i class="fas fa-calendar md-1"></i>
                                                ينتهي في: {{ $election->end_date->format('Y-m-d H:i') }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-users md-1"></i>
                                                {{ $election->candidates_count ?? 0 }} مرشح
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end">
                                        @if($votedElections->contains($election->id))
                                            <span class="badge bg-success mb-2">تم التصويت</span><br>
                                            <a href="{{ route('voter.votes.verify', $election->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                التحقق من الصوت
                                            </a>
                                        @else
                                            <a href="{{ route('voting.show', $election->id) }}" 
                                               class="btn btn-primary">
                                                <i class="fas fa-vote-yea md-1"></i>
                                                صوت الآن
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-vote-yea fa-4x text-gray-300 mb-3"></i>
                            <h5 class="text-muted">لا توجد انتخابات متاحة حالياً</h5>
                            <p class="text-muted">سيتم إشعارك عند توفر انتخابات جديدة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 mb-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">الإجراءات السريعة</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="{{ route('elections.index') }}" class="btn btn-primary btn w-100">
                                <i class="fas fa-vote-yea mb-1"></i><br>
                                <small>الانتخابات</small>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('voter.votes.history') }}" class="btn btn-success btn w-100">
                                <i class="fas fa-history mb-1"></i><br>
                                <small>تاريخ التصويت</small>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('voter.profile') }}" class="btn btn-info btn w-100">
                                <i class="fas fa-user-cog mb-1"></i><br>
                                <small>الملف الشخصي</small>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="#" class="btn btn-warning btn w-100">
                                <i class="fas fa-question-circle mb-1"></i><br>
                                <small>المساعدة</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Notifications -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">الإشعارات الأخيرة</h6>
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        عرض الكل
                    </a>
                </div>
                <div class="card-body">
                    @if(isset($recentNotifications) && count($recentNotifications) > 0)
                        @foreach($recentNotifications as $notification)
                        <div class="d-flex align-items-start border-bottom py-2">
                            <div class="md-3">
                                @if($notification->notification->type == 'election')
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-vote-yea text-white"></i>
                                    </div>
                                @elseif($notification->notification->type == 'reminder')
                                    <div class="icon-circle bg-warning">
                                        <i class="fas fa-bell text-white"></i>
                                    </div>
                                @else
                                    <div class="icon-circle bg-info">
                                        <i class="fas fa-info text-white"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold small">{{ $notification->notification->title }}</div>
                                <div class="text-muted small">{{ Str::limit($notification->notification->message, 60) }}</div>
                                <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>
                            @if(!$notification->read_at)
                            <div>
                                <span class="badge bg-primary">جديد</span>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-bell fa-2x text-gray-300 mb-2"></i>
                            <p class="text-muted small">لا توجد إشعارات جديدة</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Voting Guide -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">دليل التصويت</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex align-items-center">
                            <div class="md-3">
                                <div class="icon-circle bg-primary">
                                    <span class="text-white font-weight-bold">1</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-weight-bold small">اختر الانتخابات</div>
                                <div class="text-muted small">من القائمة المتاحة</div>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="md-3">
                                <div class="icon-circle bg-success">
                                    <span class="text-white font-weight-bold">2</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-weight-bold small">اختر المرشح</div>
                                <div class="text-muted small">اقرأ البرامج بعناية</div>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="md-3">
                                <div class="icon-circle bg-warning">
                                    <span class="text-white font-weight-bold">3</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-weight-bold small">أكد التصويت</div>
                                <div class="text-muted small">تأكد من اختيارك</div>
                            </div>
                        </div>
                        <div class="list-group-item d-flex align-items-center">
                            <div class="md-3">
                                <div class="icon-circle bg-info">
                                    <span class="text-white font-weight-bold">4</span>
                                </div>
                            </div>
                            <div>
                                <div class="font-weight-bold small">احفظ رمز التحقق</div>
                                <div class="text-muted small">للتحقق لاحقاً</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    body {
    padding-top: 70px; /* عدل الرقم حسب ارتفاع الـ Navbar */
}
</style>
@endsection