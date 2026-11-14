@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/home-style.css') }}">
<link rel="stylesheet" href="{{ asset('css/detail-style.css') }}">

@section('content')
<div class="container py-4 text-dark">
    <a href="{{ url('/') }}" class="btn btn-light btn-sm mb-4 fw-bold rounded-pill px-3 border shadow-sm text-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> {{ __('menu_home') }}
    </a>

    <div class="row g-5 mb-5">
        <div class="col-md-5">
            <div class="position-relative">
                @if($seed->image)
                    <img src="{{ asset('uploads/seeds/' . $seed->image) }}"
                         class="img-fluid detail-seed-img shadow-sm w-100 rounded-4 border"
                         alt="{{ $seed->name_kh }}"
                         style="max-height: 420px; object-fit: cover;">
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center bg-light rounded-4 border text-muted shadow-sm" style="height: 380px;">
                        <i class="fa-solid fa-seedling fs-1 text-success opacity-50 mb-3"></i>
                        <span class="fw-bold">Grow2Growth Quality Seed</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-7">
            <span class="category-badge mb-2 d-inline-block">
                {{ App::isLocale('en') ? ($seed->category?->name_en ?? 'Seed') : ($seed->category?->name_kh ?? 'គ្រាប់ពូជ') }}
            </span>

            <h2 class="fw-bold mb-1 text-dark" style="font-family: 'Kantumruy Pro', sans-serif;">{{ App::isLocale('en') ? $seed->name_en : $seed->name_kh }}</h2>

            <div class="d-flex align-items-center gap-2 mb-3">
                <div class="text-warning small">
                    @php $avgRating = $seed->averageRating(); @endphp
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($avgRating))
                            <i class="fa-solid fa-star"></i>
                        @elseif($i - $avgRating <= 0.5)
                            <i class="fa-solid fa-star-half-stroke"></i>
                        @else
                            <i class="fa-regular fa-star text-muted opacity-50"></i>
                        @endif
                    @endfor
                </div>
                <span class="fw-bold text-dark font-monospace">{{ number_format($avgRating, 1) }}</span>
                <span class="text-muted small">·</span>
                <a href="#reviews-section" class="text-success small fw-bold text-decoration-none">
                    {{ $seed->reviews->count() }} {{ __('មតិវាយតម្លៃ') }}
                </a>
            </div>

            <h3 class="price-tag fs-2 mb-4 fw-bold text-success">
                ${{ number_format($seed->price, 2) }}
                <span class="fs-6 text-muted fw-normal">/ {{ __('per_pack') }}</span>
            </h3>

            <div class="card trace-info-card border-0 shadow-sm p-4 mb-4 bg-white rounded-4">
                <h5 class="fw-bold text-success mb-3 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                    <i class="fa-solid fa-circle-nodes text-success me-2"></i>{{ __('traceability_title') ?? 'ព័ត៌មានតាមដានប្រភពដើម (Traceability)' }}
                </h5>

                @if($seed->traceability)
                    <div class="mb-4 p-3 bg-success bg-opacity-10 rounded-3 border-start border-success border-3 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-dark small fw-bold"><i class="fa-solid fa-seedling text-success me-1"></i>អត្រាដុះលូតលាស់ពីមន្ទីរពិសោធន៍ (Germination Rate)</span>
                            <span class="text-success fw-bold fs-5 font-monospace">{{ $seed->traceability->germination_rate }}%</span>
                        </div>
                        <div class="progress" style="height: 8px; background-color: #e9ecef;">
                            <div class="progress-bar bg-success rounded" role="progressbar" style="width: {{ $seed->traceability->germination_rate }}%;" aria-valuenow="{{ $seed->traceability->germination_rate }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="trace-item-box d-flex align-items-center p-2 border rounded-3 bg-light bg-opacity-50">
                                <div class="trace-item-icon me-3 text-success fs-5"><i class="fa-solid fa-qrcode"></i></div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 11px;">លេខវគ្គផលិត (Batch)</small>
                                    <span class="text-primary fw-bold font-monospace small">#{{ $seed->traceability->batch_number }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="trace-item-box d-flex align-items-center p-2 border rounded-3 bg-light bg-opacity-50">
                                <div class="trace-item-icon me-3 text-success fs-5"><i class="fa-solid fa-calendar-check"></i></div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 11px;">កាលបរិច្ឆេទប្រមូលផល</small>
                                    <span class="text-dark fw-bold small">{{ $seed->traceability->harvest_date }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="trace-item-box d-flex align-items-center p-2 border rounded-3 bg-light bg-opacity-50">
                                <div class="trace-item-icon me-3 text-success fs-5"><i class="fa-solid fa-calendar-xmark"></i></div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 11px;">កាលបរិច្ឆេទផុតកំណត់</small>
                                    <span class="text-dark fw-bold small">{{ $seed->traceability->expiry_date }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="trace-item-box d-flex align-items-center p-2 border rounded-3 bg-light bg-opacity-50">
                                <div class="trace-item-icon me-3 text-success fs-5"><i class="fa-solid fa-map-location-dot"></i></div>
                                <div>
                                    <small class="text-muted d-block" style="font-size: 11px;">ប្រភពកសិដ្ឋានដាំដុះ</small>
                                    <span class="text-dark fw-bold small">
                                        {{ App::isLocale('en') ? ($seed->traceability->origin_en ?? $seed->traceability->origin_kh) : $seed->traceability->origin_kh }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <small class="text-muted">
                            <i class="fa-solid fa-shield-halved text-success me-1"></i> មានវិញ្ញាបនបត្រត្រួតពិនិត្យមន្ទីរពិសោធន៍
                        </small>
                        <a href="{{ url('/seed-certificate/' . $seed->id) }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold">
                            <i class="fa-solid fa-certificate me-1.5 text-warning"></i> មើលវិញ្ញាបនបត្រគុណភាព A4
                        </a>
                    </div>
                @else
                    <div class="p-3 text-center border rounded-3 bg-light">
                        <p class="text-muted mb-0 small"><i class="fa-solid fa-info-circle text-warning me-1"></i>មិនទាន់មានទិន្នន័យតាមដានប្រភពឡើយ (No Data Available)</p>
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center justify-content-between mb-4 px-2">
                <p class="text-muted m-0 fw-bold">
                    <i class="fa-solid fa-boxes-stacked me-2 text-success"></i>{{ __('stock') }}：
                    <span class="text-dark fs-5 fw-bold">{{ $seed->stock }}</span> កញ្ចប់
                </p>
            </div>

            <div class="pt-2">
                @if($seed->stock > 0)
                    <form action="{{ url('/add-to-cart/' . $seed->id) }}" method="POST" class="d-flex align-items-center gap-3 flex-wrap m-0">
                        @csrf
                        <div class="input-group rounded-pill overflow-hidden border bg-light shadow-sm" style="max-width: 140px; height: 48px;">
                            <button class="btn btn-link text-success border-0 px-3 text-decoration-none" type="button" onclick="const input = document.getElementById('seedDetailQty'); if(input.value > 1) input.value--;">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <input type="number" id="seedDetailQty" name="quantity" value="1" min="1" max="{{ $seed->stock }}" class="form-control border-0 bg-transparent text-center fw-bold text-dark font-monospace" style="font-size: 16px;">
                            <button class="btn btn-link text-success border-0 px-3 text-decoration-none" type="button" onclick="const input = document.getElementById('seedDetailQty'); if(input.value < {{ $seed->stock }}) input.value++;">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg px-5 fw-bold rounded-pill shadow seed-btn d-inline-flex align-items-center" style="height: 48px; font-size: 16px;">
                            <i class="fa-solid fa-cart-plus me-2"></i>{{ __('add_to_cart') }}
                        </button>

                        <button type="button" onclick="toggleDetailWishlist({{ $seed->id }})" class="btn btn-outline-danger btn-lg rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center" style="height: 48px;" id="detailWishlistBtn">
                            <i class="{{ isset(session('wishlist')[$seed->id]) ? 'fa-solid' : 'fa-regular' }} fa-heart me-2" id="detailWishlistIcon"></i>
                            <span id="detailWishlistText">{{ isset(session('wishlist')[$seed->id]) ? 'បានរក្សាទុក' : 'ពេញចិត្ត' }}</span>
                        </button>
                    </form>
                @else
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-secondary btn-lg px-5 fw-bold rounded-pill shadow-sm" disabled style="height: 48px;">
                            <i class="fa-solid fa-ban me-2"></i>{{ __('out_of_stock') }}
                        </button>
                        <button type="button" onclick="toggleDetailWishlist({{ $seed->id }})" class="btn btn-outline-danger btn-lg rounded-pill px-4 fw-bold shadow-sm d-inline-flex align-items-center" style="height: 48px;" id="detailWishlistBtn">
                            <i class="{{ isset(session('wishlist')[$seed->id]) ? 'fa-solid' : 'fa-regular' }} fa-heart me-2" id="detailWishlistIcon"></i>
                            <span id="detailWishlistText">{{ isset(session('wishlist')[$seed->id]) ? 'បានរក្សាទុក' : 'ពេញចិត្ត' }}</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($seed->traceability)
    <div class="row mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <h5 class="fw-bold text-success mb-4 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                    <i class="fa-solid fa-book-open text-success me-2"></i>សៀវភៅណែនាំបច្ចេកទេស និងវិធីសាស្រ្ដដាំដុះ (Cultivation Insights)
                </h5>
                <hr class="text-muted opacity-25 mb-4">

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 bg-light h-100 shadow-inner">
                            <small class="text-muted d-block fw-bold mb-1"><i class="fa-solid fa-flask text-success me-1"></i>កម្រិត pH ដីសមស្រប (Soil pH)</small>
                            <span class="fw-bold text-dark font-monospace fs-5">{{ $seed->traceability->soil_ph ?? '6.0 - 6.8 (លំនាំដើម)' }}</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 bg-light h-100 shadow-inner">
                            <small class="text-muted d-block fw-bold mb-1"><i class="fa-solid fa-droplet text-success me-1"></i>របៀបស្រោចទឹក (Watering Schedule)</small>
                            <span class="fw-bold text-dark small">{{ $seed->traceability->watering_schedule ?? 'ស្រោចទឹករាល់ព្រឹក (១ដងក្នុងមួយថ្ងៃ)' }}</span>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="p-3 border rounded-3 bg-light h-100 shadow-inner">
                            <small class="text-muted d-block fw-bold mb-1"><i class="fa-solid fa-gavel text-success me-1"></i>ឈ្មោះកសិដ្ឋានផលិត (Source Farm)</small>
                            <span class="fw-bold text-success small"><i class="fa-solid fa-tractor me-1"></i>{{ $seed->traceability->farm_name ?? 'សហគមន៍កសិករតាក្រាម' }}</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-4 border rounded-3 bg-light" style="background-color: #fafdfb !important;">
                            <small class="text-muted d-block fw-bold mb-2"><i class="fa-solid fa-paste text-success me-1"></i>ការណែនាំពីបច្ចេកទេសដាំដុះវិទ្យាសាស្ត្រ (Step-by-Step Guide)</small>
                            <div class="text-secondary small" style="white-space: pre-line; line-height: 1.6; font-size: 13.5px;">
                                {{ $seed->traceability->cultivation_guide ?? "១. ត្រូវត្រាំគ្រាប់ពូជក្នុងទឹកក្តៅឧណ្ហៗ (៤០អង្សាសេ) រយៈពេល ២ម៉ោង មុនសាប\n២. រៀបចំដីថ្នាលឱ្យធូរ មានជីជាតិល្អ និងរក្សា pH ដីចន្លោះ ៦-៧\n៣. គ្របចំបើងស្តើងៗពីលើក្រោយសាប និងស្រោចទឹកឱ្យជោកល្មមរាល់ព្រឹក។" }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- ⭐ ផ្នែកវាយតម្លៃ និងមតិយោបល់របស់កសិករ (Customer Reviews & Ratings) -->
    <div class="row mt-4" id="reviews-section">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                            <i class="fa-solid fa-star text-warning me-2"></i>ការវាយតម្លៃ និងមតិយោបល់កសិករ (Reviews & Ratings)
                        </h4>
                        <p class="text-muted small mb-0">ការចែករំលែកបទពិសោធន៍ផ្ទាល់ពីកសិករដែលបានដាំដុះគ្រាប់ពូជនេះជាក់ស្តែង</p>
                    </div>
                    <button class="btn btn-outline-success fw-bold rounded-pill px-4" type="button" data-bs-toggle="collapse" data-bs-target="#writeReviewCollapse" aria-expanded="false">
                        <i class="fa-solid fa-pen me-2"></i>សរសេរការវាយតម្លៃ
                    </button>
                </div>

                @if(session('review_success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                        <i class="fa-solid fa-circle-check me-2 fs-5 align-middle"></i>{{ session('review_success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                        <ul class="mb-0 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- 📝 REVIEW FORM (COLLAPSIBLE / EXPANDED) -->
                <div class="collapse {{ ($errors->any() || session('review_success')) ? 'show' : '' }} mb-4" id="writeReviewCollapse">
                    <div class="card card-body bg-light rounded-4 border-0 p-4 shadow-sm">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fa-solid fa-star-half-stroke text-warning me-1.5"></i>ចែករំលែកមតិរបស់អ្នកលើគ្រាប់ពូជនេះ
                        </h6>
                        <form action="{{ route('seed.review.store', $seed->id) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">ឈ្មោះរបស់អ្នក <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_name" class="form-control rounded-3" placeholder="ឧ. កសិករ សុខ ហេង" value="{{ old('customer_name', auth()->user()?->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">លេខទូរស័ព្ទ (ជាជម្រើស)</label>
                                    <input type="text" name="customer_phone" class="form-control rounded-3" placeholder="ឧ. 012 345 678" value="{{ old('customer_phone') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted d-block">កម្រិតផ្កាយវាយតម្លៃ (Star Rating) <span class="text-danger">*</span></label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach([5 => '⭐⭐⭐⭐⭐ (៥ ផ្កាយ - ល្អឥតខ្ចោះ)', 4 => '⭐⭐⭐⭐ (៤ ផ្កាយ - ល្អណាស់)', 3 => '⭐⭐⭐ (៣ ផ្កាយ - មធ្យម)', 2 => '⭐⭐ (២ ផ្កាយ - ខ្សោយ)', 1 => '⭐ (១ ផ្កាយ - មិនពេញចិត្ត)'] as $val => $label)
                                            <div class="form-check form-check-inline border bg-white rounded-pill px-3 py-1.5 m-0 shadow-xs">
                                                <input class="form-check-input" type="radio" name="rating" id="rating_{{ $val }}" value="{{ $val }}" {{ old('rating', '5') == $val ? 'checked' : '' }} required>
                                                <label class="form-check-label small fw-bold text-dark cursor-pointer" for="rating_{{ $val }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">មតិយោបល់ និងបទពិសោធន៍ដាំដុះ (Comment) <span class="text-danger">*</span></label>
                                    <textarea name="comment" rows="3" class="form-control rounded-3" placeholder="រៀបរាប់ពីអត្រាដុះ កម្លាំងលូតលាស់ រសជាតិ ឬទិន្នផលដែលទទួលបាន..." required>{{ old('comment') }}</textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-light rounded-pill px-4 me-2 fw-bold" data-bs-toggle="collapse" data-bs-target="#writeReviewCollapse">
                                        បោះបង់
                                    </button>
                                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                                        <i class="fa-solid fa-paper-plane me-1.5"></i>បញ្ជូនការវាយតម្លៃ
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- 📊 RATING OVERVIEW -->
                <div class="row g-4 align-items-center mb-4 p-4 rounded-4 bg-light border">
                    <div class="col-md-4 text-center border-end">
                        <div class="display-4 fw-bold text-dark font-monospace">{{ number_format($seed->averageRating(), 1) }}</div>
                        <div class="text-warning fs-5 my-1">
                            @php $avg = $seed->averageRating(); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($avg))
                                    <i class="fa-solid fa-star"></i>
                                @elseif($i - $avg <= 0.5)
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                @else
                                    <i class="fa-regular fa-star text-muted opacity-50"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="text-muted small">សរុប <strong>{{ $seed->reviews->count() }}</strong> ការវាយតម្លៃ</div>
                    </div>
                    <div class="col-md-8">
                        @php
                            $totalRev = $seed->reviews->count();
                        @endphp
                        @foreach([5, 4, 3, 2, 1] as $star)
                            @php
                                $starCount = $seed->reviews->where('rating', $star)->count();
                                $pct = $totalRev > 0 ? round(($starCount / $totalRev) * 100) : ($star == 5 ? 100 : 0);
                            @endphp
                            <div class="d-flex align-items-center gap-2 mb-1.5">
                                <span class="small fw-bold text-muted font-monospace" style="width: 45px;">{{ $star }} <i class="fa-solid fa-star text-warning"></i></span>
                                <div class="progress flex-grow-1" style="height: 8px;">
                                    <div class="progress-bar bg-warning rounded" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="small text-muted font-monospace text-end" style="width: 40px;">{{ $starCount }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 💬 REVIEWS LIST -->
                <div class="reviews-list">
                    @forelse($seed->reviews as $rev)
                        <div class="p-3 mb-3 border rounded-3 bg-white shadow-xs">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-success bg-opacity-10 text-success fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 15px;">
                                        {{ mb_substr($rev->customer_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">{{ $rev->customer_name }}</div>
                                        <small class="text-success" style="font-size: 11px;">
                                            <i class="fa-solid fa-circle-check me-1"></i>កសិករបានទិញពិតប្រាកដ
                                        </small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-solid fa-star {{ $i <= $rev->rating ? '' : 'text-muted opacity-25' }}"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted font-monospace" style="font-size: 11px;">{{ $rev->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <p class="text-secondary small mb-0 ps-5" style="line-height: 1.6;">
                                {{ $rev->comment }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted bg-light rounded-3">
                            <i class="fa-regular fa-comment-dots fs-1 text-muted opacity-50 mb-2"></i>
                            <p class="mb-1 fw-bold">មិនទាន់មានការវាយតម្លៃនៅឡើយទេ</p>
                            <small>សូមធ្វើជាកសិករដំបូងគេដែលចែករំលែកបទពិសោធន៍ដាំដុះគ្រាប់ពូជនេះ!</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDetailWishlist(seedId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    const icon = document.getElementById('detailWishlistIcon');
    const text = document.getElementById('detailWishlistText');
    const btn = document.getElementById('detailWishlistBtn');

    if (btn) {
        btn.style.transform = 'scale(1.08)';
        setTimeout(() => btn.style.transform = 'scale(1)', 200);
    }

    fetch(`/wishlist/toggle/${seedId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (icon && text) {
                if (data.added) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                    text.textContent = 'បានរក្សាទុក';
                } else {
                    icon.classList.remove('fa-solid');
                    icon.classList.add('fa-regular');
                    text.textContent = 'ពេញចិត្ត';
                }
            }

            // Update badge counts in header and dock
            document.querySelectorAll('.header-wishlist-count, .dock-wishlist-count').forEach(el => {
                el.textContent = data.count;
                el.style.display = data.count > 0 ? '' : 'none';
            });
        }
    })
    .catch(err => console.error('Wishlist toggle error:', err));
}
</script>
@endsection
