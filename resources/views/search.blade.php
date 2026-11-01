@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/home-style.css') }}">

@section('content')
    <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <a href="{{ url('/') }}" class="btn btn-light btn-sm border fw-bold rounded-pill px-3 shadow-sm text-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> {{ __('menu_home') ?? 'ត្រលប់ទៅទំព័រដើម' }}
        </a>
        <span class="text-muted small">
            រកឃើញចំនួន <strong class="text-success">{{ $seeds->count() }}</strong> មុខ
        </span>
    </div>

    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">
                    @if(isset($selectedCategory))
                        <i class="fa-solid fa-leaf text-success me-2"></i>ប្រភេទបន្លែ៖ <span class="text-success">{{ $selectedCategory->name_kh }} ({{ $selectedCategory->name_en }})</span>
                        @if($query) <small class="text-muted fs-6">/ ស្វែងរក "{{ $query }}"</small> @endif
                    @elseif($query)
                        <i class="fa-solid fa-magnifying-glass text-success me-2"></i>លទ្ធផលស្វែងរកសម្រាប់៖ <span class="text-success">"{{ $query }}"</span>
                    @else
                        <i class="fa-solid fa-border-all text-success me-2"></i>គ្រាប់ពូជទាំងអស់
                    @endif
                </h4>
                <p class="text-muted small mb-0">គ្រាប់ពូជសុទ្ធ ១០០% ធានាគុណភាពពីសហគមន៍កសិកម្មខេត្តបាត់ដំបង</p>
            </div>
            <div>
                <!-- Category switch shortcuts -->
                @if(isset($navCategories))
                    <div class="d-flex flex-wrap gap-1.5">
                        <a href="{{ url('/search') }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ !request('category_id') ? 'btn-success' : 'btn-light border' }}" style="font-size: 12px;">
                            ទាំងអស់
                        </a>
                        @foreach($navCategories as $cat)
                            <a href="{{ url('/search?category_id=' . $cat->id) }}" class="btn btn-sm rounded-pill px-3 fw-bold {{ request('category_id') == $cat->id ? 'btn-success' : 'btn-light border' }}" style="font-size: 12px;">
                                {{ App::isLocale('en') ? $cat->name_en : $cat->name_kh }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($seeds->isEmpty())
        <div class="alert alert-light text-center py-5 rounded-4 shadow-sm bg-white border mb-5">
            <div class="p-4 bg-warning bg-opacity-10 rounded-circle d-inline-flex mb-3 text-warning">
                <i class="fa-solid fa-triangle-exclamation display-5"></i>
            </div>
            <h5 class="fw-bold text-dark mb-2" style="font-family: 'Kantumruy Pro', sans-serif;">មិនមានទិន្នន័យគ្រាប់ពូជដែលអ្នកកំពុងស្វែងរកឡើយ!</h5>
            <p class="text-muted small mb-3">សូមសាកល្បងផ្លាស់ប្តូរពាក្យគន្លឹះ ឬជ្រើសរើសប្រភេទបន្លែផ្សេងទៀត</p>
            <a href="{{ url('/') }}" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> មើលគ្រាប់ពូជទាំងអស់លើទំព័រដើម
            </a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
            @foreach($seeds as $seed)
            @php
                $rate = $seed->traceability ? $seed->traceability->germination_rate : 90.00;
                $rielPrice = number_format($seed->price * 4100);
            @endphp
            <div class="col">
                <div class="card seed-card h-100 shadow-sm border-0 position-relative">
                    <div class="seed-image-wrapper">
                        @if($seed->image)
                            <img src="{{ asset('uploads/seeds/' . $seed->image) }}" class="card-img-top seed-image w-100" alt="{{ $seed->name_kh }}">
                        @else
                            <div class="seed-placeholder-img w-100">
                                <i class="fa-solid fa-seedling fs-1 mb-2 opacity-50"></i>
                                <span class="small fw-bold">Grow2Growth Seed</span>
                            </div>
                        @endif

                        @if($seed->batch_number)
                            <a href="{{ url('/trace?batch_number=' . urlencode($seed->batch_number)) }}"
                               class="batch-badge-pill position-absolute top-0 start-0 m-2.5 z-2"
                               title="ស្កេនពិនិត្យប្រភព">
                                <i class="fa-solid fa-qrcode text-warning me-1"></i>{{ $seed->batch_number }}
                            </a>
                        @endif

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
                        <div class="mb-2">
                            <span class="category-badge d-inline-block">
                                {{ App::isLocale('en') ? ($seed->category?->name_en ?? 'Seed') : ($seed->category?->name_kh ?? 'គ្រាប់ពូជ') }}
                            </span>
                        </div>

                        <h6 class="card-title fw-bold mb-1 text-dark text-truncate" title="{{ $seed->name_kh }}" style="font-family: 'Kantumruy Pro', sans-serif;">
                            {{ App::isLocale('en') ? $seed->name_en : $seed->name_kh }}
                        </h6>
                        <small class="text-muted mb-2 d-block text-truncate" style="font-size: 11.5px;">
                            {{ App::isLocale('en') ? $seed->name_kh : $seed->name_en }}
                        </small>

                        <div class="mb-2">
                            <span class="price-tag">${{ number_format($seed->price, 2) }}</span>
                            <span class="text-muted small">({{ $rielPrice }} ៛) {{ __('per_pack') ?? 'កញ្ចប់' }}</span>
                        </div>

                        <div class="mb-3 mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-1" style="font-size: 11px;">
                                <span class="text-muted fw-bold">
                                    <i class="fa-solid fa-seedling text-success me-1"></i>
                                    {{ __('growth_potential') ?? 'អត្រាដំណុះ' }}
                                </span>
                                <span class="text-success fw-bold">{{ number_format($rate, 1) }}%</span>
                            </div>
                            <div class="progress" style="height: 6px; background-color: #e9ecef;">
                                <div class="progress-bar bg-success rounded" role="progressbar" style="width: {{ $rate }}%;"></div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 pt-2 border-top">
                            <a href="{{ url('/seed-detail/' . $seed->id) }}" class="btn btn-outline-secondary btn-sm fw-bold rounded-pill flex-grow-1 d-flex align-items-center justify-content-center" style="font-size: 12.5px;">
                                <i class="fa-solid fa-eye me-1"></i> {{ __('view_origin') ?? 'មើលប្រភព' }}
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
    @endif
@endsection
