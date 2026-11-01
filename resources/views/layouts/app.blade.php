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
        body { font-family: 'Kantumruy Pro', 'Koh Santepheap', sans-serif; background-color: #fafdff; color: #333; }
        .bg-eco-green { background-color: #198754 !important; } /* ពណ៌បៃតងកសិកម្មស្តង់ដារ */
        .grow-auth-pill { border-radius: 50px; font-weight: bold; font-size: 13px; padding: 6px 16px; transition: all 0.3s shadow; }
        .grow-btn-login { background-color: transparent; color: #fff; border: 1px solid rgba(255,255,255,0.6); }
        .grow-btn-login:hover { background-color: rgba(255,255,255,0.1); color: #fff; }
        .grow-btn-register { background-color: #fff; color: #198754; border: 1px solid #fff; margin-left: 5px; shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .grow-btn-register:hover { background-color: #e8f5e9; color: #198754; }
        .dropdown-item.active, .dropdown-item:active { background-color: #198754 !important; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-eco-green shadow-sm py-2 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ url('/') }}" style="font-size: 19px;">
                <i class="fa-solid fa-seedling me-2 text-warning fs-4"></i>
                <span>Grow2Growth</span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-bold" style="font-size: 14.5px;">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active text-warning fw-bold' : '' }}" href="{{ url('/') }}">
                            {{ __('menu_home') ?? 'ទំព័រដើម' }}
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('menu_seeds') ?? 'គ្រាប់ពូជបន្លែ' }}
                        </a>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-3 py-2" aria-labelledby="categoryDropdown" style="font-size: 13.5px;">
                            <li>
                                <a class="dropdown-item py-1.5 fw-bold text-success" href="{{ url('/#featured-seeds-section') }}">
                                    <i class="fa-solid fa-border-all me-2"></i>គ្រាប់ពូជទាំងអស់ (All Seeds)
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            @if(isset($navCategories))
                                @foreach($navCategories as $cat)
                                    <li>
                                        <a class="dropdown-item py-1.5 text-secondary" href="{{ url('/#featured-seeds-section') }}" onclick="setTimeout(() => { const btn = document.querySelector(`.filter-btn[data-category='{{ $cat->id }}']`); if(btn) btn.click(); }, 150);">
                                            <i class="fa-solid fa-chevron-right text-success me-2" style="font-size: 10px;"></i>{{ App::isLocale('en') ? $cat->name_en : $cat->name_kh }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('traceability*') || Request::is('trace*') ? 'active text-warning fw-bold' : '' }}" href="{{ url('/traceability') }}">
                            {{ __('menu_traceability') ?? 'តាមដានប្រភពដើម' }}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#farming-guides-section') }}">
                            បច្ចេកទេសដាំដុះ
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/#about-farm-section') }}">
                            អំពីកសិដ្ឋាន
                        </a>
                    </li>
                </ul>

                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2">
                    <a href="https://t.me/" target="_blank" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold d-none d-xl-inline-flex align-items-center text-decoration-none me-1" style="font-size: 12px; height: 36px; border-color: rgba(255,255,255,0.4);">
                        <i class="fa-brands fa-telegram text-warning me-1.5 fs-6"></i>
                        <span>ជំនួយកសិករ</span>
                    </a>
                    <form action="{{ url('/search') }}" method="GET" class="d-flex align-items-center m-0 me-lg-2">
                        <div class="input-group rounded-pill overflow-hidden border border-light border-opacity-25 bg-white px-2 shadow-sm" style="height: 36px; max-width: 240px;">
                            <input type="text"
                                name="search"
                                class="form-control border-0 bg-white shadow-none ps-2 small text-dark"
                                placeholder="ស្វែងរកគ្រាប់ពូជ..."
                                style="font-size: 12.5px; height: 100%;"
                                required>
                            <button class="btn bg-white border-0 text-muted p-0 pe-2" type="submit" style="height: 100%;">
                                <i class="fa-solid fa-magnifying-glass" style="font-size: 12px;"></i>
                            </button>
                        </div>
                    </form>

                    <a href="{{ url('/cart') }}" class="btn btn-success btn-sm me-lg-2 position-relative fw-bold rounded-pill border border-white border-opacity-20 d-inline-flex align-items-center" style="height: 36px; padding: 0 14px;">
                        <i class="fa-solid fa-basket-shopping me-1.5 text-warning"></i>
                        <span style="font-size: 13px;">{{ __('cart') ?? 'កន្ត្រក' }}</span>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow font-monospace" style="font-size: 10px; padding: 4px 6px;">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>

                    <div class="btn-group btn-group-sm me-lg-3 rounded-pill overflow-hidden border border-white border-opacity-20" role="group" style="height: 34px;">
                        <a href="{{ url('/change-language/kh') }}" class="btn btn-success fw-bold btn-sm d-flex align-items-center px-2 {{ session()->get('locale') == 'kh' || !session()->has('locale') ? 'active bg-white text-success' : '' }}" style="font-size: 11px;">🇰🇭 KH</a>
                        <a href="{{ url('/change-language/en') }}" class="btn btn-success fw-bold btn-sm d-flex align-items-center px-2 {{ session()->get('locale') == 'en' ? 'active bg-white text-success' : '' }}" style="font-size: 11px;">🇬🇧 EN</a>
                    </div>

                    <ul class="navbar-nav align-items-lg-center m-0 p-0 list-unstyled d-flex flex-row gap-2">
                        @guest
                            <li class="nav-item d-flex align-items-center gap-1">
                                <a class="btn grow-auth-pill grow-btn-login d-flex align-items-center" href="{{ url('/login') }}">
                                    <i class="fa-solid fa-right-to-bracket me-1"></i> <span>ចូលប្រើ</span>
                                </a>
                                <a class="btn grow-auth-pill grow-btn-register d-flex align-items-center" href="{{ url('/register') }}">
                                    <i class="fa-solid fa-user-plus me-1"></i> <span>ចុះឈ្មោះ</span>
                                </a>
                            </li>
                        @endguest

                        @auth
                            <li class="nav-item">
                                <a class="nav-link text-white fw-bold me-2 small d-flex align-items-center" href="{{ url('/my-orders') }}">
                                    <i class="fa-solid fa-box-open text-warning me-1"></i> ប្រវត្តិកម្មង់
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle fw-bold text-dark bg-light rounded-pill px-3 border py-1 d-flex align-items-center shadow-sm" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 13.5px;">
                                    <i class="fa-solid fa-circle-user text-success me-1 fs-5"></i> {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 mt-2 p-1 bg-white animated fadeIn" aria-labelledby="navbarDropdown" style="font-size: 13.5px;">
                                    @if(Auth::user()->role === 'admin')
                                        <a class="dropdown-item py-2 rounded text-dark fw-bold" href="{{ url('/admin/dashboard') }}">
                                            <i class="fa-solid fa-chart-pie text-success me-2"></i> ផ្ទាំងគ្រប់គ្រង Admin
                                        </a>
                                        <div class="dropdown-divider my-1"></div>
                                    @endif

                                    <a class="dropdown-item text-danger py-2 rounded fw-bold" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-power-off me-2"></i> ចាកចេញពីប្រព័ន្ធ
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </nav> <main class="py-4 min-vh-100">
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
                            <span>ឃុំតាក្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fa-solid fa-phone text-success me-2"></i>
                            <span>053 952 888 / 098 765 432</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class="fa-solid fa-envelope text-success me-2"></i>
                            <span>support@grow2growth.kh</span>
                        </li>
                    </ul>

                    <div class="d-flex gap-2">
                        <a href="https://t.me/" target="_blank" class="btn btn-outline-light btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;" title="Telegram Channel">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
