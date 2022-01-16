<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/fontawesome-free/css/all.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('css/datatables-bs4/dataTables.bootstrap4.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('css/overlayScrollbars/OverlayScrollbars.min.css') }}">
    @yield('css')
</head>

@if (session('role') == 1)

    <body class="hold-transition sidebar-mini layout-fixed sidebar-collapse" onload="resetSelection()">
    @else

        <body class="hold-transition layout-fixed sidebar-collapse" onload="currentDate()">
@endif
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-sm bg-dark">
        @if (session('role') == 1)
            <ul class="navbar-nav">
                <li class="nav-item" style="display: flex;align-items: center">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: #156A8F">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
        @endif
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand text-uppercase text-white fw-bold fs-1" href="index">Hệ thống điểm danh</a>
            </div>
            <ul class="navbar-nav float-right" style="float:right">
                <li class="nav-item"><a class="nav-link text-white fs-5" href="#">{{ session('name') }}</a></li>
                <li class="nav-item"><a class="nav-link text-white fs-5" href="{{ route('logout') }}">Đăng xuất</a></li>
            </ul>
        </div>
    </nav>
    <!-- /.navbar -->

    @if (session('role') == 1)
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('index') }}" class="brand-link">
                <img src="{{ asset('img/bkacad.png') }}" alt="HỆ THỐNG ĐIỂM DANH BKACAD" class="brand-image" />
                <span class="brand-text font-weight-light font-weight-bold">BKACAD</span>
            </a>
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-school"></i>
                                <p>
                                    NIÊN KHÓA
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('new_schoolyear') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tạo mới</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('schoolyear') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách niên khóa</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-graduation-cap"></i>
                                <p>
                                    CHUYÊN NGÀNH
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('new_major') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tạo mới</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('major') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách chuyên ngành</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    MÔN HỌC
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('new_subject') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tạo mới</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('subject') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách môn học</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    LỚP HỌC
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('new_class') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tạo mới</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('class') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách lớp học</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-table"></i>
                                <p>
                                    KHÓA HỌC
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('new_course') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tạo mới</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('course') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Danh sách khóa học</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>
    @endif

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="card card-body ">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
        <!-- /.content-wrapper -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2021 <a href="#">Attendance Application</a>.</strong>
            All rights reserved.
        </footer>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->


    <!-- jQuery -->
    <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/datatables-buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/datatables-buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/datatables-buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('js/datatables-bs4/dataTables.bootstrap4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('js/overlayScrollbars/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('/js/adminlte.js') }}"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
    @yield('course-dropdown-js')
</body>
</html>
