<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Sistem Penerimaan Pegawai</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        /* Custom User Dropdown Styles */
        .user-profile-dropdown {
            min-width: 280px;
            padding: 0;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-top: 8px;
        }

        .user-profile-header {
            background: linear-gradient(135deg, #6777ef 0%, #64B5F6 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .user-profile-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.2);
            object-fit: cover;
            margin-bottom: 10px;
        }

        .user-profile-body {
            padding: 15px;
        }

        .user-profile-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: #555;
            transition: all 0.3s;
            border-radius: 4px;
            text-decoration: none;
        }

        .user-profile-item:hover {
            background-color: #f8f9fa;
            color: #333;
        }

        .user-profile-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            color: #6777ef;
        }

        .user-profile-footer {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background-color: #f8f9fa;
            border-top: 1px solid #eee;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin-right: 8px;
            object-fit: cover;
        }

        .navbar-nav .nav-link.dropdown-toggle {
            display: flex;
            align-items: center;
        }
    </style>

    @stack('styles')
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                @auth
                    <li class="nav-item dropdown user-menu">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6777ef&color=fff"
                                class="user-avatar" alt="User Avatar">
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right user-profile-dropdown"
                            aria-labelledby="navbarDropdown">
                            <!-- User Profile Header -->
                            <div class="user-profile-header">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=6777ef&color=fff"
                                    alt="User Image">
                                <h5 class="mb-0 mt-2">{{ Auth::user()->name }}</h5>
                            </div>

                            <!-- User Profile Body -->
                            <div class="user-profile-body">
                                <a href="{{ route('profile.edit') }}" class="user-profile-item">
                                    <i class="fas fa-user-edit"></i>
                                    <span>Edit Profile</span>
                                </a>
                                <a href="{{ route('profile.password') }}" class="user-profile-item">
                                    <i class="fas fa-key"></i>
                                    <span>Change Password</span>
                                </a>
                            </div>

                            <!-- User Profile Footer -->
                            <div class="user-profile-footer">
                                <a href="{{ route('logout') }}" class="btn btn-sm btn-outline-danger"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </li>
                @endauth
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-light-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('dashboard') }}" class="brand-link">
                <span class="brand-text font-weight-light">Sistem Penerimaan Pegawai</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}"
                                class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @auth
                            @if (auth()->user()->isSuperAdmin())
                                <li class="nav-item">
                                    <a href="{{ route('users.index') }}"
                                        class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-user"></i>
                                        <p>Pengguna</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('vacancies.index') }}"
                                        class="nav-link {{ request()->is('vacancies*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-briefcase"></i>
                                        <p>List Pekerjaan</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('criteria_statuses.index') }}"
                                        class="nav-link {{ request()->is('criteria_statuses*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-clipboard-check"></i>
                                        <p>Kriteria Status</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('criterias.index') }}"
                                        class="nav-link {{ request()->is('criterias*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-stream"></i>
                                        <p>Kriteria</p>
                                    </a>
                                </li>
                            @endif

                            @if (auth()->user()->isSuperAdmin())
                                <li class="nav-item">
                                    <a href="{{ route('ahp.index') }}"
                                        class="nav-link {{ request()->is('ahp*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-project-diagram"></i>
                                        <p>Perhitungan AHP</p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('profile-matching.index') }}"
                                        class="nav-link {{ request()->routeIs('profile-matching.index') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-chart-line"></i>
                                        <p>Profile Matching</p>
                                    </a>
                                </li>
                            @endif

                            @if (!auth()->user()->isDirektur())
                                <li class="nav-item">
                                    <a href="{{ route('candidates.index') }}"
                                        class="nav-link {{ request()->is('candidates*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>Kandidat</p>
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a href="{{ route('profile-matching.history.index') }}"
                                    class="nav-link {{ request()->routeIs('profile-matching.history.*') ? 'active' : '' }}">
                                    <i class="nav-icon fas fa-history"></i>
                                    <p>History Matching</p>
                                </a>
                            </li>

                            @if (!auth()->user()->isDirektur())
                                <li class="nav-item">
                                    <a href="{{ route('interviews.index') }}"
                                        class="nav-link {{ request()->routeIs('interviews.*') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-user-check"></i>
                                        <p>Interview</p>
                                    </a>
                                </li>

                                
                            @endif

                        @endauth
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
                <!--
                @auth
                                <div class="sidebar-footer p-3">
                                    <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                    <a href="#" class="btn btn-block btn-outline-light"
                                        onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </a>
                                </div>
                @endauth
                -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title')</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            @yield('breadcrumb')
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="#">Sistem Penerimaan Pegawai</a>.</strong>
            All rights reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <script>
        $(document).ready(function() {
            // Pastikan dropdown bekerja
            $('.dropdown-toggle').dropdown();

            // Tutup dropdown saat klik di luar
            $(document).click(function(e) {
                if (!$(e.target).closest('.navbar-nav .dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                }
            });

            // Inisialisasi tooltip
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    @stack('scripts')
</body>

</html>
