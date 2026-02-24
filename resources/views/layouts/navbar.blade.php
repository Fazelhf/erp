<nav class="navbar navbar-expand-lg navbar-dark shadow-sm py-2" style="background: linear-gradient(90deg, #1e293b, #0f172a);">
    <div class="container-fluid px-3 px-md-4">

        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/op.jpg') }}" alt="Fazel" class="rounded-circle" style="width:30px; height:30px; object-fit:cover;">
            <span class="d-none d-sm-inline">{{ config('app.name') }}</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link px-3 rounded {{ request()->routeIs('dashboard') ? 'active bg-primary bg-opacity-25' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door me-1"></i> داشبورد
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3 rounded {{ request()->routeIs('customers.*') ? 'active bg-primary bg-opacity-25' : '' }}" href="{{ route('customers.index') }}">
                        <i class="bi bi-people me-1"></i> مشتریان
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3 rounded {{ request()->routeIs('products.*') ? 'active bg-primary bg-opacity-25' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-box me-1"></i> محصولات
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link px-3 rounded {{ request()->routeIs('invoices.*') ? 'active bg-primary bg-opacity-25' : '' }}" href="{{ route('invoices.index') }}">
                        <i class="bi bi-file-earmark-text me-1"></i> مالی
                    </a>
                </li>

                @auth
                    @php
                        $unreadCount = \App\Models\LetterAction::where('to_user_id', auth()->id())
                                        ->where('is_read', false)
                                        ->count();
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link px-3 rounded {{ request()->routeIs('letters.inbox') ? 'active bg-primary bg-opacity-25' : '' }}" href="{{ route('letters.inbox') }}">
                            <i class="bi bi-envelope me-1"></i> کارتابل نامه‌ها
                            @if($unreadCount > 0)
                                <span class="badge rounded-pill bg-warning text-dark ms-1" style="font-size: 0.7rem;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 rounded {{ Request::is('hr*') ? 'active bg-primary bg-opacity-10 fw-semibold' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-badge me-1"></i> منابع انسانی
                        </a>
                        <ul class="dropdown-menu shadow border-0 mt-2">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('hr.index') }}">
                                    <i class="bi bi-calendar2-check me-2"></i> درخواست‌های من
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item py-2 text-crimson fw-bold" href="{{ route('hr.admin.index') }}">
                                    <i class="bi bi-shield-check me-2"></i> پنل تاییدات (مدیریت)
                                </a>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav align-items-lg-center">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="rounded-circle bg-info text-dark d-flex align-items-center justify-content-center fw-bold" style="width:30px;height:30px;font-size:12px;">
                                {{ mb_substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="d-none d-lg-inline">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 15) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2"></i> پروفایل
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger">
                                        <i class="bi bi-box-arrow-left me-2"></i> خروج
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link px-3" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> ورود
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<style>
    .text-crimson { color: #990033 !important; }
    .nav-link.active { font-weight: 600; }
    .dropdown-item:hover { background-color: #f8f9fa; }
</style>