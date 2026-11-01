@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/home-style.css') }}">
<link rel="stylesheet" href="{{ asset('css/cart-style.css') }}">

@section('content')
<div class="container py-4 text-dark">
    <!-- Breadcrumb & Back -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <a href="{{ url('/') }}" class="btn btn-light btn-sm fw-bold rounded-pill px-3 border shadow-sm text-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> {{ __('menu_home') ?? 'ទំព័រដើម' }}
        </a>
        @if(isset($seeds) && $seeds->count() > 0)
            <form action="{{ route('wishlist.clear') }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់សម្អាតបញ្ជីគ្រាប់ពូជពេញចិត្តទាំងអស់មែនទេ?')" class="m-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                    <i class="fa-solid fa-trash-can me-1.5"></i> សម្អាតបញ្ជីទាំងអស់
                </button>
            </form>
        @endif
    </div>

    <!-- Page Header -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-success mb-1 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                <span class="rounded-circle bg-danger bg-opacity-10 text-danger p-2 me-2 d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-heart"></i>
                </span>
                <span>គ្រាប់ពូជពេញចិត្តរបស់អ្នក (Saved Seeds)</span>
            </h4>
            <p class="text-muted small mb-0">បញ្ជីគ្រាប់ពូជកសិកម្មគុណភាពខ្ពស់ដែលអ្នកបានរក្សាទុកសម្រាប់រដូវដាំដុះ</p>
        </div>

        @if(isset($seeds) && $seeds->count() > 0)
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2 rounded-pill font-monospace" style="font-size: 13px;">
                    <i class="fa-solid fa-seedling me-1"></i> សរុប {{ $seeds->count() }} មុខ
                </span>
            </div>
        @endif
    </div>

    <!-- Flash Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2 fs-5 align-middle text-success"></i>
            <span class="fw-bold">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('wishlist_status'))
        <div class="alert alert-info alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-heart text-danger me-2 fs-5 align-middle"></i>
            <span class="fw-bold">{{ session('wishlist_status') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2 fs-5 align-middle text-danger"></i>
            <span class="fw-bold">{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Content: Grid of Saved Seeds or Empty State -->
    @if(isset($seeds) && $seeds->count() > 0)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 mb-5">
            @foreach($seeds as $seed)
                @php
                    $rate = $seed->traceability ? $seed->traceability->germination_rate : 90.00;
                    $rielPrice = number_format($seed->price * 4100);
                @endphp
                <div class="col" id="wishlist-col-{{ $seed->id }}">
                    <div class="card seed-card h-100 shadow-sm border-0 position-relative rounded-4 overflow-hidden bg-white">
                        <!-- Image Wrapper -->
                        <div class="seed-image-wrapper position-relative">
                            @if($seed->image)
                                <img src="{{ asset('uploads/seeds/' . $seed->image) }}" class="card-img-top seed-image w-100" alt="{{ $seed->name_kh }}" style="height: 180px; object-fit: cover;">
                            @else
                                <div class="seed-placeholder-img w-100 d-flex flex-column align-items-center justify-content-center bg-light text-muted" style="height: 180px;">
                                    <i class="fa-solid fa-seedling fs-1 mb-2 opacity-50 text-success"></i>
                                    <span class="small fw-bold">Grow2Growth Seed</span>
                                </div>
                            @endif

                            <!-- Batch Tag -->
                            @if($seed->batch_number)
                                <span class="batch-badge-pill position-absolute top-0 start-0 m-2.5 z-2" style="font-size: 11px;">
                                    <i class="fa-solid fa-qrcode text-warning me-1"></i>{{ $seed->batch_number }}
                                </span>
                            @endif

                            <!-- Remove from Wishlist Form -->
                            <form action="{{ route('wishlist.toggle', $seed->id) }}" method="POST" class="position-absolute top-0 end-0 m-2.5 z-2">
                                @csrf
                                <button type="submit" class="btn btn-light rounded-circle shadow-sm border-0 d-flex align-items-center justify-content-center text-danger" style="width: 32px; height: 32px; background: rgba(255,255,255,0.95);" title="ដកចេញពីបញ្ជីពេញចិត្ត">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Card Body -->
                        <div class="card-body p-3 d-flex flex-column">
                            <div class="mb-1">
                                <span class="category-badge d-inline-block">
                                    {{ App::isLocale('en') ? ($seed->category?->name_en ?? 'Seed') : ($seed->category?->name_kh ?? 'គ្រាប់ពូជ') }}
                                </span>
                            </div>

                            <h6 class="card-title fw-bold mb-1 text-dark text-truncate" title="{{ $seed->name_kh }}" style="font-family: 'Kantumruy Pro', sans-serif;">
                                <a href="{{ url('/seed-detail/' . $seed->id) }}" class="text-dark text-decoration-none hover-success">
                                    {{ App::isLocale('en') ? $seed->name_en : $seed->name_kh }}
                                </a>
                            </h6>

                            <!-- Rating -->
                            <div class="d-flex align-items-center gap-1 mb-2">
                                <div class="text-warning small" style="font-size: 11px;">
                                    @php $seedAvg = $seed->averageRating(); @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= floor($seedAvg) ? '' : 'text-muted opacity-25' }}"></i>
                                    @endfor
                                </div>
                                <span class="fw-bold text-dark font-monospace" style="font-size: 11px;">{{ number_format($seedAvg, 1) }}</span>
                                <span class="text-muted" style="font-size: 10px;">({{ $seed->reviews->count() }})</span>
                            </div>

                            <!-- Price -->
                            <div class="mb-2">
                                <span class="price-tag fw-bold text-success fs-5">${{ number_format($seed->price, 2) }}</span>
                                <span class="text-muted small">({{ $rielPrice }} ៛)</span>
                            </div>

                            <!-- Stock & Germination info -->
                            <div class="d-flex justify-content-between align-items-center mb-3 mt-auto small">
                                <span class="text-muted">
                                    <i class="fa-solid fa-seedling text-success me-1"></i>ដំណុះ: <strong>{{ number_format($rate, 1) }}%</strong>
                                </span>
                                @if($seed->stock > 0)
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold rounded-pill">
                                        ស្តុក {{ $seed->stock }}
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger fw-bold rounded-pill">
                                        អស់ស្តុក
                                    </span>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2 pt-2 border-top">
                                @if($seed->stock > 0)
                                    <form action="{{ route('wishlist.move_to_cart', $seed->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm fw-bold rounded-pill w-100 shadow-sm py-2">
                                            <i class="fa-solid fa-cart-plus me-1.5"></i> បញ្ចូលក្នុងកន្ត្រក
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary btn-sm fw-bold rounded-pill w-100 py-2" disabled>
                                        <i class="fa-solid fa-ban me-1.5"></i> អស់ពីស្តុក
                                    </button>
                                @endif
                                <a href="{{ url('/seed-detail/' . $seed->id) }}" class="btn btn-light btn-sm fw-bold rounded-pill border text-muted py-1.5">
                                    ពិនិត្យព័ត៌មានលម្អិត
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white my-4">
            <div class="py-4">
                <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fa-regular fa-heart fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2" style="font-family: 'Kantumruy Pro', sans-serif;">
                    មិនទាន់មានគ្រាប់ពូជក្នុងបញ្ជីពេញចិត្តនៅឡើយទេ
                </h5>
                <p class="text-muted small mx-auto mb-4" style="max-width: 460px; line-height: 1.6;">
                    សូមចុចលើរូបបេះដូង ❤️ លើគ្រាប់ពូជបន្លែណាដែលអ្នកចាប់អារម្មណ៍ ដើម្បីចំណាំទុកងាយស្រួលរកមើល និងបញ្ជាទិញនៅពេលក្រោយ។
                </p>
                <a href="{{ url('/') }}" class="btn btn-success fw-bold rounded-pill px-4 py-2.5 shadow-sm">
                    <i class="fa-solid fa-seedling me-2"></i> ស្វែងរកគ្រាប់ពូជកសិកម្មឥឡូវនេះ
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

