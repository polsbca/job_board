<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Job Board') }} - Admin Dashboard</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            width: 250px;
            transition: all 0.3s;
        }

        #sidebar.active {
            margin-left: -250px;
        }

        #content {
            flex: 1;
            padding: 0;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }

            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="bg-light">
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-light border-end">
            <div class="sidebar-header p-3 border-bottom">
                <h4 class="mb-0">Job Board</h4>
            </div>
            <ul class="nav flex-column p-2">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-dark">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.jobs.index') }}" class="nav-link text-dark">
                        <i class="fas fa-briefcase me-2"></i> Jobs
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link text-dark">
                        <i class="fas fa-users me-2"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.applications.index') }}" class="nav-link text-dark">
                        <i class="fas fa-file-alt me-2"></i> Applications
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">
                <div class="container-fluid">
                    <button id="sidebarCollapse" class="btn btn-outline-secondary">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="ms-auto">
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-2"></i>{{ auth()->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-edit me-2"></i> Profile
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="py-4 px-3">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('sidebarCollapse');
            const sidebar = document.getElementById('sidebar');
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('active');
            });
        });
    </script>
</body>
</html>
