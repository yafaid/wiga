<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('judul_halaman')</title>


    <!-- General CSS Files -->
    <link rel="stylesheet" href="/assets/modules/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/modules/fontawesome/css/all.min.css">


    <!-- CSS Libraries -->
    <link rel="stylesheet" href="/assets/modules/jqvmap/dist/jqvmap.min.css">
    <link rel="stylesheet" href="/assets/modules/weather-icon/css/weather-icons.min.css">
    <link rel="stylesheet" href="/assets/modules/weather-icon/css/weather-icons-wind.min.css">
    <link rel="stylesheet" href="/assets/modules/summernote/summernote-bs4.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Template CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    @yield('header')
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-94034622-3');
    </script>

    <!-- /END GA -->
</head>

<body>
<div id="loading" class="loading-container">
  <div class="loading-spinner"></div>
</div>

    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                    </ul>

                </form>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">Hi, {{ Auth::user()->role->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @if (Auth::check() && Auth::user()->last_login_at)
                                <div class="dropdown-title">Logged in {{ Auth::user()->last_login_at->diffInMinutes() }}
                                    min ago</div>
                            @endif
                            <a href="{{ route('profil') }}" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a>SI-WIDYAGAMA</a>
                    </div>
                    <div class="sidebar-brand sidebar-brand-sm">
                        <a>SI-WG</a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Dashboard</li>
                        <li><a class="nav-link" href="{{ route('dbadmin') }}"><i class="fas fa-pencil-ruler"></i>
                                <span>Dashboard</span></a></li>
                        <li class="menu-header">Data Siswa</li>
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="fas fa-graduation-cap"></i><span>Data Siswa</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="{{ route('siswa') }}">Data Siswa</a></li>
                                <li><a class="nav-link" href="{{ route('siswaacc') }}">Akun Siswa</a></li>
                            </ul>
                        </li>
                        <li class="menu-header">Data Absensi</li>
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="fas fa-book-open"></i><span>Absen</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="{{ route('presensi.harian') }}">Presensi Harian</a></li>                                
                                <li><a class="nav-link" href="{{ route('presensi') }}">Presensi Mapel</a></li>
                            </ul>
                        </li>
                        <li class="menu-header">Data Guru</li>
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="fas fa-chalkboard"></i><span>Data Guru</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="{{ route('guru') }}">Data Guru</a></li>
                                <li><a class="nav-link" href="{{ route('gm') }}">Data Guru dan Mapelnya</a></li>
                                <li><a class="nav-link" href="{{ route('guruacc') }}">Akun Guru</a></li>
                            </ul>
                        </li>
                        <li class="menu-header">Data Kelas</li>
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="fas fa-users"></i><span>Data Kelas</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="{{ route('kelas') }}">Kelas</a></li>
                                <li><a class="nav-link" href="{{ route('jurusan') }}">Data Jurusan</a></li>
                            </ul>
                        </li>
                        <li class="menu-header">Data Mapel</li>
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="fas fa-brain"></i><span>Data Mata Pelajaran</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="{{ route('matapelajaran') }}">Data Mata Pelajaran</a></li>
                            </ul>
                        </li>
                        {{-- <li class="menu-header">Data Pembelajaran</li>
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="fas fa-atom"></i><span>Data Pembelajaran</span></a>
                        </li> --}}
                        <li class="menu-header">Data Tahun Pembelajaran</li>
                        <li class="dropdown">
                            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i
                                    class="fas fa-list-ol"></i><span>Data Tahun</span></a>
                            <ul class="dropdown-menu">
                                <li><a class="nav-link" href="{{ route('tahunpelajaran') }}">Data Tahun</a></li>
                            </ul>
                        </li>
                        <li class="menu-header">Data Admin</li>
                        <li><a class="nav-link" href="{{ route('admin') }}"><i class="fas fa-user"></i>
                                <span>Admin</span></a></li>
                    </ul>
                </aside>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    @yield('konten')
                </section>
            </div>

            <footer class="main-footer">
                SMK Widyagama 2023
            </footer>
        </div>
    </div>



    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/assets/modules/jquery.min.js"></script>
    <script src="/assets/modules/popper.js"></script>
    <script src="/assets/modules/tooltip.js"></script>
    <script src="/assets/modules/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
    <script src="/assets/modules/moment.min.js"></script>
    <script src="/assets/js/stisla.js"></script>

    <!-- JS Libraies -->
    <script src="/assets/modules/simple-weather/jquery.simpleWeather.min.js"></script>
    <script src="/assets/modules/chart.min.js"></script>
    <script src="/assets/modules/jqvmap/dist/jquery.vmap.min.js"></script>
    <script src="/assets/modules/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="/assets/modules/summernote/summernote-bs4.js"></script>
    <script src="/assets/modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    

    <!-- Page Specific JS File -->


    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
        $('#loading').hide();
    });
    </script>

</body>

</html>
