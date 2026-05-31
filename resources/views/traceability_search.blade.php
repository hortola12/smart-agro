@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/traceability-search-style.css') }}">

<div class="container py-4">
    <div class="row justify-content-center py-4">
        <div class="col-md-10 col-lg-8">
            <div class="card trace-card p-5 shadow-sm rounded-4 border bg-white position-relative overflow-hidden">

                <div class="trace-bg-circle circle-1"></div>
                <div class="trace-bg-circle circle-2"></div>

                <div class="position-relative text-center" style="z-index: 2;">

                    <div class="trace-icon-box mb-4 shadow bg-success text-white mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px;">
                        <i class="fa-solid fa-location-dot fs-3"></i>
                    </div>

                    <h2 class="fw-bold text-dark mb-3 trace-title" style="font-family: 'Kantumruy Pro', sans-serif;">
                        {{ __('menu_traceability') ?? 'តាមដានប្រភពដើមគ្រាប់ពូជ' }}
                    </h2>

                    <p class="text-muted trace-desc px-md-5 mb-4 small">
                        ស្វាគមន៍មកកាន់ប្រព័ន្ធធានាគុណភាព និងខ្សែសង្វាក់កសិកម្មវៃឆ្លាត
                        <b class="text-success">Grow2Growth</b> 🌱 <br>
                        សូមវាយបញ្ចូលលេខកូដវគ្គផលិត (Batch Number) ដើម្បីឆែកពិនិត្យមើលប្រភពកសិដ្ឋានដើម អត្រាដុះលូតលាស់ពីមន្ទីរពិសោធន៍ និងព័ត៌មានបច្ចេកទេសដាំដុះ Preserved។
                    </p>

                    @if($errors->has('search'))
                        <div class="alert alert-danger border-0 shadow-sm rounded-pill py-2 px-4 mb-4 mx-md-5 small fw-bold text-danger d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>
                            <span>{{ $errors->first('search') }}</span>
                        </div>
                    @endif

                    <form action="{{ url('/trace-batch') }}"
                          method="GET"
                          class="search-hub-wrapper rounded-pill p-2 mb-4 mx-md-5 shadow-sm border border-success bg-white d-flex align-items-center">

                        <div class="input-group align-items-center">
                            <span class="input-group-text bg-white border-0 text-success ps-3">
                                <i class="fa-solid fa-qrcode fs-5"></i>
                            </span>

                            <input type="text"
                                   id="batchSearchInput"
                                   name="search"
                                   class="form-control border-0 px-2 fs-6 search-input"
                                   placeholder="វាយលេខ Batch ទីនេះ... (ឧ. BATCH-2026-LT03)"
                                   value="{{ request('search') ?? old('search') }}"
                                   required>

                            <button class="btn btn-success rounded-pill px-4 fw-bold search-btn"
                                    type="submit" style="font-size: 14px; height: 40px;">
                                <i class="fa-solid fa-magnifying-glass me-1"></i>
                                {{ __('search') ?? 'ស្វែងរក' }}
                            </button>
                        </div>
                    </form>

                    <div class="mt-3">
                        <span class="text-muted small d-block mb-2 fw-bold">
                            <i class="fa-solid fa-lightbulb text-warning me-1"></i>
                            វគ្គផលិតគំរូសម្រាប់សាកល្បង៖
                        </span>

                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <span class="badge bg-light text-success border font-monospace px-3 py-2 shadow-sm rounded-pill"
                                  style="cursor: pointer; font-size: 13px; font-weight: bold;"
                                  onclick="fillSampleCode('BATCH-2026-LT03')">
                                BATCH-2026-LT03
                            </span>

                            <span class="badge bg-light text-success border font-monospace px-3 py-2 shadow-sm rounded-pill"
                                  style="cursor: pointer; font-size: 13px; font-weight: bold;"
                                  onclick="fillSampleCode('BATCH-2026-LT01')">
                                BATCH-2026-LT01
                            </span>

                            <span class="badge bg-light text-success border font-monospace px-3 py-2 shadow-sm rounded-pill"
                                  style="cursor: pointer; font-size: 13px; font-weight: bold;"
                                  onclick="fillSampleCode('BATCH-2026-LT05')">
                                BATCH-2026-LT05
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function fillSampleCode(code){
        document.getElementById('batchSearchInput').value = code;
    }
</script>

@endsection
