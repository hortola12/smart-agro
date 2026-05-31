@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

@section('admin_content')

<div class="container-fluid traceability-detail-page">

    <div class="trace-topbar mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            <div>
                <a href="{{ url('/admin/traceability') }}"
                   class="back-btn mb-3 d-inline-flex align-items-center text-decoration-none">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    ត្រលប់ទៅបញ្ជីរួម
                </a>

                <h3 class="trace-page-title mb-2" style="font-family: 'Kantumruy Pro', sans-serif;">
                    <i class="fa-solid fa-file-shield text-success me-2"></i>
                    ព័ត៌មានកសិកម្មលម្អិតនៃវគ្គផលិត
                </h3>

                <p class="trace-page-subtitle mb-0">
                    ពិនិត្យមើលប្រភពដើម ការដាំដុះ និងព័ត៌មានវិទ្យាសាស្ត្រកសិកម្មលម្អិត
                </p>
            </div>

            <div class="batch-code-box shadow-sm">
                <span class="batch-label">Batch Number</span>
                <h5 class="batch-code mb-0 font-monospace">
                    #{{ $trace->batch_number }}
                </h5>
            </div>

        </div>
    </div>

    <div class="row g-4">

        <div class="col-12 col-lg-4">

            <div class="card trace-detail-card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <div class="text-center">

                        <div class="seed-image-wrapper mb-4">
                            <img src="{{ $trace->seed?->image ? asset('uploads/seeds/' . $trace->seed->image) : 'https://via.placeholder.com/150' }}"
                                 class="seed-image rounded-3 border shadow-sm"
                                 alt="Seed Image"
                                 style="width: 130px; height: 130px; object-fit: cover;">
                        </div>

                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5 mb-3 d-inline-flex align-items-center fw-bold" style="font-size: 12px;">
                            <i class="fa-solid fa-seedling me-2"></i>
                            គ្រាប់ពូជកសិកម្ម
                        </span>

                        <h4 class="seed-title mb-1 fw-bold text-dark" style="font-family: 'Kantumruy Pro', sans-serif;">
                            {{ $trace->seed?->name_kh ?? 'មិនមានឈ្មោះ' }}
                        </h4>

                        <p class="seed-subtitle mb-4 text-muted font-monospace small">
                            {{ $trace->seed?->name_en }}
                        </p>

                    </div>

                    <div class="trace-info-box mb-4 bg-light p-3 rounded-3 border border-light">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="fw-bold text-dark small mb-0">
                                <i class="fa-solid fa-chart-line text-success me-1"></i>
                                អត្រាដុះលូតលាស់
                            </label>

                            <span class="badge bg-success text-white font-monospace rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11.5px;">
                                {{ $trace->germination_rate }}%
                            </span>
                        </div>

                        <div class="progress custom-progress" style="height: 7px; background-color: #e9ecef;">
                            <div class="progress-bar bg-success rounded"
                                 role="progressbar"
                                 style="width: {{ $trace->germination_rate }}%;">
                            </div>
                        </div>

                    </div>

                    <div class="trace-info-list d-flex flex-column gap-2">

                        <div class="trace-info-item d-flex align-items-center p-2.5 border rounded-3 bg-light bg-opacity-50">
                            <div class="trace-icon success me-3 text-success bg-success bg-opacity-10 rounded p-2" style="width: 38px; text-align: center;">
                                <i class="fa-solid fa-calendar-check fs-5"></i>
                            </div>

                            <div>
                                <small class="text-muted d-block" style="font-size: 11px; font-weight: 600;">
                                    ថ្ងៃប្រមូលផល (Harvest)
                                </small>
                                <strong class="text-dark font-monospace" style="font-size: 13.5px;">
                                    {{ $trace->harvest_date }}
                                </strong>
                            </div>
                        </div>

                        <div class="trace-info-item d-flex align-items-center p-2.5 border rounded-3 bg-light bg-opacity-50">
                            <div class="trace-icon danger me-3 text-danger bg-danger bg-opacity-10 rounded p-2" style="width: 38px; text-align: center;">
                                <i class="fa-solid fa-calendar-xmark fs-5"></i>
                            </div>

                            <div>
                                <small class="text-muted d-block" style="font-size: 11px; font-weight: 600;">
                                    ថ្ងៃផុតកំណត់ (Expiry)
                                </small>
                                <strong class="text-dark font-monospace" style="font-size: 13.5px;">
                                    {{ $trace->expiry_date }}
                                </strong>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>

        <div class="col-12 col-lg-8">

            <div class="card trace-detail-card border-0 shadow-sm h-100">
                <div class="card-body p-4">

                    <div class="section-header mb-4 d-flex align-items-center">
                        <div class="section-icon bg-success bg-opacity-10 text-success p-2.5 rounded-3 me-3 fs-4">
                            <i class="fa-solid fa-leaf"></i>
                        </div>

                        <div>
                            <h5 class="fw-bold text-dark mb-0" style="font-family: 'Kantumruy Pro', sans-serif; font-size: 16px;">
                                ទិន្នន័យកសិកម្មវិទ្យាសាស្ត្រ
                            </h5>
                            <p class="text-muted small mb-0" style="font-size: 11.5px;">
                                Agricultural Scientific Information & Traceability
                            </p>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <div class="mini-info-card border rounded-3 p-3 bg-light bg-opacity-25 shadow-sm h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="mini-icon text-success me-2 fs-5">
                                        <i class="fa-solid fa-industry"></i>
                                    </div>
                                    <small class="text-muted fw-bold mb-0" style="font-size: 12px;">កសិដ្ឋានផលិត (Source Farm)</small>
                                </div>
                                <strong class="text-dark d-block ps-4" style="font-size: 14.5px;">
                                    {{ $trace->farm_name ?? 'N/A' }}
                                </strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mini-info-card border rounded-3 p-3 bg-light bg-opacity-25 shadow-sm h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="mini-icon text-danger me-2 fs-5">
                                        <i class="fa-solid fa-map-location-dot"></i>
                                    </div>
                                    <small class="text-muted fw-bold mb-0" style="font-size: 12px;">ទីតាំងភូមិសាស្ត្រ (Origin Location)</small>
                                </div>
                                <strong class="text-dark d-block ps-4" style="font-size: 14.5px;">
                                    {{ $trace->origin_kh ?? 'N/A' }}
                                </strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mini-info-card border rounded-3 p-3 bg-light bg-opacity-25 shadow-sm h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="mini-icon text-primary me-2 fs-5">
                                        <i class="fa-solid fa-flask"></i>
                                    </div>
                                    <small class="text-muted fw-bold mb-0" style="font-size: 12px;">កម្រិត pH ដីសមស្រប (Soil pH)</small>
                                </div>
                                <strong class="text-primary font-monospace fs-5 d-block ps-4 fw-bold">
                                    {{ $trace->soil_ph ?? 'N/A' }}
                                </strong>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mini-info-card border rounded-3 p-3 bg-light bg-opacity-25 shadow-sm h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="mini-icon text-info me-2 fs-5">
                                        <i class="fa-solid fa-droplet"></i>
                                    </div>
                                    <small class="text-muted fw-bold mb-0" style="font-size: 12px;">របបស្រោចទឹក (Watering Schedule)</small>
                                </div>
                                <strong class="text-dark d-block ps-4" style="font-size: 14.5px;">
                                    {{ $trace->watering_schedule ?? 'N/A' }}
                                </strong>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex align-items-center p-3 mb-3 rounded-4 bg-success bg-opacity-10 border border-success border-opacity-20 shadow-sm transition-all">
                        <div class="guide-icon-wrapper bg-success text-white rounded-3 shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; min-width: 48px;">
                            <i class="fa-solid fa-book-open fs-5"></i>
                        </div>

                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-success mb-0" style="font-family: 'Kantumruy Pro', sans-serif; font-size: 15px; letter-spacing: 0.3px;">
                                សៀវភៅណែនាំបច្ចេកទេសដាំដុះ
                            </h6>
                            <small class="text-muted font-monospace d-flex align-items-center mt-0.5" style="font-size: 11px;">
                                <i class="fa-solid fa-graduation-cap me-1 small"></i> Cultivation Guide Instruction
                            </small>
                        </div>

                        <div class="text-success bg-white rounded-circle d-none d-sm-flex align-items-center justify-content-center border" style="width: 28px; height: 28px;">
                            <i class="fa-solid fa-chevron-right style='font-size: 10px;'"></i>
                        </div>
                    </div>

                        <div class="guide-content p-3 bg-white rounded-3 border border-light" style="line-height: 1.8; white-space: pre-line; color: #2d3748; font-size: 14px;">
                            {{ $trace->cultivation_guide ?? 'មិនទាន់មានការបញ្ចូលសៀវភៅណែនាំបច្ចេកទេសនៅឡើយទេ។' }}
                        </div>

                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

@endsection
