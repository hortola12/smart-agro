<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard - Grow2Growth Agri-Tech</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,300..700;1,300..700&family=Koh+Santepheap:wght@100;300;400;700;900&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* 🟢 កែសម្រួល៖ កំណត់ពុម្ពអក្សរ Kantumruy Pro ទៅលើផ្ទាំង Admin ទាំងមូល ឱ្យមើលទៅស្រទន់ និងមានវិជ្ជាជីវៈខ្ពស់ */
        body {
            background-color: #f8fafc;
            font-family: 'Kantumruy Pro', 'Segoe UI', Roboto, sans-serif;
            color: #333;
        }
        .sidebar {
            min-width: 260px;
            max-width: 260px;
            background-color: #1e2225; /* ប្តូរពណ៌ឱ្យរលោងជាងមុនបន្តិច */
            min-height: 100vh;
            transition: all 0.3s;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 11px 20px;
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 13.5px;
            border-radius: 8px;
            margin: 5px 15px;
            transition: all 0.2s ease-in-out;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.08);
            transform: translateX(3px);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #198754; /* ពណ៌បៃតងកសិកម្មជោគជ័យ */
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.2);
        }
        .sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 15px;
        }
        .main-content {
            width: 100%;
            min-height: 100vh;
            background-color: #f8fafc;
        }
        .admin-navbar {
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            border-bottom: 1px solid #edf2f7;
        }
        .dropdown-item:active {
            background-color: #198754 !important;
        }
    </style>
</head>
<body>

    <div class="d-flex">
        <div class="sidebar d-none d-md-block shadow">
            <div class="p-4 border-bottom border-secondary border-opacity-25 text-center">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <h4 class="fw-bold text-white m-0 d-flex align-items-center justify-content-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                        <i class="fa-solid fa-seedling text-warning me-2 fs-3"></i>Grow2Growth
                    </h4>
                    <small class="text-success fw-bold" style="font-size: 10px; letter-spacing: 1px;">SMART SEED TRACEABILITY</small>
                </a>
            </div>

            <div class="py-3">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie text-warning"></i> ផ្ទាំងគ្រប់គ្រងទូទៅ
                </a>

                <a href="{{ url('/admin/seeds') }}" class="nav-link {{ request()->is('admin/seeds*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box-open text-info"></i> គ្រប់គ្រងគ្រាប់ពូជ
                </a>

                <a href="{{ url('/admin/traceability') }}" class="nav-link {{ request()->is('admin/traceability*') ? 'active' : '' }}">
                    <i class="fa-solid fa-route text-success"></i> គ្រប់គ្រងប្រភពដើម
                </a>

                <a href="{{ url('/admin/orders') }}" class="nav-link d-flex align-items-center justify-content-between {{ request()->is('admin/orders*') ? 'active' : '' }}">
                    <span><i class="fa-solid fa-receipt text-danger"></i> គ្រប់គ្រងការកម្ម៉ង់</span>
                    @if(isset($adminPendingOrdersCount) && $adminPendingOrdersCount > 0)
                        <span class="badge bg-danger rounded-pill font-monospace" style="font-size: 11px;">{{ $adminPendingOrdersCount }}</span>
                    @endif
                </a>

                <a href="{{ url('/admin/categories') }}" class="nav-link {{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags text-primary"></i> គ្រប់គ្រងប្រភេទបន្លែ
                </a>

                <a href="{{ url('/admin/customers') }}" class="nav-link {{ request()->is('admin/customers*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users text-warning"></i> បញ្ជីអតិថិជន
                </a>

                <hr class="text-secondary mx-3 my-3 opacity-25">

                <a href="{{ url('/') }}" class="nav-link text-white-50">
                    <i class="fa-solid fa-globe text-primary"></i> ទៅកាន់វេបសាយខាងមុខ
                </a>
            </div>
        </div>

        <!-- 📱 OFFCANVAS SIDEBAR FOR MOBILE ADMIN -->
        <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="adminMobileSidebar" style="width: 280px; background-color: #1e2225 !important;">
            <div class="offcanvas-header border-bottom border-secondary border-opacity-25 p-3">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <h5 class="fw-bold text-white m-0 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                        <i class="fa-solid fa-seedling text-warning me-2 fs-4"></i>Grow2Growth
                    </h5>
                    <small class="text-success fw-bold" style="font-size: 10px; letter-spacing: 1px;">ADMIN PANEL</small>
                </a>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0 py-3">
                <div class="px-2">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard*') ? 'active bg-success text-white' : 'text-white-50' }} p-2.5 rounded-3 d-flex align-items-center mb-1">
                        <i class="fa-solid fa-chart-pie text-warning me-2.5"></i> ផ្ទាំងគ្រប់គ្រងទូទៅ
                    </a>
                    <a href="{{ url('/admin/seeds') }}" class="nav-link {{ request()->is('admin/seeds*') ? 'active bg-success text-white' : 'text-white-50' }} p-2.5 rounded-3 d-flex align-items-center mb-1">
                        <i class="fa-solid fa-box-open text-info me-2.5"></i> គ្រប់គ្រងគ្រាប់ពូជ
                    </a>
                    <a href="{{ url('/admin/traceability') }}" class="nav-link {{ request()->is('admin/traceability*') ? 'active bg-success text-white' : 'text-white-50' }} p-2.5 rounded-3 d-flex align-items-center mb-1">
                        <i class="fa-solid fa-route text-success me-2.5"></i> គ្រប់គ្រងប្រភពដើម
                    </a>
                    <a href="{{ url('/admin/orders') }}" class="nav-link {{ request()->is('admin/orders*') ? 'active bg-success text-white' : 'text-white-50' }} p-2.5 rounded-3 d-flex align-items-center justify-content-between mb-1">
                        <span><i class="fa-solid fa-receipt text-danger me-2.5"></i> គ្រប់គ្រងការកម្ម៉ង់</span>
                        @if(isset($adminPendingOrdersCount) && $adminPendingOrdersCount > 0)
                            <span class="badge bg-danger rounded-pill font-monospace">{{ $adminPendingOrdersCount }}</span>
                        @endif
                    </a>
                    <a href="{{ url('/admin/categories') }}" class="nav-link {{ request()->is('admin/categories*') ? 'active bg-success text-white' : 'text-white-50' }} p-2.5 rounded-3 d-flex align-items-center mb-1">
                        <i class="fa-solid fa-tags text-primary me-2.5"></i> គ្រប់គ្រងប្រភេទបន្លែ
                    </a>
                    <a href="{{ url('/admin/customers') }}" class="nav-link {{ request()->is('admin/customers*') ? 'active bg-success text-white' : 'text-white-50' }} p-2.5 rounded-3 d-flex align-items-center mb-1">
                        <i class="fa-solid fa-users text-warning me-2.5"></i> បញ្ជីអតិថិជន
                    </a>
                    <hr class="text-secondary opacity-25 my-3">
                    <a href="{{ url('/') }}" class="nav-link text-white-50 p-2.5 rounded-3 d-flex align-items-center mb-1">
                        <i class="fa-solid fa-globe text-primary me-2.5"></i> ទៅកាន់វេបសាយខាងមុខ
                    </a>
                </div>
            </div>
        </div>

        <div class="main-content d-flex flex-column">
            <nav class="navbar navbar-expand-lg admin-navbar py-2 px-3 px-md-4 mb-4">
                <div class="container-fluid p-0 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <!-- Mobile Hamburger Button -->
                        <button class="btn btn-outline-success btn-sm rounded-pill me-2 d-md-none px-2.5 py-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminMobileSidebar">
                            <i class="fa-solid fa-bars fs-6"></i>
                        </button>

                        <span class="navbar-brand fw-bold text-dark fs-6 d-md-none m-0" style="font-family: 'Kantumruy Pro', sans-serif;">
                            <i class="fa-solid fa-seedling text-success me-1"></i>Admin Panel
                        </span>

                        <span class="text-muted small d-none d-md-inline-block">
                            <i class="fa-solid fa-calendar-day text-success me-1"></i>
                            ថ្ងៃនេះ៖ <b>{{ date('d-m-Y') }}</b> | ម៉ោង <b>{{ date('H:i') }}</b>
                        </span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ url('/') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold d-none d-sm-inline-flex align-items-center" style="font-size: 12.5px;">
                            <i class="fa-solid fa-globe me-1.5"></i> វេបសាយខាងមុខ
                        </a>

                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle fw-bold btn-sm rounded-pill px-3 shadow-sm border py-1.5 d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 13px;">
                                <i class="fa-solid fa-user-shield text-success me-1.5"></i>
                                {{ auth()->check() ? auth()->user()->name : 'អ្នកគ្រប់គ្រង (Admin)' }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2 p-1.5 bg-white" style="font-size: 13px; min-width: 180px;">
                                <li class="px-3 py-1.5 border-bottom mb-1">
                                    <small class="text-muted d-block">ចូលប្រើជា</small>
                                    <span class="fw-bold text-dark">{{ auth()->check() ? auth()->user()->name : 'Admin' }}</span>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger py-2 rounded-3 fw-bold" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-power-off me-2"></i>ចាកចេញពីប្រព័ន្ធ
                                    </a>
                                </li>
                            </ul>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-4 flex-grow-1 pb-5">
                @yield('admin_content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
