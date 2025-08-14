 <ul class="nav flex-column">
                       
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('super_admin.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('super_admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    لوحة التحكم
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('super_admin.super_admins.*') ? 'active' : '' }}" 
                                   href="{{ route('super_admin.super_admins.index') }}">
                                    <i class="fas fa-user-shield"></i>
                                    السوبرادمن
                                </a>
                            </li>
                           
                        
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('super_admin.admins.*') ? 'active' : '' }}" 
                                   href="{{ route('super_admin.admins.index') }}">
                                    <i class="fas fa-user-tie"></i>
                                    الأدمن
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('super_admin.assistants.*') ? 'active' : '' }}" 
                                   href="{{ route('super_admin.assistants.index') }}">
                                    <i class="fas fa-user-friends"></i>
                                    المساعدين
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('super_admin.voters.*') ? 'active' : '' }}" 
                                   href="{{ route('super_admin.voters.index') }}">
                                    <i class="fas fa-users"></i>
                                    الناخبين
                                </a>
                            </li>
                        
                        
                        @auth('admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    لوحة التحكم
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.elections.*') ? 'active' : '' }}" 
                                   href="{{ route('admin.elections.index') }}">
                                    <i class="fas fa-vote-yea"></i>
                                    الانتخابات
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.voters.*') ? 'active' : '' }}" 
                                   href="{{ route('voters.index') }}">
                                    <i class="fas fa-users"></i>
                                    الناخبين
                                </a>
                            </li>
                        @endauth
                        
                        @auth('assistant')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('assistant.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('assistant.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    لوحة التحكم
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('assistant.voters.*') ? 'active' : '' }}" 
                                   href="{{ route('assistant.voters.index') }}">
                                    <i class="fas fa-users"></i>
                                    الناخبين
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('assistant.help.*') ? 'active' : '' }}" 
                                   href="{{ route('assistant.help.index') }}">
                                    <i class="fas fa-question-circle"></i>
                                    طلبات المساعدة
                                </a>
                            </li>
                        @endauth
                        
                        @auth('voter')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('voter.dashboard') ? 'active' : '' }}" 
                                   href="{{ route('voter.dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    لوحة التحكم
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('voter.elections.*') ? 'active' : '' }}" 
                                   href="{{ route('voter.elections.index') }}">
                                    <i class="fas fa-vote-yea"></i>
                                    الانتخابات
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('voter.votes.*') ? 'active' : '' }}" 
                                   href="{{ route('voter.votes.history') }}">
                                    <i class="fas fa-history"></i>
                                    تاريخ التصويت
                                </a>
                            </li>
                        @endauth
                    </ul>