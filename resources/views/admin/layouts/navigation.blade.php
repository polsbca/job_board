@php
    $navLinks = [
        'Dashboard'    => ['route' => 'admin.dashboard',          'pattern' => 'admin.dashboard',         'icon' => 'fas fa-globe'],
        'Jobs'         => ['route' => 'admin.jobs.index',         'pattern' => 'admin.jobs.*',            'icon' => 'fas fa-briefcase'],
        'Users'        => ['route' => 'admin.users.index',        'pattern' => 'admin.users.*',           'icon' => 'fas fa-users'],
        'Applications' => ['route' => 'admin.applications.index', 'pattern' => 'admin.applications.*',    'icon' => 'fas fa-file-alt'],
    ];
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        {{-- Brand --}}
        <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">
            {{ config('app.name', 'Laravel') }} Admin
        </a>

        {{-- Toggler --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar"
                aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar Content --}}
        <div class="collapse navbar-collapse" id="adminNavbar">
            {{-- Left: Navigation Links --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @foreach($navLinks as $label => $data)
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs($data['pattern']) ? 'active fw-semibold' : '' }}"
                           href="{{ route($data['route']) }}">
                            <i class="{{ $data['icon'] }} me-2"></i> {{ __($label) }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Right: User Dropdown --}}
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="adminUserDropdown" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user me-1"></i> {{ e(Auth::user()->name) }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminUserDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-cog me-2"></i> Profile
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
