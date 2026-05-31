@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/home-style.css') }}">

@section('content')

@auth
    @if(Auth::user()->role === 'admin')
        <div class="alert alert-dark border-0 shadow-sm rounded-3 d-flex justify-content-between align-items-center p-3 mb-4" style="background-color: #212529;">
            <div class="text-white small">
                <i class="fa-solid fa-user-shield text-warning me-2"></i>អ្នកកំពុងចូលប្រើប្រាស់ជា៖ <b>អ្នកគ្រប់គ្រង (Admin Mode)</b>
            </div>
            <a href="{{ url('/admin/dashboard') }}" class="btn btn-warning btn-sm fw-bold rounded-pill px-3">
                <i class="fa-solid fa-chart-pie me-1"></i> ចូលទៅផ្ទាំង Admin Dashboard
            </a>
        </div>
    @endif
@endauth

<div class="hero-box p-5 mb-5 shadow-sm border-start border-success border-5 position-relative overflow-hidden">
    <div class="container-fluid py-2 position-relative" style="z-index: 2;">
        <h1 class="display-6 fw-bold text-success mb-3">
            {{ __('banner_title') }}
        </h1>
        <p class="col-md-8 fs-6 text-muted mb-4">
            {{ __('banner_desc') }}
        </p>

        <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
            <div>
                <a href="{{ url('/cart') }}" class="btn btn-success btn-lg fw-bold rounded-pill px-4 shadow-sm d-inline-flex align-items-center border-2 border-success text-decoration-none" style="height: 48px; font-size: 16px;">
                    <span class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 26px; height: 26px;">
                        <i class="fa-solid fa-basket-shopping" style="font-size: 13px;"></i>
                    </span>
                    <span>{{ __('shop_now') }}</span>
                </a>
            </div>

            <div class="w-100" style="max-width: 530px;">
                <form action="{{ url('/trace-batch') }}" method="GET" class="m-0">
                    <div class="input-group search-wrapper shadow-sm rounded-pill overflow-hidden border border-success bg-white p-1" style="height: 48px;">
                        <span class="input-group-text bg-white border-0 text-success ps-3">
                            <i class="fa-solid fa-qrcode"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-0 bg-white search-input" placeholder="វាយលេខ Batch តាមដាន... (ឧ. BATCH-2026-LT03)" required>
                        <button class="btn btn-success px-3 fw-bold rounded-pill d-flex align-items-center search-btn" type="submit" style="font-size: 14px;">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> តាមដាន
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="position-absolute end-0 bottom-0 opacity-10 text-success d-none d-md-block" style="font-size: 140px; transform: translate(10px, 20px); z-index: 1;">
        <i class="fa-solid fa-leaf"></i>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="feature-card p-4 shadow-sm h-100 d-flex align-items-start">
            <div class="feature-icon me-3">
                <i class="fa-solid fa-seedling fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-1">គ្រាប់ពូជសុទ្ធ ១០០%</h6>
                <p class="text-muted small mb-0">គ្រាប់ពូជមានគុណភាពខ្ពស់ ឆ្លងកាត់ការពិសោធន៍ អត្រាដុះលូតលាស់ខ្លាំង។</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="feature-card p-4 shadow-sm h-100 d-flex align-items-start">
            <div class="feature-icon me-3">
                <i class="fa-solid fa-shield-halved fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-1">តាមដានប្រភពច្បាស់លាស់</h6>
                <p class="text-muted small mb-0">រាល់គ្រាប់ពូជទាំងអស់ អាចស្កេនពិនិត្យមើលកសិដ្ឋានដើម និងថ្ងៃផលិតបាន ១០០%។</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="feature-card p-4 shadow-sm h-100 d-flex align-items-start">
            <div class="feature-icon me-3">
                <i class="fa-solid fa-truck-fast fs-4"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-1">ដឹកជញ្ជូនរហ័សទាន់ចិត្ត</h6>
                <p class="text-muted small mb-0">សេវាកម្មវេចខ្ចប់មានសុវត្ថិភាពខ្ពស់ និងដឹកជញ្ជូនលឿនរហ័សជូនបងប្អូនកសិករ។</p>
            </div>
        </div>
    </div>
</div>

<div class="mb-5 p-4 bg-white rounded-4 shadow-sm">
    <div class="text-center mb-4">
        <h5 class="fw-bold text-success">
            <i class="fa-solid fa-route me-2"></i>របៀបដែលប្រព័ន្ធដំណើរការ
        </h5>
        <p class="text-muted small">ត្រឹមតែ ៣ ជំហានងាយៗ ក្នុងការស្វែងរក និងតាមដានប្រភពគ្រាប់ពូជកសិកម្មវៃឆ្លាត</p>
    </div>

    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="step-box">
                <div class="step-circle mb-3 mx-auto d-flex align-items-center justify-content-center text-success rounded-circle shadow-sm">
                    <span class="fs-4 fw-bold">1</span>
                </div>
                <h6 class="fw-bold text-dark">1. ជ្រើសរើសគ្រាប់ពូជ</h6>
                <p class="text-muted small px-3">ជ្រើសរើសប្រភេទគ្រាប់ពូជបន្លែដែលលោកអ្នកចង់ដាំដុះ រួចមើលព័ត៌មានលម្អិត។</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="step-box">
                <div class="step-circle mb-3 mx-auto d-flex align-items-center justify-content-center text-success rounded-circle shadow-sm">
                    <span class="fs-4 fw-bold">2</span>
                </div>
                <h6 class="fw-bold text-dark">2. ពិនិត្យប្រភពដើម</h6>
                <p class="text-muted small px-3">វាយបញ្ចូលលេខ Batch ឬស្កេនដើម្បីមើលកសិដ្ឋានផលិត និងអត្រាលូតលាស់ពិតប្រាកដ។</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="step-box">
                <div class="step-circle mb-3 mx-auto d-flex align-items-center justify-content-center text-success rounded-circle shadow-sm">
                    <span class="fs-4 fw-bold">3</span>
                </div>
                <h6 class="fw-bold text-dark">3. បញ្ជាទិញដោយទំនុកចិត្ត</h6>
                <p class="text-muted small px-3">ដាក់ចូលកន្ត្រកទំនិញ បំពេញព័ត៌មានដឹកជញ្ជូន រួចរង់ចាំទទួលគ្រាប់ពូជដល់ទីកន្លែង។</p>
            </div>
        </div>
    </div>
</div>

<h4 id="featured-seeds-section" class="section-title mb-5 fw-bold text-success d-flex align-items-center">
    <span class="p-2 bg-success bg-opacity-10 rounded-3 me-3 d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
        <i class="fa-solid fa-wheat-awn"></i>
    </span>
    <span>{{ __('featured_seeds') }}</span>
</h4>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
    @foreach($seeds as $seed)
    <div class="col">
        <div class="card seed-card h-100 shadow-sm border-0">
            @if($seed->image)
                <img src="{{ asset('uploads/seeds/' . $seed->image) }}" class="card-img-top seed-image" alt="Seed Image">
            @else
                <img src="https://via.placeholder.com/400x300" class="card-img-top seed-image" alt="Seed Image">
            @endif

            <div class="card-body p-3">
                <span class="category-badge mb-2 d-inline-block">
                    @if(App::isLocale('en'))
                        {{ $seed->category?->name_en ?? 'Seed' }}
                    @else
                        {{ $seed->category?->name_kh ?? 'គ្រាប់ពូជ' }}
                    @endif
                </span>

                <h6 class="card-title fw-bold mb-1 text-dark">
                    {{ App::isLocale('en') ? $seed->name_en : $seed->name_kh }}
                </h6>

                <p class="price-tag mb-2">
                    ${{ number_format($seed->price, 2) }}
                    <span class="fs-6 text-muted">{{ __('per_pack') }}</span>
                </p>

                <div class="mb-3">
                    @php
                        // 🟢 កែសម្រួល៖ ប្តូរមកចាប់យកតាមរយៈ Relationship One-to-One 'traceability' វិញ
                        // ដើម្បីឱ្យបង្ហាញតម្លៃអត្រាលូតលាស់ពិតប្រាកដចេញពី Database (ដូចជា ៩៥% ឬ ៩៨%) លែងជាប់គាំងត្រឹម ៩០% គ្រប់ផលិតផល
                        $rate = $seed->traceability ? $seed->traceability->germination_rate : 90.00;
                    @endphp

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

                <small class="text-muted d-block mb-3" style="font-size: 13px;">
                    <i class="fa-solid fa-circle-check text-success me-1"></i>
                    {{ __('stock') }} <b>{{ $seed->stock }}</b>
                </small>

                <div class="d-grid gap-2">
                    <a href="{{ url('/seed-detail/' . $seed->id) }}" class="btn btn-success btn-sm fw-bold rounded-pill shadow-sm seed-btn">
                        <i class="fa-solid fa-eye me-1"></i>
                        {{ __('view_origin') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection
