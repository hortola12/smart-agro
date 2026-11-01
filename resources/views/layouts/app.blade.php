<!DOCTYPE html>
<html lang="{{ App::getLocale() == 'en' ? 'en' : 'km' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Agro-Seed Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,300..700;1,300..700&family=Koh+Santepheap:wght@100;300;400;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nokora:wght@100;300;400;700;900&family=Preahvihear&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* 🟢 កែសម្រួលពុម្ពអក្សរ៖ ប្តូរមកប្រើពុម្ពអក្សរ Kantumruy Pro ជំនាន់ថ្មី ងាយស្រួលមើល និងមានវិជ្ជាជីវៈខ្ពស់ */
        body {
            font-family: 'Kantumruy Pro', 'Koh Santepheap', sans-serif;
            background-color: #fafdff;
            color: #333;
        }

        .bg-eco-green {
            background-color: #198754 !important;
        }

        /* ពណ៌បៃតងកសិកម្មស្តង់ដារ */
        .grow-auth-pill {
            border-radius: 50px;
            font-weight: bold;
            font-size: 13px;
            padding: 6px 16px;
            transition: all 0.3s ease;
        }

        .grow-btn-login {
            background-color: transparent;
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, 0.7);
        }

        .grow-btn-login:hover {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff;
            transform: translateY(-1px);
        }

        .grow-btn-register {
            background-color: #fff;
            color: #198754;
            border: 1.5px solid #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .grow-btn-register:hover {
            background-color: #e8f5e9;
            color: #198754;
            transform: translateY(-1px);
        }

        .dropdown-item.active,
        .dropdown-item:active {
            background-color: #198754 !important;
        }

        .nav-link-custom {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 8px 14px !important;
            border-radius: 50px;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
        }

        .nav-link-custom:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.16);
            transform: translateY(-1px);
        }

        .nav-link-custom.active {
            color: #ffffff !important;
            background-color: rgba(0, 0, 0, 0.2);
            font-weight: 800;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .hover-warning:hover {
            color: #ffc107 !important;
        }

        /* 📱 FLOATING VERTICAL QUICK DOCK (RIGHT SIDE) */
        .floating-right-dock {
            position: fixed;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1045;
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(12px);
            border: 1.5px solid rgba(25, 135, 84, 0.25);
            border-radius: 50px;
            padding: 10px 6px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
        }

        .dock-item {
            position: relative;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #198754;
            background: transparent;
            text-decoration: none;
            transition: all 0.25s ease;
            font-size: 16px;
        }

        .dock-item:hover {
            background-color: #198754;
            color: #ffffff;
            transform: scale(1.12);
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.35);
        }

        .dock-item.dock-active {
            background-color: #198754;
            color: #ffffff;
        }

        .dock-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            font-size: 9.5px;
            font-family: monospace;
            padding: 3px 6px;
            border-radius: 50px;
            background-color: #dc3545;
            color: #ffffff;
            border: 2px solid #ffffff;
            font-weight: bold;
        }

        .dock-tooltip {
            position: absolute;
            right: 54px;
            white-space: nowrap;
            background-color: #1e293b;
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            opacity: 0;
            pointer-events: none;
            transform: translateX(10px);
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .dock-item:hover .dock-tooltip {
            opacity: 1;
            transform: translateX(0);
        }

        .dock-divider {
            width: 24px;
            height: 1px;
            background-color: rgba(25, 135, 84, 0.2);
            margin: 2px 0;
        }

        @media (max-width: 576px) {
            .floating-right-dock {
                right: 8px;
                padding: 6px 4px;
                gap: 4px;
            }

            .dock-item {
                width: 36px;
                height: 36px;
                font-size: 14px;
            }

            .dock-tooltip {
                display: none;
            }
        }
    </style>
</head>

<body>

    <!-- 📞 TOP ANNOUNCEMENT & UTILITY BAR -->
    <div class="top-utility-bar py-1.5 px-3 d-none d-md-block text-white" style="background-color: #113824; font-size: 12px; border-bottom: 1px solid rgba(255,255,255,0.08);">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <span>
                    <i class="fa-solid fa-phone text-warning me-1"></i> ជំនួយកសិករ៖
                    <a href="tel:053952888" class="text-white text-decoration-none fw-bold hover-warning">053 952 888</a>
                </span>
                <span class="opacity-50">|</span>
                <span>
                    <i class="fa-solid fa-location-dot text-success me-1"></i> កសិដ្ឋានតាក្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង
                </span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span>
                    <i class="fa-solid fa-truck-fast text-warning me-1"></i> ដឹកជញ្ជូនរហ័ស ២៥ ខេត្ត-ក្រុង
                </span>
                <span class="opacity-50">|</span>
                <a href="https://t.me/" target="_blank" class="text-white text-decoration-none fw-bold hover-warning">
                    <i class="fa-brands fa-telegram text-info me-1"></i> Telegram Channel
                </a>
                <span class="opacity-50">|</span>
                <!-- Language Switcher in top bar -->
                <div class="d-inline-flex gap-1">
                    <a href="{{ url('/change-language/kh') }}" class="badge rounded-pill text-decoration-none {{ session()->get('locale') == 'kh' || !session()->has('locale') ? 'bg-warning text-dark fw-bold' : 'bg-white bg-opacity-10 text-white' }} px-2 py-1">🇰🇭 KH</a>
                    <a href="{{ url('/change-language/en') }}" class="badge rounded-pill text-decoration-none {{ session()->get('locale') == 'en' ? 'bg-warning text-dark fw-bold' : 'bg-white bg-opacity-10 text-white' }} px-2 py-1">🇬🇧 EN</a>
                </div>
            </div>
        </div>
    </div>

    <!-- 🟢 MAIN STICKY NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-eco-green sticky-top shadow-sm py-2">
        <div class="container">
            <!-- Brand Logo -->
            <a class="navbar-brand fw-bold d-flex align-items-center text-white text-decoration-none me-3" href="{{ url('/') }}">
                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-2.5 shadow-sm" style="width: 38px; height: 38px;">
                    <i class="fa-solid fa-seedling fs-5 text-success"></i>
                </div>
                <div class="d-flex flex-column leading-tight">
                    <span class="fs-5 fw-bold font-monospace" style="letter-spacing: 0.5px;">Grow2Growth</span>
                    <small class="text-warning text-uppercase" style="font-size: 9.5px; letter-spacing: 1px; font-weight: 700;">Smart Seed Traceability</small>
                </div>
            </a>

            <!-- Mobile Navbar Actions (Cart + Toggler) -->
            <div class="d-flex align-items-center gap-2 d-lg-none">
                <a href="{{ url('/cart') }}" class="btn btn-warning btn-sm rounded-pill px-2.5 py-1 fw-bold position-relative shadow-sm text-dark">
                    <i class="fa-solid fa-basket-shopping"></i>
                    @if(session('cart') && count(session('cart')) > 0)
                    <span class="badge rounded-pill bg-danger font-monospace" style="font-size: 10px;">{{ count(session('cart')) }}</span>
                    @endif
                </a>
                <button class="navbar-toggler border-0 shadow-none px-2 py-1" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMainCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <!-- Navbar Links & Search & Right Actions -->
            <div class="collapse navbar-collapse" id="navbarMainCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-bold" style="font-size: 14px;">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fa-solid fa-house me-1"></i> {{ __('menu_home') ?? 'ទំព័រដើម' }}
                        </a>
                    </li>

                    <!-- Categories Dropdown with seed counts -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-link-custom dropdown-toggle {{ Request::is('search*') && request('category_id') ? 'active' : '' }}" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-leaf me-1"></i> {{ __('menu_seeds') ?? 'គ្រាប់ពូជបន្លែ' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-agro border-0 shadow-lg rounded-4 p-2 mt-2" aria-labelledby="categoryDropdown" style="min-width: 260px; font-size: 13.5px;">
                            <li class="px-3 py-1.5 text-muted small fw-bold text-uppercase border-bottom mb-1" style="font-size: 11px; letter-spacing: 0.5px;">
                                ជ្រើសរើសតាមប្រភេទបន្លែ
                            </li>
                            <li>
                                <a class="dropdown-item rounded-3 py-2 fw-bold text-success d-flex justify-content-between align-items-center" href="{{ url('/#featured-seeds-section') }}">
                                    <span><i class="fa-solid fa-border-all me-2"></i>គ្រាប់ពូជទាំងអស់</span>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill font-monospace">All</span>
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            @if(isset($navCategories))
                            @foreach($navCategories as $cat)
                            <li>
                                <a class="dropdown-item rounded-3 py-2 text-dark d-flex justify-content-between align-items-center" href="{{ url('/search?category_id=' . $cat->id) }}">
                                    <span>
                                        <i class="fa-solid fa-chevron-right text-success me-2" style="font-size: 10px;"></i>
                                        {{ App::isLocale('en') ? $cat->name_en : $cat->name_kh }}
                                    </span>
                                    <span class="badge bg-light text-muted border rounded-pill font-monospace" style="font-size: 11px;">
                                        {{ $cat->seeds_count ?? 0 }}
                                    </span>
                                </a>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-link-custom {{ Request::is('traceability*') || Request::is('trace*') ? 'active' : '' }}" href="{{ url('/traceability') }}">
                            <i class="fa-solid fa-qrcode me-1"></i> {{ __('menu_traceability') ?? 'តាមដានប្រភពដើម' }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ url('/#farming-guides-section') }}">
                            <i class="fa-solid fa-book-open-reader me-1"></i> បច្ចេកទេសដាំដុះ
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="{{ url('/#about-farm-section') }}">
                            <i class="fa-solid fa-tractor me-1"></i> អំពីកសិដ្ឋាន
                        </a>
                    </li>
                </ul>

                <!-- Right Elements: Search, Cart, Auth -->
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 pt-2 pt-lg-0 border-top border-white border-opacity-10 border-lg-0">
                    <!-- Search Input -->
                    <form action="{{ url('/search') }}" method="GET" class="m-0">
                        <div class="input-group input-group-sm rounded-pill overflow-hidden border border-white border-opacity-25 bg-white shadow-sm px-2" style="height: 38px; min-width: 190px;">
                            <input type="text"
                                name="search"
                                class="form-control border-0 bg-white shadow-none ps-2 text-dark"
                                placeholder="ស្វែងរកគ្រាប់ពូជ..."
                                value="{{ request('search') }}"
                                style="font-size: 13px;"
                                required>
                            <button class="btn bg-white border-0 text-muted p-0 pe-1" type="submit">
                                <i class="fa-solid fa-magnifying-glass text-success" style="font-size: 13px;"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Cart Button (Desktop) -->
                    <a href="{{ url('/cart') }}" class="btn btn-warning btn-sm fw-bold rounded-pill shadow-sm d-inline-flex align-items-center px-3 text-dark" style="height: 38px;">
                        <i class="fa-solid fa-basket-shopping me-1.5"></i>
                        <span style="font-size: 13px;">{{ __('cart') ?? 'កន្ត្រក' }}</span>
                        @if(session('cart') && count(session('cart')) > 0)
                        <span class="badge rounded-pill bg-danger ms-1.5 font-monospace shadow-sm" style="font-size: 10.5px;">
                            {{ count(session('cart')) }}
                        </span>
                        @endif
                    </a>

                    <!-- Auth Profile / Login & Register -->
                    @guest
                    <div class="d-flex align-items-center gap-1.5">
                        <a class="btn grow-auth-pill grow-btn-login d-inline-flex align-items-center" href="{{ url('/login') }}" style="height: 38px;">
                            <i class="fa-solid fa-right-to-bracket me-1.5"></i> <span>ចូលប្រើ</span>
                        </a>
                        <a class="btn grow-auth-pill grow-btn-register d-inline-flex align-items-center" href="{{ url('/register') }}" style="height: 38px;">
                            <i class="fa-solid fa-user-plus me-1.5"></i> <span>ចុះឈ្មោះ</span>
                        </a>
                    </div>
                    @endguest

                    @auth
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle fw-bold btn-sm rounded-pill px-3 shadow-sm border py-1.5 d-flex align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 13px; height: 38px;">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-1.5 fw-bold" style="width: 24px; height: 24px; font-size: 12px;">
                                {{ mb_substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="text-truncate" style="max-width: 110px;">{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-2 p-1.5 bg-white" style="font-size: 13.5px; min-width: 210px;">
                            <li class="px-3 py-2 border-bottom">
                                <div class="fw-bold text-dark text-truncate">{{ Auth::user()->name }}</div>
                                <small class="text-muted d-block text-truncate">{{ Auth::user()->email }}</small>
                                @if(Auth::user()->role === 'admin')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-0.5 mt-1 small fw-bold">Admin</span>
                                @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 mt-1 small fw-bold">កសិករ (Customer)</span>
                                @endif
                            </li>
                            <li>
                                <a class="dropdown-item py-2 rounded-3 text-dark fw-bold mt-1" href="{{ url('/my-orders') }}">
                                    <i class="fa-solid fa-box-open text-primary me-2"></i> ប្រវត្តិកម្ម៉ង់ទិញ
                                </a>
                            </li>
                            @if(Auth::user()->role === 'admin')
                            <li>
                                <a class="dropdown-item py-2 rounded-3 text-dark fw-bold" href="{{ url('/admin/dashboard') }}">
                                    <i class="fa-solid fa-chart-pie text-warning me-2"></i> ផ្ទាំង Admin Dashboard
                                </a>
                            </li>
                            @endif
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger py-2 rounded-3 fw-bold" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-power-off me-2"></i> ចាកចេញពីប្រព័ន្ធ
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main class="py-4 min-vh-100">
        <div class="container">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 py-2.5 mb-4" role="alert" style="font-size: 14px; font-weight: bold;">
                <i class="fa-solid fa-circle-check me-2 text-success fs-5"></i>{{ session('success') }}
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 py-2.5 mb-4" role="alert" style="font-size: 14px; font-weight: bold;">
                <i class="fa-solid fa-circle-exclamation me-2 text-danger fs-5"></i>{{ session('error') }}
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="text-white pt-5 pb-4" style="background: linear-gradient(180deg, #163626 0%, #0d2318 100%); border-top: 4px solid #198754;">
        <div class="container">
            <div class="row g-4 mb-4">
                <!-- Col 1: Brand Info -->
                <div class="col-lg-4 col-md-6">
                    <a class="navbar-brand fw-bold d-flex align-items-center text-white mb-3 text-decoration-none" href="{{ url('/') }}" style="font-size: 20px;">
                        <i class="fa-solid fa-seedling me-2 text-warning fs-3"></i>
                        <span>Grow2Growth</span>
                    </a>
                    <p class="text-white-50 small mb-3" style="line-height: 1.7;">
                        វេទិកាទីផ្សារគ្រាប់ពូជកសិកម្មវៃឆ្លាត និងតាមដានប្រភពដើម (Traceability) ដំបូងគេនៅកម្ពុជា សហការជាមួយសហគមន៍កសិកម្មខេត្តបាត់ដំបង ធានាគ្រាប់ពូជសុទ្ធ អត្រាដំណុះ ៩៥%+។
                    </p>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                            <i class="fa-solid fa-certificate me-1"></i> សហគមន៍កសិកម្មបាត់ដំបង
                        </span>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                            <i class="fa-solid fa-qrcode me-1"></i> QR Traceable
                        </span>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div class="col-lg-2 col-md-6 col-6">
                    <h6 class="fw-bold text-warning mb-3" style="font-family: 'Kantumruy Pro', sans-serif;">តំណភ្ជាប់រហ័ស</h6>
                    <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                        <li><a href="{{ url('/') }}" class="text-white-50 text-decoration-none hover-white">ទំព័រដើម</a></li>
                        <li><a href="{{ url('/#featured-seeds-section') }}" class="text-white-50 text-decoration-none hover-white">គ្រាប់ពូជទាំងអស់</a></li>
                        <li><a href="{{ url('/traceability') }}" class="text-white-50 text-decoration-none hover-white">តាមដានលេខបាច់ QR</a></li>
                        <li><a href="{{ url('/#farming-guides-section') }}" class="text-white-50 text-decoration-none hover-white">បច្ចេកទេសដាំដុះ</a></li>
                        <li><a href="{{ url('/#about-farm-section') }}" class="text-white-50 text-decoration-none hover-white">អំពីកសិដ្ឋានដៃគូ</a></li>
                        <li><a href="{{ url('/cart') }}" class="text-white-50 text-decoration-none hover-white">កន្ត្រកទំនិញ</a></li>
                    </ul>
                </div>

                <!-- Col 3: Categories -->
                <div class="col-lg-3 col-md-6 col-6">
                    <h6 class="fw-bold text-warning mb-3" style="font-family: 'Kantumruy Pro', sans-serif;">ប្រភេទគ្រាប់ពូជ</h6>
                    <ul class="list-unstyled small mb-0 d-flex flex-column gap-2">
                        @if(isset($navCategories))
                        @foreach($navCategories as $cat)
                        <li>
                            <a href="{{ url('/#featured-seeds-section') }}"
                                onclick="setTimeout(() => { const btn = document.querySelector(`.filter-btn[data-category='{{ $cat->id }}']`); if(btn) btn.click(); }, 150);"
                                class="text-white-50 text-decoration-none hover-white">
                                <i class="fa-solid fa-leaf text-success me-1.5" style="font-size: 10px;"></i>
                                {{ App::isLocale('en') ? $cat->name_en : $cat->name_kh }}
                            </a>
                        </li>
                        @endforeach
                        @else
                        <li><span class="text-white-50">បន្លែយកស្លឹក (Leafy)</span></li>
                        <li><span class="text-white-50">បន្លែយកផ្លែ (Fruit)</span></li>
                        <li><span class="text-white-50">ពពួកមើម (Root)</span></li>
                        @endif
                    </ul>
                </div>

                <!-- Col 4: Contact & Support -->
                <div class="col-lg-3 col-md-6">
                    <h6 class="fw-bold text-warning mb-3" style="font-family: 'Kantumruy Pro', sans-serif;">ទំនាក់ទំនង & ជំនួយ</h6>
                    <ul class="list-unstyled small text-white-50 mb-3 d-flex flex-column gap-2">
                        <li class="d-flex align-items-start">
                            <i class="fa-solid fa-location-dot text-success me-2 mt-1"></i>
                            <span>ឃុំតាគ្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fa-solid fa-phone text-success me-2"></i>
                            <span>088 3322 318 / 088 3322 318</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fa-solid fa-envelope text-success me-2"></i>
                            <span>suppot@grow2growth.kh</span>
                        </li>
                    </ul>

                    <div class="d-flex gap-2">
                        <a href="https://t.me/Hor_Tola12" target="_blank" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Telegram Channel">
                            <i class="fa-brands fa-telegram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="YouTube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>

            <hr class="border-secondary opacity-25 my-4">

            <div class="row align-items-center opacity-75 small">
                <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <p class="mb-0">© 2026 <strong>Grow2Growth Agri-Tech</strong>. រក្សាសិទ្ធិគ្រប់យ៉ាង</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <span class="me-3"><i class="fa-solid fa-truck-fast text-success me-1"></i> ដឹកជញ្ជូន ២៥ ខេត្ត-ក្រុង</span>
                    <span><i class="fa-solid fa-shield-halved text-warning me-1"></i> ធានាគុណភាព ១០០%</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- 🌿 FLOATING VERTICAL QUICK DOCK (RIGHT SIDE) -->
    <div class="floating-right-dock d-none d-sm-flex" id="floatingRightDock">
        <!-- 1. Quick Cart -->
        <a href="{{ url('/cart') }}" class="dock-item {{ Request::is('cart*') ? 'dock-active' : '' }}">
            <i class="fa-solid fa-basket-shopping"></i>
            @if(session('cart') && count(session('cart')) > 0)
            <span class="dock-badge">{{ count(session('cart')) }}</span>
            @endif
            <span class="dock-tooltip">🛒 កន្ត្រកទំនិញ ({{ count(session('cart', [])) }})</span>
        </a>

        <!-- 2. Order History (My Orders) -->
        <a href="{{ url('/my-orders') }}" class="dock-item {{ Request::is('my-orders*') ? 'dock-active' : '' }}">
            <i class="fa-solid fa-receipt"></i>
            <span class="dock-tooltip">📦 ប្រវត្តិកម្ម៉ង់ទិញ (My Orders)</span>
        </a>

        <!-- 3. Fast Order Tracking (By Phone / ID) -->
        <a href="{{ url('/order-tracking') }}" class="dock-item {{ Request::is('order-tracking*') ? 'dock-active' : '' }}">
            <i class="fa-solid fa-truck-fast"></i>
            <span class="dock-tooltip">🚚 តាមដានការដឹកជញ្ជូន (Track Order)</span>
        </a>

        <!-- 4. Trace Batch QR -->
        <a href="{{ url('/traceability') }}" class="dock-item {{ Request::is('traceability*') || Request::is('trace*') ? 'dock-active' : '' }}">
            <i class="fa-solid fa-qrcode"></i>
            <span class="dock-tooltip">🔍 តាមដានលេខបាច់ (Trace Batch)</span>
        </a>

        <div class="dock-divider"></div>

        <!-- 4. Telegram Farmer Help -->
        <a href="https://t.me/Hor_Tola12" target="_blank" class="dock-item text-info">
            <i class="fa-brands fa-telegram"></i>
            <span class="dock-tooltip">💬 Telegram ជំនួយកសិករ ២៤/៧</span>
        </a>

        <!-- 5. Phone Hotline -->
        <a href="tel:0883322318" class="dock-item text-warning">
            <i class="fa-solid fa-phone"></i>
            <span class="dock-tooltip">📞 Hotline: 088 3322 318</span>
        </a>

        <div class="dock-divider"></div>

        <!-- 6. Scroll to Top -->
        <button type="button" class="dock-item border-0 bg-transparent text-secondary" onclick="window.scrollTo({ top: 0, behavior: 'smooth' });" title="ឡើងលើគេ">
            <i class="fa-solid fa-arrow-up"></i>
            <span class="dock-tooltip">⬆️ ឡើងលើគេ (Back to Top)</span>
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>