<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <i class="fas fa-vote-yea me-2"></i>
            <span class="font-weight-bold">نظام الانتخابات</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
           

            <ul class="navbar-nav me-auto">  
                @auth('super_admin')
                    @can('manage roles')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->route() && request()->routeIs('super_admin.dashboard') ? 'active' : '' }}" href="{{ route('super_admin.dashboard') }}">لوحة التحكم</a>
                    </li>
                    @endcan
                    @can('manage users')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="usersDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            إدارة المستخدمين
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="usersDropdown">
                            <li><a class="dropdown-item" href="{{ route('super_admin.super_admins.index') }}">السوبرادمن</a></li>
                            <li><a class="dropdown-item" href="{{ route('super_admin.admins.index') }}">الأدمن</a></li>
                            <li><a class="dropdown-item" href="{{ route('super_admin.assistants.index') }}">المساعدين</a></li>
                            <li><a class="dropdown-item" href="{{ route('super_admin.voters.index') }}">الناخبين</a></li>
                        </ul>
                    </li>
                    @endcan
                    @can('manage elections')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->route() && request()->routeIs('super_admin.elections.*') ? 'active' : '' }}" href="{{ route('super_admin.elections.index') }}">الانتخابات</a>
                    </li>
                    @endcan
                    @if(session('active_guard') === 'super_admin')
                    @can('manage roles')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->route() && request()->routeIs('super_admin.permissions.*') ? 'active' : '' }}" href="{{ route('super_admin.permissions.index') }}">الصلاحيات</a>
                    </li>
                    @endcan
                    @endif
                @endauth

  


                @auth('admin')
                    @can('manage users')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">لوحة التحكم</a>
                    </li>
                    @endcan
                    @can('manage admins')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.admins.index') }}">الأدمن</a>
                    </li>
                    @endcan
                    @can('manage assistants')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.assistants.index') }}">المساعدين</a>
                    </li>
                    @endcan
                    @can('manage elections')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.elections.index') }}">الانتخابات</a>
                    </li>
                    @endcan
                    @can('manage voters')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.voters.index') }}">الناخبين</a>
                    </li>
                    @endcan
                @endauth

                @auth('assistant')
                    @can('manage assistants')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->route() && request()->routeIs('assistant.dashboard') ? 'active' : '' }}" href="{{ route('assistant.dashboard') }}">لوحة التحكم</a>
                    </li>
                    @endcan
                    @can('manage voters')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->route() && request()->routeIs('assistant.voters.*') ? 'active' : '' }}" href="{{ route('assistant.voters.index') }}">الناخبين</a>
                    </li>
                    @endcan
                @endauth

                @auth('voter')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->route() && request()->routeIs('voter.dashboard') ? 'active' : '' }}" href="{{ route('voter.dashboard') }}">لوحة التحكم</a>
                    </li>
                    @can('view elections')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->route() && request()->routeIs('voter.elections.*') ? 'active' : '' }}" href="{{ route('voter.elections.index') }}">الانتخابات</a>
                    </li>
                    @endcan
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.elections') }}">الانتخابات العامة</a>
                    </li>
                @endguest
            </ul>

            {{-- User Profile and Logout --}}
            <ul class="navbar-nav">
                @if (Auth::guard('super_admin')->check() || Auth::guard('admin')->check() || Auth::guard('assistant')->check() || Auth::guard('voter')->check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if (Auth::guard('super_admin')->check())
                                {{ Auth::guard('super_admin')->user()->name }}
                            @elseif (Auth::guard('admin')->check())
                                {{ Auth::guard('admin')->user()->name }}
                            @elseif (Auth::guard('assistant')->check())
                                {{ Auth::guard('assistant')->user()->name }}
                            @elseif (Auth::guard('voter')->check())
                                {{ Auth::guard('voter')->user()->name }}
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#">الملف الشخصي</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('auth.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">تسجيل الخروج</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('auth.login') }}">تسجيل الدخول</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('voter.register') }}">تسجيل</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
