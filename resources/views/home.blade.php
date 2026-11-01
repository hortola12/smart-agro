@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/home-style.css') }}">

@section('content')

@auth
    @if(Auth::user()->role === 'admin')
        <div class="alert alert-dark border-0 shadow-sm rounded-4 d-flex justify-content-between align-items-center p-3 mb-4" style="background-color: #1e293b;">
            <div class="text-white small d-flex align-items-center">
                <i class="fa-solid fa-user-shield text-warning me-2 fs-5"></i>
                <span>អ្នកកំពុងចូលប្រើប្រាស់ជា៖ <b>អ្នកគ្រប់គ្រង (Admin Mode)</b></span>
            </div>
            <a href="{{ url('/admin/dashboard') }}" class="btn btn-warning btn-sm fw-bold rounded-pill px-3 shadow-sm">
                <i class="fa-solid fa-chart-pie me-1"></i> ចូលទៅផ្ទាំង Admin Dashboard
            </a>
        </div>
    @endif
@endauth

<!-- 🌿 ផ្នែកទី ១៖ HERO BANNER -->
<div class="hero-box p-4 p-md-5 mb-5 shadow-sm border-start border-success border-5 position-relative overflow-hidden">
    <div class="container-fluid py-2 position-relative" style="z-index: 2;">
        <div class="mb-3">
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3 py-2 fw-bold" style="font-size: 13px;">
                <i class="fa-solid fa-seedling text-success me-1.5"></i> សហគមន៍កសិកម្មឆ្លាតវៃ ខេត្តបាត់ដំបង (Certified Battambang Seeds)
            </span>
        </div>

        <h1 class="display-6 fw-bold text-success mb-3" style="font-family: 'Kantumruy Pro', sans-serif;">
            {{ __('banner_title') }}
        </h1>
        <p class="col-md-9 fs-6 text-muted mb-4" style="line-height: 1.8;">
            {{ __('banner_desc') }}
        </p>

        <!-- Search & Action Controls -->
        <div class="row g-3 align-items-center mb-4">
            <div class="col-auto">
                <a href="#featured-seeds-section" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm d-inline-flex align-items-center text-decoration-none" style="height: 48px; font-size: 15px;">
                    <i class="fa-solid fa-wheat-awn me-2"></i>
                    <span>{{ __('shop_now') }}</span>
                </a>
            </div>

            <div class="col-md-6 col-lg-5">
                <form action="{{ url('/trace-batch') }}" method="GET" class="m-0">
                    <div class="input-group search-wrapper shadow-sm rounded-pill overflow-hidden border border-success bg-white p-1" style="height: 48px;">
                        <span class="input-group-text bg-white border-0 text-success ps-3">
                            <i class="fa-solid fa-qrcode fs-5"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-0 bg-white search-input" placeholder="វាយលេខ Batch តាមដាន... (ឧ. BATCH-2026-LT01)" required>
                        <button class="btn btn-success px-3 fw-bold rounded-pill d-flex align-items-center search-btn" type="submit" style="font-size: 14px;">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> តាមដាន
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Micro Stat Chips -->
        <div class="d-flex flex-wrap gap-2 pt-2">
            <div class="stat-chip">
                <i class="fa-solid fa-chart-line text-success me-2"></i>
                <span>៩៨% អត្រាដុះលូតលាស់ខ្ពស់</span>
            </div>
            <div class="stat-chip">
                <i class="fa-solid fa-shield-halved text-success me-2"></i>
                <span>១០០% តាមដានលេខបាច់ QR</span>
            </div>
            <div class="stat-chip">
                <i class="fa-solid fa-truck-fast text-success me-2"></i>
                <span>ដឹកជញ្ជូនរហ័ស ២៥ ខេត្ត-ក្រុង</span>
            </div>
            <div class="stat-chip">
                <i class="fa-solid fa-handshake-angle text-success me-2"></i>
                <span>គាំទ្រកសិករមូលដ្ឋាន</span>
            </div>
        </div>
    </div>

    <div class="position-absolute end-0 bottom-0 opacity-10 text-success d-none d-lg-block" style="font-size: 180px; transform: translate(20px, 30px); z-index: 1;">
        <i class="fa-solid fa-leaf"></i>
    </div>
</div>

<!-- 🌟 ផ្នែកទី ២៖ ចំណុចលេចធ្លោ ៣ យ៉ាង (Core Features) -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="feature-card p-4 shadow-sm h-100 d-flex align-items-start">
            <div class="feature-icon me-3">
                <i class="fa-solid fa-seedling fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">គ្រាប់ពូជសុទ្ធ ១០០%</h6>
                <p class="text-muted small mb-0">គ្រាប់ពូជមានគុណភាពខ្ពស់ ឆ្លងកាត់ការពិសោធន៍ អត្រាដុះលូតលាស់ខ្លាំង និងគ្មានជំងឺឆ្លង។</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="feature-card p-4 shadow-sm h-100 d-flex align-items-start">
            <div class="feature-icon me-3">
                <i class="fa-solid fa-qrcode fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">តាមដានប្រភពច្បាស់លាស់</h6>
                <p class="text-muted small mb-0">រាល់គ្រាប់ពូជទាំងអស់ អាចស្កេនពិនិត្យមើលកសិដ្ឋានដើម ថ្ងៃប្រមូលផល និងកាលបរិច្ឆេទផុតកំណត់។</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="feature-card p-4 shadow-sm h-100 d-flex align-items-start">
            <div class="feature-icon me-3">
                <i class="fa-solid fa-headset fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">ប្រឹក្សាបច្ចេកទេសឥតគិតថ្លៃ</h6>
                <p class="text-muted small mb-0">មានអ្នកឯកទេសកសិកម្មរង់ចាំផ្តល់ការណែនាំពីរបៀបរៀបចំដី ជី និងការថែទាំដំណាំគ្រប់ដំណាក់កាល។</p>
            </div>
        </div>
    </div>
</div>

<!-- 🔄 ផ្នែកទី ៣៖ របៀបដែលប្រព័ន្ធដំណើរការ (3 Steps) -->
<div class="mb-5 p-4 p-md-5 bg-white rounded-4 shadow-sm">
    <div class="text-center mb-4">
        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5 fw-bold mb-2">
            <i class="fa-solid fa-route me-1"></i> ងាយស្រួល និងរហ័ស
        </span>
        <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">របៀបដែលប្រព័ន្ធដំណើរការ</h4>
        <p class="text-muted small mb-0">ត្រឹមតែ ៣ ជំហានងាយៗ ក្នុងការស្វែងរក តាមដាន និងបញ្ជាទិញគ្រាប់ពូជកសិកម្មវៃឆ្លាត</p>
    </div>

    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="step-box">
                <div class="step-circle mb-3 mx-auto d-flex align-items-center justify-content-center text-success rounded-circle shadow-sm">
                    <span class="fs-4 fw-bold">1</span>
                </div>
                <h6 class="fw-bold text-dark" style="font-family: 'Kantumruy Pro', sans-serif;">1. ជ្រើសរើសគ្រាប់ពូជ</h6>
                <p class="text-muted small px-3">ជ្រើសរើសប្រភេទគ្រាប់ពូជបន្លែដែលលោកអ្នកចង់ដាំដុះ រួចមើលព័ត៌មានលម្អិត និងកម្រិតលូតលាស់។</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="step-box">
                <div class="step-circle mb-3 mx-auto d-flex align-items-center justify-content-center text-success rounded-circle shadow-sm">
                    <span class="fs-4 fw-bold">2</span>
                </div>
                <h6 class="fw-bold text-dark" style="font-family: 'Kantumruy Pro', sans-serif;">2. ពិនិត្យប្រភពដើម</h6>
                <p class="text-muted small px-3">វាយបញ្ចូលលេខ Batch ឬស្កេនដើម្បីមើលកសិដ្ឋានផលិត និងអត្រាលូតលាស់ពិតប្រាកដចេញពីមន្ទីរពិសោធន៍។</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="step-box">
                <div class="step-circle mb-3 mx-auto d-flex align-items-center justify-content-center text-success rounded-circle shadow-sm">
                    <span class="fs-4 fw-bold">3</span>
                </div>
                <h6 class="fw-bold text-dark" style="font-family: 'Kantumruy Pro', sans-serif;">3. បញ្ជាទិញ & ដឹកជូនដល់ចម្ការ</h6>
                <p class="text-muted small px-3">ដាក់ចូលកន្ត្រកទំនិញ បំពេញព័ត៌មានដឹកជញ្ជូន រួចរង់ចាំទទួលគ្រាប់ពូជដល់ទីកន្លែងប្រកបដោយសុវត្ថិភាព។</p>
            </div>
        </div>
    </div>
</div>

<!-- 🌾 ផ្នែកទី ៤៖ បញ្ជីគ្រាប់ពូជលេចធ្លោ & FILTER រហ័ស -->
<div id="featured-seeds-section" class="mb-5 pt-3">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="section-title m-0 fw-bold text-success d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                <span class="p-2 bg-success bg-opacity-10 rounded-3 me-3 d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-wheat-awn"></i>
                </span>
                <span>{{ __('featured_seeds') }}</span>
            </h4>
            <small class="text-muted d-block mt-2">
                បង្ហាញ <span id="visible-seeds-count" class="fw-bold text-success">{{ $seeds->count() }}</span> ក្នុងចំណោម {{ $seeds->count() }} មុខគ្រាប់ពូជ
            </small>
        </div>

        <!-- Live Search & Sort in Seed Grid -->
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <div class="input-group input-group-sm rounded-pill overflow-hidden border bg-white shadow-sm" style="max-width: 240px; height: 38px;">
                <span class="input-group-text bg-white border-0 text-muted ps-3">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input type="text" id="seed-live-search" class="form-control border-0 bg-white" placeholder="ស្វែងរកក្នុងបញ្ជី..." style="font-size: 13px;">
            </div>

            <select id="seed-sort-select" class="form-select form-select-sm rounded-pill border bg-white shadow-sm px-3" style="width: auto; height: 38px; font-size: 13px; font-weight: 600;">
                <option value="default">តម្រៀប៖ លំនាំដើម</option>
                <option value="price-asc">តម្លៃ៖ ទាបទៅខ្ពស់ ($)</option>
                <option value="price-desc">តម្លៃ៖ ខ្ពស់ទៅទាប ($)</option>
                <option value="rate-desc">អត្រាដំណុះ៖ ខ្ពស់បំផុត (%)</option>
            </select>
        </div>
    </div>

    <!-- Category Filter Pills -->
    <div class="filter-card p-3 mb-4 shadow-sm">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <span class="text-muted small fw-bold me-1"><i class="fa-solid fa-filter me-1 text-success"></i>ប្រភេទ៖</span>
                <button type="button" class="btn btn-sm filter-btn active" data-category="all">
                    <i class="fa-solid fa-border-all me-1"></i> {{ App::isLocale('en') ? 'All' : 'ទាំងអស់' }} ({{ $seeds->count() }})
                </button>
                @if(isset($categories))
                    @foreach($categories as $category)
                        <button type="button" class="btn btn-sm filter-btn" data-category="{{ $category->id }}">
                            {{ App::isLocale('en') ? $category->name_en : $category->name_kh }}
                            <span class="opacity-75">({{ $category->seeds_count ?? $category->seeds->count() }})</span>
                        </button>
                    @endforeach
                @endif
            </div>

            <button type="button" id="reset-filter-btn" class="btn btn-sm btn-link text-muted text-decoration-none p-0 d-none">
                <i class="fa-solid fa-rotate-left me-1"></i>កំណត់ឡើងវិញ
            </button>
        </div>
    </div>

    <!-- Seed Cards Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" id="seed-grid-container">
        @foreach($seeds as $seed)
        @php
            $rate = $seed->traceability ? $seed->traceability->germination_rate : 90.00;
            $rielPrice = number_format($seed->price * 4100);
        @endphp
        <div class="col seed-item"
             data-category-id="{{ $seed->category_id }}"
             data-name="{{ mb_strtolower($seed->name_kh . ' ' . $seed->name_en) }}"
             data-price="{{ $seed->price }}"
             data-rate="{{ $rate }}"
             data-stock="{{ $seed->stock }}">
            <div class="card seed-card h-100 shadow-sm border-0 position-relative">
                <!-- Image Wrapper with Overlays -->
                <div class="seed-image-wrapper">
                    @if($seed->image)
                        <img src="{{ asset('uploads/seeds/' . $seed->image) }}" class="card-img-top seed-image w-100" alt="{{ $seed->name_kh }}">
                    @else
                        <div class="seed-placeholder-img w-100">
                            <i class="fa-solid fa-seedling fs-1 mb-2 opacity-50"></i>
                            <span class="small fw-bold">Grow2Growth Seed</span>
                        </div>
                    @endif

                    <!-- Batch Tag (Clickable to trace) -->
                    @if($seed->batch_number)
                        <a href="{{ url('/trace?batch_number=' . urlencode($seed->batch_number)) }}"
                           class="batch-badge-pill position-absolute top-0 start-0 m-2.5 z-2"
                           title="ស្កេនពិនិត្យប្រភពដើម">
                            <i class="fa-solid fa-qrcode text-warning me-1"></i>{{ $seed->batch_number }}
                        </a>
                    @endif

                    <!-- Stock Status Pill -->
                    @if($seed->stock > 5)
                        <span class="stock-badge-pill bg-success text-white position-absolute top-0 end-0 m-2.5 z-2">
                            <i class="fa-solid fa-circle-check me-1"></i>មានស្តុក ({{ $seed->stock }})
                        </span>
                    @elseif($seed->stock > 0)
                        <span class="stock-badge-pill bg-warning text-dark position-absolute top-0 end-0 m-2.5 z-2">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>សល់តិច ({{ $seed->stock }})
                        </span>
                    @else
                        <span class="stock-badge-pill bg-danger text-white position-absolute top-0 end-0 m-2.5 z-2">
                            <i class="fa-solid fa-ban me-1"></i>អស់ស្តុក
                        </span>
                    @endif
                </div>

                <div class="card-body p-3 d-flex flex-column">
                    <!-- Category Badge -->
                    <div class="mb-2">
                        <span class="category-badge d-inline-block">
                            @if(App::isLocale('en'))
                                {{ $seed->category?->name_en ?? 'Vegetable Seed' }}
                            @else
                                {{ $seed->category?->name_kh ?? 'គ្រាប់ពូជបន្លែ' }}
                            @endif
                        </span>
                    </div>

                    <!-- Seed Title -->
                    <h6 class="card-title fw-bold mb-1 text-dark text-truncate" title="{{ $seed->name_kh }}" style="font-family: 'Kantumruy Pro', sans-serif;">
                        {{ App::isLocale('en') ? $seed->name_en : $seed->name_kh }}
                    </h6>
                    <small class="text-muted mb-2 d-block text-truncate" style="font-size: 11.5px;">
                        {{ App::isLocale('en') ? $seed->name_kh : $seed->name_en }}
                    </small>

                    <!-- Price -->
                    <div class="mb-2">
                        <span class="price-tag">${{ number_format($seed->price, 2) }}</span>
                        <span class="text-muted small">({{ $rielPrice }} ៛) {{ __('per_pack') }}</span>
                    </div>

                    <!-- Germination Progress Bar -->
                    <div class="mb-3 mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 11px;">
                            <span class="text-muted fw-bold">
                                <i class="fa-solid fa-seedling text-success me-1"></i>
                                {{ __('growth_potential') }}
                            </span>
                            <span class="text-success fw-bold">
                                {{ number_format($rate, 1) }}%
                            </span>
                        </div>

                        <div class="progress" style="height: 6px; background-color: #e9ecef;">
                            <div class="progress-bar bg-success rounded" role="progressbar" style="width: {{ $rate }}%;" aria-valuenow="{{ $rate }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <!-- Dual Action Buttons -->
                    <div class="d-flex gap-2 pt-2 border-top">
                        <a href="{{ url('/seed-detail/' . $seed->id) }}" class="btn btn-outline-secondary btn-sm fw-bold rounded-pill flex-grow-1 d-flex align-items-center justify-content-center" style="font-size: 12.5px;">
                            <i class="fa-solid fa-eye me-1"></i> {{ __('view_origin') }}
                        </a>

                        <form action="{{ url('/add-to-cart/' . $seed->id) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-quick-cart btn-sm fw-bold rounded-pill px-3 d-flex align-items-center justify-content-center shadow-sm" {{ $seed->stock <= 0 ? 'disabled' : '' }} title="ដាក់ចូលកន្ត្រក">
                                <i class="fa-solid fa-cart-plus me-1"></i> កន្ត្រក
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Empty State Message -->
    <div id="no-seeds-filter-message" class="alert alert-light border rounded-4 text-center py-5 shadow-sm mt-4 d-none bg-white">
        <div class="mb-3 text-secondary">
            <i class="fa-solid fa-seedling fs-1 opacity-50"></i>
        </div>
        <h6 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">មិនមានគ្រាប់ពូជដែលត្រូវនឹងការស្វែងរកឡើយ</h6>
        <p class="text-muted small mb-3">សូមសាកល្បងផ្លាស់ប្តូរពាក្យគន្លឹះ ឬជ្រើសរើសប្រភេទបន្លែទាំងអស់ឡើងវិញ</p>
        <button type="button" class="btn btn-success btn-sm rounded-pill px-4 fw-bold" onclick="document.querySelector(`.filter-btn[data-category='all']`).click(); document.getElementById('seed-live-search').value = '';">
            <i class="fa-solid fa-rotate-left me-1"></i> បង្ហាញគ្រាប់ពូជទាំងអស់ឡើងវិញ
        </button>
    </div>
</div>

<!-- 📅 ផ្នែកទី ៥៖ ប្រតិទិនដាំដុះតាមរដូវកាល (Seasonal Planting Calendar) -->
<div class="mb-5 pt-3">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4 pb-2 border-bottom">
            <div>
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5 fw-bold mb-2">
                    <i class="fa-solid fa-calendar-days me-1"></i> ប្រតិទិនកសិកម្មជាក់ស្តែង
                </span>
                <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">
                    រដូវកាលដាំដុះដែលសមស្របនៅកម្ពុជា
                </h4>
                <p class="text-muted small mb-0">ជ្រើសរើសប្រភេទគ្រាប់ពូជឱ្យស្របតាមរដូវកាល ដើម្បីធានាបានទិន្នផលខ្ពស់បំផុត និងកាត់បន្ថយសត្វល្អិត</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Season 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="season-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 me-3">
                            <i class="fa-solid fa-cloud-showers-heavy fs-4"></i>
                        </div>
                        <div>
                            <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 small fw-bold">ឧសភា - តុលា</span>
                            <h6 class="fw-bold text-dark mb-0 mt-1" style="font-family: 'Kantumruy Pro', sans-serif;">រដូវវស្សា (Rainy Season)</h6>
                        </div>
                    </div>
                    <p class="text-muted small mb-3">ស័ក្តិសមសម្រាប់បន្លែដែលធន់នឹងទឹកភ្លៀង និងអាកាសធាតុសើមខ្លាំង។</p>
                    <ul class="list-unstyled small text-dark mb-0 d-flex flex-column gap-1.5">
                        <li><i class="fa-solid fa-check text-success me-2"></i><b>ត្រកួន</b> - ដុះលឿន ធន់ទឹកភ្លៀងខ្លាំង</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i><b>ននោង & ត្រសក់</b> - ឡើងទ្រើងបានល្អក្នុងរដូវភ្លៀង</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i><b>សណ្តែកកួរ</b> - ផ្កាច្រើន និងផ្លែវែងល្អ</li>
                    </ul>
                </div>
            </div>

            <!-- Season 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="season-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 me-3">
                            <i class="fa-solid fa-sun fs-4"></i>
                        </div>
                        <div>
                            <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 small fw-bold">វិច្ឆិកា - មេសា</span>
                            <h6 class="fw-bold text-dark mb-0 mt-1" style="font-family: 'Kantumruy Pro', sans-serif;">រដូវរំហើយ & ប្រាំង (Cool & Dry)</h6>
                        </div>
                    </div>
                    <p class="text-muted small mb-3">អាកាសធាតុត្រជាក់សមស្របបំផុតសម្រាប់បន្លែស្លឹក និងបន្លែមើមសរីរាង្គ។</p>
                    <ul class="list-unstyled small text-dark mb-0 d-flex flex-column gap-1.5">
                        <li><i class="fa-solid fa-check text-success me-2"></i><b>ស្ពៃបូកគោ & សាឡាត់</b> - ស្រួយ ផ្អែម មិនរលួយគល់</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i><b>ស្ពៃក្តោប & ផ្កាខាត់ណា</b> - រុំក្បាលណែនល្អ</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i><b>ការ៉ុត & ឆៃថាវ</b> - មើមធំត្រង់ និងស្រស់ល្អ</li>
                    </ul>
                </div>
            </div>

            <!-- Season 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="season-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 me-3">
                            <i class="fa-solid fa-house-chimney-window fs-4"></i>
                        </div>
                        <div>
                            <span class="badge bg-success text-white rounded-pill px-2.5 py-1 small fw-bold">ពេញមួយឆ្នាំ</span>
                            <h6 class="fw-bold text-dark mb-0 mt-1" style="font-family: 'Kantumruy Pro', sans-serif;">ផ្ទះសំណាញ់វៃឆ្លាត (Greenhouse)</h6>
                        </div>
                    </div>
                    <p class="text-muted small mb-3">គ្រប់គ្រងសំណើម ពន្លឺ និងការពារសត្វល្អិតបាន ១០០% ដាំបានគ្រប់រដូវ។</p>
                    <ul class="list-unstyled small text-dark mb-0 d-flex flex-column gap-1.5">
                        <li><i class="fa-solid fa-check text-success me-2"></i><b>ប៉េងប៉ោះឆឺរី</b> - ទិន្នផលខ្ពស់ គ្មានស្នាមជាំ</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i><b>ម្ទេសប្លោកផ្អែម</b> - សាច់ក្រាស់ ពណ៌ស្រស់ឆើត</li>
                        <li><i class="fa-solid fa-check text-success me-2"></i><b>សាឡាត់អ៊ីដ្រូពូនិច</b> - ប្រមូលផលរៀងរាល់ ៣០ ថ្ងៃ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 📖 ផ្នែកទី ៦៖ បច្ចេកទេស និងគន្លឹះដាំដុះ (Cultivation Insights) -->
<div id="farming-guides-section" class="mb-5 pt-3">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-4 pb-2 border-bottom">
            <div>
                <h4 class="fw-bold text-success mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">
                    <i class="fa-solid fa-book-open-reader me-2"></i>បច្ចេកទេស និងគន្លឹះដាំដុះវិទ្យាសាស្ត្រ (Cultivation Insights)
                </h4>
                <p class="text-muted small mb-0">ចំណេះដឹងកសិកម្មជាក់ស្តែង ជួយឱ្យដំណាំលូតលាស់ល្អ និងទទួលបានទិន្នផលខ្ពស់</p>
            </div>
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold" style="font-size: 12px;">
                <i class="fa-solid fa-graduation-cap me-1"></i> អនុវត្តតាមកសិកម្មឆ្លាតវៃ
            </span>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-3.5 border rounded-3 bg-light h-100 shadow-sm hover-shadow transition">
                    <div class="feature-icon mb-3 text-success fs-3">
                        <i class="fa-solid fa-seedling"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2" style="font-family: 'Kantumruy Pro', sans-serif;">១. វិធីត្រាំ និងបណ្តុះគ្រាប់ពូជ</h6>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        ត្រាំគ្រាប់ពូជក្នុងទឹកក្តៅឧណ្ហៗ (៤០°C - ទឹកក្តៅ ២ ភាគ បូក ទឹកត្រជាក់ ៣ ភាគ) រយៈពេល ២-៣ ម៉ោង មុនសាបលើថ្នាលកំប៉ុស្តិ៍ ដើម្បីភ្ញោចដំណុះបានលឿន និងស្មើល្អ។
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3.5 border rounded-3 bg-light h-100 shadow-sm hover-shadow transition">
                    <div class="feature-icon mb-3 text-warning fs-3">
                        <i class="fa-solid fa-flask"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2" style="font-family: 'Kantumruy Pro', sans-serif;">២. ការគ្រប់គ្រងកម្រិត pH ដី</h6>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        រក្សាកម្រិត pH ដីចន្លោះ ៦.០ ទៅ ៦.៨ សម្រាប់បន្លែស្ពៃ និងសាឡាត់ ដោយប្រើជីកំប៉ុស្តិ៍ធម្មជាតិ ឬកំបោរសកសិកម្ម ដើម្បីឱ្យឫសដំណាំស្រូបយកជីវជាតិបានពេញលេញ។
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3.5 border rounded-3 bg-light h-100 shadow-sm hover-shadow transition">
                    <div class="feature-icon mb-3 text-primary fs-3">
                        <i class="fa-solid fa-droplet"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-2" style="font-family: 'Kantumruy Pro', sans-serif;">៣. របបស្រោចទឹក និងពន្លឺ</h6>
                    <p class="text-muted small mb-0" style="line-height: 1.6;">
                        ស្រោចទឹករាល់ព្រឹកព្រលឹម ឬប្រើប្រព័ន្ធដំណក់ទឹក (Drip Irrigation) ដើម្បីកាត់បន្ថយសំណើមលើស្លឹកការពារជំងឺផ្សិត និងធានាឱ្យដំណាំត្រូវពន្លឺព្រះអាទិត្យ ៦ ម៉ោងក្នុងមួយថ្ងៃ។
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🏡 ផ្នែកទី ៧៖ អំពីកសិដ្ឋានដៃគូ & TRACEABILITY SPOTLIGHT -->
<div id="about-farm-section" class="mb-5">
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 text-white" style="background: linear-gradient(135deg, #198754, #14532d);">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge bg-white text-success rounded-pill px-3 py-1.5 fw-bold mb-3" style="font-size: 12px;">
                    <i class="fa-solid fa-tractor me-1"></i> កសិដ្ឋានតាក្រាម ខេត្តបាត់ដំបង
                </span>
                <h3 class="fw-bold mb-3" style="font-family: 'Kantumruy Pro', sans-serif;">អំពីសហគមន៍កសិកម្ម និងប្រភពគ្រាប់ពូជ</h3>
                <p class="opacity-90 small mb-4" style="line-height: 1.7; font-size: 14px;">
                    វេទិកា <b>Grow2Growth</b> សហការយ៉ាងជិតស្និទ្ធជាមួយកសិករដាំដុះគ្រាប់ពូជក្នុងឃុំតាក្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង។ រាល់គ្រាប់ពូជបន្លែទាំងអស់ ត្រូវបានជ្រើសរើសដោយដៃ ធ្វើតេស្តអត្រាដំណុះក្នុងមន្ទីរពិសោធន៍កសិកម្ម និងចុះបញ្ជីលេខកូដបាច់ (Batch QR) ជាក់លាក់ ដើម្បីធានាបាននូវគុណភាពខ្ពស់បំផុត និងគ្មានសារធាតុគីមីហាមឃាត់ឡើយ។
                </p>
                <div class="d-flex flex-wrap gap-4 pt-2 border-top border-white border-opacity-25">
                    <div>
                        <h4 class="fw-bold mb-0 font-monospace text-warning">៩៥% - ៩៨%</h4>
                        <small class="opacity-75" style="font-size: 11.5px;">អត្រាដុះលូតលាស់ពីមន្ទីរពិសោធន៍</small>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 font-monospace text-warning">១០០%</h4>
                        <small class="opacity-75" style="font-size: 11.5px;">អាចស្កេនតាមដានប្រភពដើម</small>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 font-monospace text-warning">សុទ្ធសាធ</h4>
                        <small class="opacity-75" style="font-size: 11.5px;">គ្មានជំងឺផ្សិតឆ្លងគ្រាប់ពូជ</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="bg-white p-4 rounded-4 shadow-lg text-dark">
                    <div class="text-success mb-2 fs-1">
                        <i class="fa-solid fa-certificate"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">ស្តង់ដារគុណភាពគ្រាប់ពូជ</h5>
                    <p class="text-muted small mb-3">មានបញ្ជាក់លេខកូដ Traceability ត្រឹមត្រូវ</p>
                    <a href="{{ url('/traceability') }}" class="btn btn-success fw-bold rounded-pill w-100 py-2.5 shadow-sm">
                        <i class="fa-solid fa-qrcode me-1"></i> ស្កេនតាមដានលេខបាច់ឥឡូវនេះ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 💬 ផ្នែកទី ៨៖ មតិកសិករ និងដៃគូដាំដុះ (Farmer Testimonials) -->
<div class="mb-5 pt-2">
    <div class="text-center mb-4">
        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5 fw-bold mb-2">
            <i class="fa-solid fa-comments me-1"></i> សម្លេងពិតពីកសិករ
        </span>
        <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">ទំនុកចិត្តពីកសិករទូទាំងប្រទេស</h4>
        <p class="text-muted small mb-0">បទពិសោធន៍ផ្ទាល់ពីបងប្អូនកសិករដែលបានប្រើប្រាស់គ្រាប់ពូជ Grow2Growth</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="testimonial-card">
                <div class="text-warning mb-2">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <p class="text-muted small mb-3" style="line-height: 1.7;">
                    "ខ្ញុំបានទិញគ្រាប់ពូជស្ពៃបូកគោ និងសាឡាត់យកទៅដាំលើផ្ទៃដីកន្លះហិកតា អត្រាដុះស្ទើរតែ ១០០% ដើមថ្លោសល្អ និងមិនងាយរលួយគល់ពេលភ្លៀងធ្លាក់ខ្លាំង។"
                </p>
                <div class="d-flex align-items-center border-top pt-3">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold shadow-sm" style="width: 40px; height: 40px;">
                        ស
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0" style="font-family: 'Kantumruy Pro', sans-serif;">លោកពូ សុខា</h6>
                        <small class="text-muted">កសិករដាំបន្លែ ស្រុកបាណន់</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="testimonial-card">
                <div class="text-warning mb-2">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <p class="text-muted small mb-3" style="line-height: 1.7;">
                    "ប្រព័ន្ធ QR Code ពិតជាមានប្រយោជន៍ខ្លាំង គ្រាន់តែស្កេនដឹងថ្ងៃផុតកំណត់ និងប្រភពច្បាស់ៗ ជួយឱ្យយើងទុកចិត្តមុនពេលយកទៅសាបលើថ្នាល។"
                </p>
                <div class="d-flex align-items-center border-top pt-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold shadow-sm" style="width: 40px; height: 40px;">
                        ច
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0" style="font-family: 'Kantumruy Pro', sans-serif;">អ្នកមីង ចិន្តា</h6>
                        <small class="text-muted">សហគមន៍កសិកម្ម ខេត្តបាត់ដំបង</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="testimonial-card">
                <div class="text-warning mb-2">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <p class="text-muted small mb-3" style="line-height: 1.7;">
                    "ការវេចខ្ចប់ការពារសំណើមបានយ៉ាងល្អ ការដឹកជញ្ជូនរហ័សទាន់ចិត្តមកដល់ភ្នំពេញតែ ១ ថ្ងៃប៉ុណ្ណោះ។ ខ្ញុំនឹងបន្តគាំទ្រគ្រាប់ពូជ Grow2Growth រហូត។"
                </p>
                <div class="d-flex align-items-center border-top pt-3">
                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold shadow-sm" style="width: 40px; height: 40px;">
                        វ
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-0" style="font-family: 'Kantumruy Pro', sans-serif;">លោក វិបុល</h6>
                        <small class="text-muted">ម្ចាស់កសិដ្ឋានបន្លែបៃតង</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ❓ ផ្នែកទី ៩៖ សំណួរដែលសួរញឹកញាប់ (FAQ Accordion) -->
<div class="mb-5 pt-2">
    <div class="text-center mb-4">
        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5 fw-bold mb-2">
            <i class="fa-solid fa-circle-question me-1"></i> ចម្លើយរហ័ស
        </span>
        <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">សំណួរដែលសួរញឹកញាប់ (FAQs)</h4>
        <p class="text-muted small mb-0">ចម្ងល់ទូទៅអំពីការទិញ និងតាមដានគ្រាប់ពូជកសិកម្ម</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="accordion accordion-agro" id="faqAgroAccordion">
                <div class="accordion-item shadow-sm">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <i class="fa-solid fa-qrcode text-success me-2"></i> តើខ្ញុំអាចដឹងពីគុណភាព និងអត្រាដុះលូតលាស់នៃគ្រាប់ពូជដោយរបៀបណា?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAgroAccordion">
                        <div class="accordion-body text-muted small" style="line-height: 1.8;">
                            លោកអ្នកអាចប្រើកាមេរ៉ាទូរស័ព្ទស្កេន QR Code លើកញ្ចប់ ឬវាយលេខ Batch Number លើគេហទំព័រនេះ។ ប្រព័ន្ធនឹងបង្ហាញប្រភពកសិដ្ឋាន កាលបរិច្ឆេទប្រមូលផល អត្រាដុះលូតលាស់ជាក់ស្តែង (Germination Rate) និងកាលបរិច្ឆេទផុតកំណត់។
                        </div>
                    </div>
                </div>

                <div class="accordion-item shadow-sm">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <i class="fa-solid fa-truck-fast text-success me-2"></i> តើសេវាដឹកជញ្ជូនចំណាយពេលប៉ុន្មានថ្ងៃទើបដល់កសិដ្ឋាន?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAgroAccordion">
                        <div class="accordion-body text-muted small" style="line-height: 1.8;">
                            សម្រាប់ការបញ្ជាទិញក្នុងរាជធានីភ្នំពេញ និងខេត្តបាត់ដំបង ចំណាយពេលត្រឹមតែ ២៤ ម៉ោង។ សម្រាប់បណ្តាខេត្តដទៃទៀត ទំនិញនឹងដឹកជញ្ជូនដល់ដៃលោកអ្នកក្នុងរយៈពេល ១ ទៅ ២ ថ្ងៃ តាមរយៈសេវាដឹកជញ្ជូនដៃគូឈានមុខ។
                        </div>
                    </div>
                </div>

                <div class="accordion-item shadow-sm">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <i class="fa-solid fa-rotate text-success me-2"></i> តើមានគោលការណ៍ធានាប្តូរគ្រាប់ពូជដែរឬទេ ប្រសិនបើមានបញ្ហា?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAgroAccordion">
                        <div class="accordion-body text-muted small" style="line-height: 1.8;">
                            Grow2Growth ធានាអត្រាដំណុះមិនតិចជាង ៩០% ឡើយ តាមការណែនាំបច្ចេកទេស។ ប្រសិនបើគ្រាប់ពូជមិនដុះតាមស្តង់ដារ ឬខូចខាតអំឡុងពេលដឹកជញ្ជូន យើងខ្ញុំនឹងប្តូរជូនកញ្ចប់ថ្មី ឬសងប្រាក់វិញភ្លាមៗ។
                        </div>
                    </div>
                </div>

                <div class="accordion-item shadow-sm">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                            <i class="fa-solid fa-headset text-success me-2"></i> តើខ្ញុំអាចទទួលបានការប្រឹក្សាបច្ចេកទេសដាំដុះបន្ថែមដោយឥតគិតថ្លៃដោយរបៀបណា?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAgroAccordion">
                        <div class="accordion-body text-muted small" style="line-height: 1.8;">
                            លោកអ្នកអាចចុចលើប៊ូតុង "ជំនួយកសិករ" នៅលើរបារ Navbar ឬផ្ញើសារមកកាន់ Telegram Channel របស់យើង។ អ្នកជំនាញកសិកម្មនឹងឆ្លើយតប និងជួយណែនាំបច្ចេកទេសដាំដុះដោយមិនគិតថ្លៃសេវាឡើយ។
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🚀 ផ្នែកទី ១០៖ CALL TO ACTION (CTA BANNER) -->
<div class="cta-banner p-4 p-md-5 text-white mb-4 shadow">
    <div class="row align-items-center g-4 position-relative" style="z-index: 2;">
        <div class="col-lg-8">
            <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold mb-2">
                <i class="fa-solid fa-bolt me-1"></i> ត្រៀមរដូវកាលដាំដុះថ្មី
            </span>
            <h3 class="fw-bold mb-2" style="font-family: 'Kantumruy Pro', sans-serif;">
                ចាប់ផ្តើមរដូវកាលដាំដុះជាមួយគ្រាប់ពូជដែលទុកចិត្តបាន!
            </h3>
            <p class="opacity-90 small mb-0" style="line-height: 1.7; max-width: 600px;">
                កុំប្រថុយជាមួយគ្រាប់ពូជគ្មានប្រភពច្បាស់លាស់។ ជ្រើសរើស Grow2Growth ដើម្បីធានាទិន្នផល និងប្រាក់ចំណេញខ្ពស់ជូនគ្រួសារលោកអ្នក។
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <a href="#featured-seeds-section" class="btn btn-warning fw-bold rounded-pill px-4 py-2.5 shadow-sm me-2 text-dark">
                <i class="fa-solid fa-basket-shopping me-1"></i> ទិញគ្រាប់ពូជឥឡូវនេះ
            </a>
            <a href="https://t.me/Hor_Tola12" target="_blank" class="btn btn-outline-light fw-bold rounded-pill px-3 py-2.5 mt-2 mt-sm-0">
                <i class="fa-brands fa-telegram me-1"></i> ទាក់ទង Telegram
            </a>
        </div>
    </div>
    <div class="position-absolute end-0 top-0 opacity-10 text-white d-none d-md-block" style="font-size: 200px; transform: translate(30px, -50px);">
        <i class="fa-solid fa-wheat-awn"></i>
    </div>
</div>

<!-- 💻 CLIENT-SIDE FILTER & REAL-TIME SEARCH SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const seedItems = Array.from(document.querySelectorAll('.seed-item'));
    const liveSearchInput = document.getElementById('seed-live-search');
    const sortSelect = document.getElementById('seed-sort-select');
    const noSeedsMsg = document.getElementById('no-seeds-filter-message');
    const visibleCountBadge = document.getElementById('visible-seeds-count');
    const resetFilterBtn = document.getElementById('reset-filter-btn');
    const gridContainer = document.getElementById('seed-grid-container');

    let currentCategory = 'all';

    function applyFiltersAndSort() {
        const query = liveSearchInput.value.toLowerCase().trim();
        let visibleItems = [];

        // 1. Filter by Category & Search Query
        seedItems.forEach(item => {
            const itemCategory = item.getAttribute('data-category-id');
            const itemName = item.getAttribute('data-name');

            const matchesCategory = (currentCategory === 'all' || itemCategory === currentCategory);
            const matchesQuery = (!query || itemName.includes(query));

            if (matchesCategory && matchesQuery) {
                item.classList.remove('d-none');
                visibleItems.push(item);
            } else {
                item.classList.add('d-none');
            }
        });

        // 2. Sort visible items
        const sortBy = sortSelect.value;
        if (sortBy === 'price-asc') {
            visibleItems.sort((a, b) => parseFloat(a.dataset.price) - parseFloat(b.dataset.price));
        } else if (sortBy === 'price-desc') {
            visibleItems.sort((a, b) => parseFloat(b.dataset.price) - parseFloat(a.dataset.price));
        } else if (sortBy === 'rate-desc') {
            visibleItems.sort((a, b) => parseFloat(b.dataset.rate) - parseFloat(a.dataset.rate));
        }

        // Re-append sorted elements in order
        visibleItems.forEach(el => gridContainer.appendChild(el));

        // 3. Update count & empty state
        if (visibleCountBadge) {
            visibleCountBadge.textContent = visibleItems.length;
        }

        if (visibleItems.length === 0) {
            noSeedsMsg.classList.remove('d-none');
        } else {
            noSeedsMsg.classList.add('d-none');
        }

        // Show/hide reset button
        if (currentCategory !== 'all' || query !== '') {
            resetFilterBtn.classList.remove('d-none');
        } else {
            resetFilterBtn.classList.add('d-none');
        }
    }

    // Category button clicks
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentCategory = this.getAttribute('data-category');
            applyFiltersAndSort();
        });
    });

    // Real-time live search input
    liveSearchInput.addEventListener('input', function() {
        applyFiltersAndSort();
    });

    // Sort selection change
    sortSelect.addEventListener('change', function() {
        applyFiltersAndSort();
    });

    // Reset filters
    resetFilterBtn.addEventListener('click', function() {
        currentCategory = 'all';
        liveSearchInput.value = '';
        sortSelect.value = 'default';
        filterButtons.forEach(b => b.classList.remove('active'));
        const allBtn = document.querySelector(`.filter-btn[data-category='all']`);
        if (allBtn) allBtn.classList.add('active');
        applyFiltersAndSort();
    });
});
</script>

@endsection
