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
                <img src="{{ $seed->image ? asset('uploads/seeds/' . $seed->image) : 'https://via.placeholder.com/500x500' }}"
                     class="img-fluid detail-seed-img shadow-sm w-100 rounded-4 border"
                     alt="Seed Image"
                     style="max-height: 420px; object-fit: cover;">
            </div>
        </div>

        <div class="col-md-7">
            <span class="category-badge mb-2 d-inline-block">
                {{ App::isLocale('en') ? ($seed->category?->name_en ?? 'Seed') : ($seed->category?->name_kh ?? 'គ្រាប់ពូជ') }}
            </span>

            <h2 class="fw-bold mb-2 text-dark" style="font-family: 'Kantumruy Pro', sans-serif;">{{ App::isLocale('en') ? $seed->name_en : $seed->name_kh }}</h2>

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
                    <form action="{{ url('/add-to-cart/' . $seed->id) }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg px-5 fw-bold rounded-pill shadow seed-btn py-2" style="font-size: 16px;">
                            <i class="fa-solid fa-cart-plus me-2"></i>{{ __('add_to_cart') }}
                        </button>
                    </form>
                @else
                    <button class="btn btn-secondary btn-lg px-5 fw-bold rounded-pill shadow-sm" disabled>
                        <i class="fa-solid fa-ban me-2"></i>{{ __('out_of_stock') }}
                    </button>
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
</div>
@endsection
