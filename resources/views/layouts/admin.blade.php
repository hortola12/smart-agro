<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Dashboard - Grow2Growth Agri-Tech</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

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

                <a href="{{ url('/admin/reviews') }}" class="nav-link {{ request()->is('admin/reviews*') ? 'active' : '' }}">
                    <i class="fa-solid fa-star text-warning"></i> មតិ និងការវាយតម្លៃ
                </a>

                <a href="{{ url('/admin/coupons') }}" class="nav-link {{ request()->is('admin/coupons*') ? 'active' : '' }}">
                    <i class="fa-solid fa-ticket text-danger"></i> លេខកូដបញ្ចុះតម្លៃ
                </a>

                <a href="{{ url('/admin/reports') }}" class="nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line text-info"></i> របាយការណ៍លក់ & ហិរញ្ញវត្ថុ
                </a>

                <a href="{{ url('/admin/settings') }}" class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear text-secondary"></i> ការកំណត់ប្រព័ន្ធ
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
                    <a href="{{ url('/admin/reviews') }}" class="nav-link {{ request()->is('admin/reviews*') ? 'active bg-success text-white' : 'text-white-50' }} p-2.5 rounded-3 d-flex align-items-center mb-1">
                        <i class="fa-solid fa-star text-warning me-2.5"></i> មតិ និងការវាយតម្លៃ
                    </a>
                    <a href="{{ url('/admin/coupons') }}" class="nav-link {{ request()->is('admin/coupons*') ? 'active bg-success text-white' : 'text-white-50' }} p-2.5 rounded-3 d-flex align-items-center mb-1">
                        <i class="fa-solid fa-ticket text-danger me-2.5"></i> លេខកូដបញ្ចុះតម្លៃ
                    </a>
                    <a href="{{ url('/admin/reports') }}" class="nav-link {{ request()->is('admin/reports*') ? 'active bg-success text-white' : 'text-white-50' }} p-2.5 rounded-3 d-flex align-items-center mb-1">
                        <i class="fa-solid fa-chart-line text-info me-2.5"></i> របាយការណ៍លក់ & ហិរញ្ញវត្ថុ
                    </a>
                    <a href="{{ url('/admin/settings') }}" class="nav-link {{ request()->is('admin/settings*') ? 'active bg-success text-white' : 'text-white-50' }} p-2.5 rounded-3 d-flex align-items-center mb-1">
                        <i class="fa-solid fa-gear text-secondary me-2.5"></i> ការកំណត់ប្រព័ន្ធ
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

                        <!-- 🇰🇭 CAMBODIA LIVE CLOCK & MINI CALENDAR DROPDOWN -->
                        <div class="dropdown d-none d-md-inline-block">
                            <button class="btn btn-light bg-white border rounded-pill px-3 py-1.5 shadow-sm d-flex align-items-center gap-2 text-dark dropdown-toggle custom-calendar-toggle"
                                    type="button"
                                    id="adminCalendarDropdownBtn"
                                    data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside"
                                    aria-expanded="false"
                                    title="ចុចដើម្បីមើលប្រតិទិន និងម៉ោងកម្ពុជា">
                                <span class="d-flex align-items-center text-success">
                                    <i class="fa-solid fa-calendar-days me-1.5 fs-6"></i>
                                    <span id="cambodiaLiveDate" class="fw-bold" style="font-size: 13px;">
                                        ថ្ងៃនេះ៖ {{ now('Asia/Phnom_Penh')->format('d-m-Y') }}
                                    </span>
                                </span>
                                <span class="text-muted opacity-50">|</span>
                                <span class="d-flex align-items-center font-monospace">
                                    <i class="fa-regular fa-clock text-primary me-1.5" style="font-size: 12px;"></i>
                                    ម៉ោង <b id="cambodiaLiveClock" class="fw-bold text-dark ms-1" style="font-size: 13px;">
                                        {{ now('Asia/Phnom_Penh')->format('H:i:s') }}
                                    </b>
                                </span>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-2 py-0.5 ms-0.5" style="font-size: 10px;">
                                    🇰🇭 KH
                                </span>
                            </button>

                            <!-- 🗓️ MINI CALENDAR DROPDOWN POPUP -->
                            <div class="dropdown-menu dropdown-menu-start shadow-lg border-0 rounded-4 p-3 mt-2"
                                 style="width: 320px; z-index: 1060;"
                                 aria-labelledby="adminCalendarDropdownBtn">
                                
                                <!-- Calendar Header with Month/Year Navigation -->
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                    <button type="button" class="btn btn-sm btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" id="prevMonthBtn" title="ខែមុន">
                                        <i class="fa-solid fa-chevron-left text-muted" style="font-size: 11px;"></i>
                                    </button>
                                    <div class="text-center">
                                        <span class="fw-bold text-dark d-block" id="calendarMonthYearKh" style="font-size: 13.5px; font-family: 'Kantumruy Pro', sans-serif;">---</span>
                                        <small class="text-muted font-monospace" id="calendarMonthYearEn" style="font-size: 11px;">---</small>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-light rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" id="nextMonthBtn" title="ខែបន្ទាប់">
                                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 11px;"></i>
                                    </button>
                                </div>

                                <!-- Day Names Row (Khmer) -->
                                <div class="calendar-days-header d-grid text-center text-muted fw-bold mb-2" style="grid-template-columns: repeat(7, 1fr); font-size: 11px;">
                                    <div class="text-danger">អា</div>
                                    <div>ច</div>
                                    <div>អ</div>
                                    <div>ព</div>
                                    <div>ព្រ</div>
                                    <div>សុ</div>
                                    <div class="text-success">ស</div>
                                </div>

                                <!-- Calendar Days Grid (Populated dynamically) -->
                                <div id="calendarDaysGrid" class="d-grid text-center gap-1 mb-3" style="grid-template-columns: repeat(7, 1fr); font-size: 12px;">
                                    <!-- Days rendered via JS -->
                                </div>

                                <!-- Footer / Live Clock details in Cambodia Time -->
                                <div class="pt-2 border-top bg-light rounded-3 p-2">
                                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                                        <small class="text-muted" style="font-size: 11px;">
                                            <i class="fa-solid fa-earth-asia text-success me-1"></i>ម៉ោងកម្ពុជា (UTC+7)៖
                                        </small>
                                        <strong id="cambodiaFullClock" class="font-monospace text-success fw-bold" style="font-size: 12.5px;">
                                            {{ now('Asia/Phnom_Penh')->format('h:i:s A') }}
                                        </strong>
                                    </div>
                                    <div class="d-flex gap-1.5 justify-content-between">
                                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-2.5 py-0.5 text-muted small" id="jumpToTodayBtn" style="font-size: 11px;">
                                            <i class="fa-solid fa-arrow-rotate-left me-1"></i>ថ្ងៃនេះ
                                        </button>
                                        <a href="{{ url('/admin/orders/packing-manifest') }}" class="btn btn-sm btn-success rounded-pill px-2.5 py-0.5 fw-bold text-white text-decoration-none shadow-sm" style="font-size: 11px;">
                                            <i class="fa-solid fa-boxes-packing me-1"></i>បញ្ជីទំនិញថ្ងៃនេះ
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <!-- 🔔 INTERACTIVE REAL-TIME NOTIFICATION BELL -->
                        <div class="dropdown me-1">
                            <button class="btn btn-light rounded-circle shadow-sm border p-0 position-relative d-flex align-items-center justify-content-center"
                                    type="button"
                                    id="adminNotificationBtn"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    style="width: 38px; height: 38px;"
                                    title="ការជូនដំណឹងសំខាន់ៗ">
                                <i class="fa-solid fa-bell {{ ($adminTotalAlertsCount ?? 0) > 0 ? 'text-danger' : 'text-secondary' }} fs-6"></i>
                                @if(($adminTotalAlertsCount ?? 0) > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger font-monospace" style="font-size: 10px;">
                                        {{ $adminTotalAlertsCount }}
                                    </span>
                                @endif
                            </button>

                            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-0 bg-white overflow-hidden"
                                 style="width: 340px; max-width: 90vw;"
                                 aria-labelledby="adminNotificationBtn">
                                <!-- Notification Header -->
                                <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                                    <h6 class="fw-bold text-dark m-0 d-flex align-items-center small" style="font-family: 'Kantumruy Pro', sans-serif;">
                                        <i class="fa-solid fa-bell text-warning me-2"></i>ការជូនដំណឹង (Notifications)
                                    </h6>
                                    <span class="badge {{ ($adminTotalAlertsCount ?? 0) > 0 ? 'bg-danger' : 'bg-secondary' }} rounded-pill font-monospace" style="font-size: 10.5px;">
                                        {{ $adminTotalAlertsCount ?? 0 }} ថ្មី
                                    </span>
                                </div>

                                <!-- Notification Body with Scrollable List -->
                                <div class="list-group list-group-flush overflow-auto" style="max-height: 380px;">
                                    <!-- 1. Pending Orders -->
                                    @if(isset($adminPendingOrders) && $adminPendingOrders->count() > 0)
                                        <div class="px-3 py-1.5 bg-danger bg-opacity-10 border-bottom">
                                            <small class="text-danger fw-bold" style="font-size: 11px;">
                                                <i class="fa-solid fa-receipt me-1"></i>ការកម្ម៉ង់ថ្មីត្រូវចាត់ចែង ({{ $adminPendingOrdersCount }})
                                            </small>
                                        </div>
                                        @foreach($adminPendingOrders as $pOrder)
                                            <a href="{{ url('/admin/orders?search=' . urlencode($pOrder->customer_phone)) }}" class="list-group-item list-group-item-action py-2.5 px-3 border-bottom">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="fw-bold text-dark small">#{{ $pOrder->id }} - {{ $pOrder->customer_name }}</span>
                                                    <span class="text-danger fw-bold font-monospace small">${{ number_format($pOrder->total_price, 2) }}</span>
                                                </div>
                                                <small class="text-muted d-block" style="font-size: 11px;">
                                                    <i class="fa-regular fa-clock me-1"></i>{{ $pOrder->created_at->diffForHumans() }} · <span class="badge bg-warning text-dark" style="font-size: 9.5px;">រង់ចាំ</span>
                                                </small>
                                            </a>
                                        @endforeach
                                    @endif

                                    <!-- 2. Low Stock Seeds -->
                                    @if(isset($adminLowStockSeeds) && $adminLowStockSeeds->count() > 0)
                                        <div class="px-3 py-1.5 bg-warning bg-opacity-15 border-bottom">
                                            <small class="text-dark fw-bold" style="font-size: 11px;">
                                                <i class="fa-solid fa-triangle-exclamation text-warning me-1"></i>គ្រាប់ពូជជិតអស់ពីស្តុក ({{ $adminLowStockCount }})
                                            </small>
                                        </div>
                                        @foreach($adminLowStockSeeds as $lsSeed)
                                            <a href="{{ url('/admin/seeds?search=' . urlencode($lsSeed->name_kh)) }}" class="list-group-item list-group-item-action py-2 px-3 border-bottom">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-dark small fw-bold text-truncate" style="max-width: 180px;">{{ $lsSeed->name_kh }}</span>
                                                    <span class="badge bg-danger rounded-pill font-monospace" style="font-size: 10px;">សល់ {{ $lsSeed->stock }}</span>
                                                </div>
                                            </a>
                                        @endforeach
                                    @endif

                                    <!-- 3. Expiring Batches -->
                                    @if(isset($adminExpiringBatches) && $adminExpiringBatches->count() > 0)
                                        <div class="px-3 py-1.5 bg-info bg-opacity-10 border-bottom">
                                            <small class="text-primary fw-bold" style="font-size: 11px;">
                                                <i class="fa-solid fa-hourglass-half me-1"></i>វគ្គផលិតជិតផុតកំណត់ ({{ $adminExpiringBatchesCount }})
                                            </small>
                                        </div>
                                        @foreach($adminExpiringBatches as $eBatch)
                                            <a href="{{ url('/admin/traceability') }}" class="list-group-item list-group-item-action py-2 px-3 border-bottom">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-dark small fw-bold font-monospace">{{ $eBatch->batch_number }}</span>
                                                    <span class="text-danger small font-monospace" style="font-size: 10.5px;">{{ $eBatch->expiry_date }}</span>
                                                </div>
                                                <small class="text-muted text-truncate d-block" style="font-size: 10.5px;">{{ $eBatch->seed?->name_kh ?? 'គ្រាប់ពូជ' }}</small>
                                            </a>
                                        @endforeach
                                    @endif

                                    <!-- Empty state -->
                                    @if(($adminTotalAlertsCount ?? 0) === 0)
                                        <div class="p-4 text-center text-muted">
                                            <i class="fa-solid fa-circle-check fs-2 text-success opacity-50 mb-2"></i>
                                            <p class="mb-0 fw-bold small">មិនមានការជូនដំណឹងថ្មីទេ</p>
                                            <small style="font-size: 11px;">ទិន្នន័យស្តុក និងការកម្ម៉ង់ដំណើរការល្អធម្មតា!</small>
                                        </div>
                                    @endif
                                </div>

                                <!-- Notification Footer -->
                                <div class="p-2 bg-light border-top text-center">
                                    <a href="{{ url('/admin/dashboard') }}" class="btn btn-sm btn-link text-success fw-bold text-decoration-none py-1 small">
                                        <i class="fa-solid fa-chart-pie me-1"></i> ទៅកាន់ Dashboard ពិនិត្យសរុប
                                    </a>
                                </div>
                            </div>
                        </div>

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

    <script>
        // 🇰🇭 CAMBODIA LIVE TICKING CLOCK (Updates every second with HH:mm:ss in Asia/Phnom_Penh)
        function updateCambodiaClock() {
            const now = new Date();
            try {
                // 24-hour format with seconds (13:45:08)
                const timeStr = now.toLocaleTimeString('en-GB', {
                    timeZone: 'Asia/Phnom_Penh',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                });

                // 12-hour format with seconds (01:45:08 PM)
                const fullStr = now.toLocaleTimeString('en-US', {
                    timeZone: 'Asia/Phnom_Penh',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });

                // Cambodia Date (02-11-2026)
                const dateParts = new Intl.DateTimeFormat('en-GB', {
                    timeZone: 'Asia/Phnom_Penh',
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                }).format(now);

                const clockEl = document.getElementById('cambodiaLiveClock');
                const fullClockEl = document.getElementById('cambodiaFullClock');
                const dateEl = document.getElementById('cambodiaLiveDate');

                if (clockEl) clockEl.innerText = timeStr;
                if (fullClockEl) fullClockEl.innerText = fullStr;
                if (dateEl) dateEl.innerText = `ថ្ងៃនេះ៖ ${dateParts.replace(/\//g, '-')}`;
            } catch (err) {
                console.error("Clock update error:", err);
            }
        }
        setInterval(updateCambodiaClock, 1000);
        updateCambodiaClock();

        // 🗓️ INTERACTIVE CAMBODIA CALENDAR GENERATOR
        (function() {
            let displayedDate = new Date();
            const khmerMonths = [
                'មករា', 'កុម្ភៈ', 'មីនា', 'មេសា', 'ឧសភា', 'មិថុនា',
                'កក្កដា', 'សីហា', 'កញ្ញា', 'តុលា', 'វិច្ឆិកា', 'ធ្នូ'
            ];

            function renderCalendar() {
                const grid = document.getElementById('calendarDaysGrid');
                const titleKh = document.getElementById('calendarMonthYearKh');
                const titleEn = document.getElementById('calendarMonthYearEn');
                if (!grid || !titleKh) return;

                const year = displayedDate.getFullYear();
                const month = displayedDate.getMonth();

                const khmerYear = String(year).replace(/[0-9]/g, d => '០១២៣៤៥៦៧៨៩'[d]);
                titleKh.innerText = `ខែ${khmerMonths[month]} ឆ្នាំ${khmerYear}`;
                titleEn.innerText = `${displayedDate.toLocaleString('en-US', { month: 'long' })} ${year}`;

                grid.innerHTML = '';

                // First day index (0 = Sunday, 1 = Monday ...)
                const firstDayIndex = new Date(year, month, 1).getDay();
                const totalDays = new Date(year, month + 1, 0).getDate();
                const prevDaysCount = new Date(year, month, 0).getDate();

                // Current day in Cambodia Time
                const cambodiaNow = new Date(new Date().toLocaleString("en-US", { timeZone: "Asia/Phnom_Penh" }));
                const isCurrentMonth = (cambodiaNow.getFullYear() === year && cambodiaNow.getMonth() === month);
                const currentDayNum = cambodiaNow.getDate();

                // Previous month filler days
                for (let i = firstDayIndex; i > 0; i--) {
                    const day = prevDaysCount - i + 1;
                    const cell = document.createElement('div');
                    cell.className = 'calendar-day-cell other-month';
                    cell.innerText = day;
                    grid.appendChild(cell);
                }

                // Current month days
                for (let d = 1; d <= totalDays; d++) {
                    const cell = document.createElement('div');
                    cell.className = 'calendar-day-cell';
                    if (isCurrentMonth && d === currentDayNum) {
                        cell.classList.add('today');
                        cell.title = 'ថ្ងៃនេះ (Today)';
                    }
                    cell.innerText = d;
                    grid.appendChild(cell);
                }

                // Next month filler days
                const totalCells = firstDayIndex + totalDays;
                const remaining = totalCells <= 35 ? (35 - totalCells) : (42 - totalCells);
                for (let j = 1; j <= remaining; j++) {
                    const cell = document.createElement('div');
                    cell.className = 'calendar-day-cell other-month';
                    cell.innerText = j;
                    grid.appendChild(cell);
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                renderCalendar();

                const prevBtn = document.getElementById('prevMonthBtn');
                const nextBtn = document.getElementById('nextMonthBtn');
                const todayBtn = document.getElementById('jumpToTodayBtn');

                if (prevBtn) {
                    prevBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        displayedDate.setMonth(displayedDate.getMonth() - 1);
                        renderCalendar();
                    });
                }
                if (nextBtn) {
                    nextBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        displayedDate.setMonth(displayedDate.getMonth() + 1);
                        renderCalendar();
                    });
                }
                if (todayBtn) {
                    todayBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        displayedDate = new Date();
                        renderCalendar();
                    });
                }
            });
        })();
    </script>
</body>
</html>
