<?php  ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'نظام الانتخابات الإلكترونية')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
        }
        body {
    overflow-y: auto; /* شريط تمرير عمودي يظهر فقط عند الحاجة */
    overflow-x: hidden; /* إخفاء التمرير الأفقي إن لم يكن مطلوبًا */
}

        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            border-radius: 0.35rem;
            margin: 0.25rem 0;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            margin-left: 0.5rem;
        }
        
        .content-wrapper {
            background-color: #f8f9fc;
            min-height: 100vh;
        }
        
        .card {
            border: none;
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2653d4;
        }
        
        .text-primary {
            color: #4e73df !important;
        }
        
        .bg-primary {
            background-color: #4e73df !important;
        }
        
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
        
        .border-left-danger {
            border-left: 0.25rem solid #e74a3b !important;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation Bar -->
    @include('components.navbar')
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white">
                            <i class="fas fa-vote-yea"></i>
                            نظام الانتخابات
                        </h4>
                    </div>
                  
                    <ul class="nav flex-column">
                        {{-- Super Admin Links --}}
                    @if ( Auth::guard('super_admin')->check())
                        @can('manage users')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('super_admin.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('super_admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    لوحة التحكم
                                </a>
                            </li>
                            
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->route() && request()->routeIs('super_admin.super_admins.*') ? 'active' : '' }}" 
                                       href="{{ route('super_admin.super_admins.index') }}">
                                        <i class="fas fa-user-shield"></i>
                                        السوبرادمن
                                    </a>
                                </li>
                           @endcan

                                @can('manage admins')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->route() && request()->routeIs('super_admin.admins.*') ? 'active' : '' }}" 
                                       href="{{ route('super_admin.admins.index') }}">
                                        <i class="fas fa-user-tie"></i>
                                        الأدمن
                                    </a>
                                </li>
                                @endcan

                                @can('manage assistants')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->route() && request()->routeIs('super_admin.assistants.*') ? 'active' : '' }}" 
                                       href="{{ route('super_admin.assistants.index') }}">
                                        <i class="fas fa-user-friends"></i>
                                        المساعدين
                                    </a>
                                </li>
                                @endcan
                            

                          @can('manage voters')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('super_admin.voters.*') ? 'active' : '' }}" 
                                   href="{{ route('super_admin.voters.index') }}">
                                    <i class="fas fa-users"></i>
                                    الناخبين
                                </a>
                            </li>
                            @endcan
                           

                            @can('manage roles')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('super_admin.permissions.*') ? 'active' : '' }}"
                                   href="{{ route('super_admin.permissions.index') }}">
                                    <i class="fas fa-shield-alt"></i>
                                    إدارة الصلاحيات
                                </a>
                            </li>
                            @endcan

                            @can('manage elections')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('super_admin.elections.*') ? 'active' : '' }}" 
                                   href="{{ route('super_admin.elections.index') }}">
                                    <i class="fas fa-vote-yea"></i>
                                    الانتخابات
                                </a>
                            </li>
                            @endcan
                            
                            
                       @endif
                        
                        {{-- Admin Specific Links --}}
                        @auth('admin')
                            @can('manage users')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    لوحة التحكم
                                </a>
                            </li>
                            @endcan
                            
                            @can('manage admins')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('admin.admins.*') ? 'active' : '' }}" 
                                   href="{{ route('admin.admins.index') }}">
                                    <i class="fas fa-user-tie"></i>
                                    الأدمن
                                </a>
                            </li>
                            @endcan
                            
                            @can('manage assistants', auth('admin')->user())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('admin.assistants.*') ? 'active' : '' }}" 
                                   href="{{ route('admin.assistants.index') }}">
                                    <i class="fas fa-user-friends"></i>
                                    المساعدين
                                </a>
                            </li>
                            @endcan
                            
                            @can('manage elections')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('admin.elections.*') ? 'active' : '' }}" 
                                   href="{{ route('admin.elections.index') }}">
                                    <i class="fas fa-vote-yea"></i>
                                    الانتخابات
                                </a>
                            </li>
                            @endcan
                            @can('manage voters')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('admin.voters.*') ? 'active' : '' }}" 
                                   href="{{ route('admin.voters.index') }}">
                                    <i class="fas fa-users"></i>
                                    الناخبين
                                </a>
                            </li>
                            @endcan
                        @endauth
                        
                        {{-- Assistant Specific Links --}}
                        @auth('assistant')
                            @can('manage users')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('assistant.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('assistant.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    لوحة التحكم
                                </a>
                            </li>
                            @endcan

                            @can('manage voters')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('assistant.voters.*') ? 'active' : '' }}" 
                                   href="{{ route('assistant.voters.index') }}">
                                    <i class="fas fa-users"></i>
                                    الناخبين
                                </a>
                            </li>
                            @endcan
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('assistant.help.*') ? 'active' : '' }}" 
                                   href="#">
                                    <i class="fas fa-question-circle"></i>
                                    طلبات المساعدة
                                </a>
                            </li>
                        @endauth
                        
                        {{-- Voter Specific Links --}}
                        @auth('voter')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('voter.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('voter.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    لوحة التحكم
                                </a>
                            </li>
                            @can('view elections')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('voter.elections.*') ? 'active' : '' }}" 
                                   href="{{ route('voter.elections.index') }}">
                                    <i class="fas fa-vote-yea"></i>
                                    الانتخابات العامة
                                </a>
                            </li>
                            @endcan
                            @can('review votes')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->route() && request()->routeIs('voter.votes.*') ? 'active' : '' }}" 
                                   href="{{ route('voter.votes.history') }}">
                                    <i class="fas fa-history"></i>
                                    تاريخ التصويت
                                </a>
                            </li>
                            @endcan
                        @endauth
                    </ul>
                    
                    <hr class="my-3">
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <form method="POST" action="{{ route('auth.logout') }}">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-start w-100">
                                    <i class="fas fa-sign-out-alt"></i>
                                    تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content-wrapper">
                <div class="py-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>

