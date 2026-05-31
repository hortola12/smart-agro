@extends('layouts.app')

@section('content')
<div class="container py-5 text-dark">
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5" style="background-color: #ffffff; border-left: 6px solid #198754 !important;">
        <div class="row align-items-center">
            <div class="col-md-8">
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-2 fw-bold" style="font-size: 13px;">
                    <i class="fa-solid fa-qrcode me-1"></i> លទ្ធផលស្វែងរកប្រភពដើមគ្រាប់ពូជវៃឆ្លាត
                </span>
                <h3 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">លេខកូដបាច់៖ <span class="text-success">{{ $traceability->batch_number }}</span></h3>
                <p class="text-muted small mb-0">គ្រាប់ពូជកសិកម្ម៖ <b class="text-success" style="font-size: 15px;">{{ App::isLocale('en') ? $traceability->seed?->name_en : $traceability->seed?->name_kh }}</b></p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div class="bg-light p-3 rounded-3 d-inline-block text-center border shadow-sm">
                    <span class="text-muted d-block small fw-bold mb-1"><i class="fa-solid fa-seedling text-success me-1"></i>អត្រាដុះលូតលាស់</span>
                    <h3 class="text-success fw-bold m-0 font-monospace">{{ number_format($traceability->germination_rate, 1) }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5">
        <div class="col-lg-7">
            <h5 class="fw-bold text-dark mb-4" style="font-family: 'Kantumruy Pro', sans-serif;"><i class="fa-solid fa-route text-success me-2"></i>ខ្សែសង្វាក់ផលិតកម្ម (Production Timeline)</h5>

            <div class="position-relative ps-4 border-start border-success border-2 ms-3 mt-2">

                <div class="position-relative mb-5">
                    <div class="position-absolute rounded-circle bg-success d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; left: -33px; top: 0; box-shadow: 0 0 0 5px #f4f9f5;">
                        <i class="fa-solid fa-tree" style="font-size: 12px;"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">១. ប្រភពកសិដ្ឋានដាំដុះ និងប្រភពដើម</h6>
                        <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot text-danger me-1"></i> ទីតាំង៖ <b>{{ $traceability->origin_kh }}</b> ({{ $traceability->farm_name ?? 'សហគមន៍កសិករតាក្រាម' }})</p>
                        <div class="bg-white p-3 rounded-3 border shadow-sm small text-secondary">
                            ព័ត៌មានលម្អិត៖ គ្រាប់ពូជមេត្រូវបានជ្រើសរើស និងថែទាំយ៉ាងយកចិត្តទុកដាក់ក្នុងផ្ទះសំណាញ់កសិកម្មបច្ចេកវិទ្យា ចេញពីតំបន់ឃុំតាក្រាម ស្រុកបាណន់ ធានាបាននូវភាពសុទ្ធ និងគ្មានជំងឺឆ្លងឡើយ។
                        </div>
                    </div>
                </div>

                <div class="position-relative mb-5">
                    <div class="position-absolute rounded-circle bg-success d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; left: -33px; top: 0; box-shadow: 0 0 0 5px #f4f9f5;">
                        <i class="fa-solid fa-wheat-awn" style="font-size: 12px;"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">២. កាលបរិច្ឆេទប្រមូលផល និងកែច្នៃ</h6>
                        <p class="text-muted small mb-2"><i class="fa-solid fa-calendar-days text-success me-1"></i> ថ្ងៃប្រមូលផល៖ <b class="text-dark">{{ $traceability->harvest_date }}</b></p>
                        <div class="bg-white p-3 rounded-3 border shadow-sm small text-secondary">
                            ការប្រមូលផលធ្វើឡើងនៅពេលគ្រាប់ពូជមានអាយុកាលទុំល្មម ស្របតាមបច្ចេកទេសកសិកម្មវិទ្យាសាស្ត្រ រួចឆ្លងកាត់ការសម្ងួតយ៉ាងយកចិត្តទុកដាក់ដើម្បីរក្សាគុណភាពគ្រាប់ឱ្យបានយូរអង្វែង។
                        </div>
                    </div>
                </div>

                <div class="position-relative">
                    <div class="position-absolute rounded-circle bg-success d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; left: -33px; top: 0; box-shadow: 0 0 0 5px #f4f9f5;">
                        <i class="fa-solid fa-box-open" style="font-size: 12px;"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">៣. រយៈពេលរក្សាទុក និងការផុតកំណត់</h6>
                        <p class="text-muted small mb-2"><i class="fa-solid fa-calendar-xmark text-danger me-1"></i> ថ្ងៃផុតកំណត់គុណភាព៖ <b class="text-dark">{{ $traceability->expiry_date }}</b></p>
                        <div class="bg-white p-3 rounded-3 border shadow-sm small text-secondary">
                            ឆ្លងកាត់ការធ្វើតេស្តអត្រាដុះលូតលាស់យ៉ាងជោគជ័យពីមន្ទីរពិសោធន៍ និងវេចខ្ចប់ក្នុងកញ្ចប់ការពារកម្តៅ និងសំណើម ត្រៀមចែកចាយជូនបងប្អូនកសិករចែកចាយដាំដុះ។
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-5 d-flex align-items-center justify-content-center">
            <div class="position-sticky" style="top: 20px; width: 100%;">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-2">
                    @if($traceability->seed && $traceability->seed->image)
                        <img src="{{ asset('uploads/seeds/' . $traceability->seed->image) }}"
                            class="img-fluid rounded-3"
                            alt="Final Seed Package"
                            style="height: 380px; width: 100%; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/400x380"
                            class="img-fluid rounded-3"
                            alt="Vegetable Quality Testing"
                            style="height: 380px; width: 100%; object-fit: cover;">
                    @endif
                    <div class="p-3 text-center bg-light rounded-bottom-3 mt-2 border-top">
                        <h6 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">គ្រាប់ពូជផ្លូវការប្រចាំបាច់ផលិតកម្ម</h6>
                        <small class="text-muted">រូបភាពកញ្ចប់ផលិតផលជាក់ស្តែងដែលបានចុះបញ្ជីក្នុងប្រព័ន្ធ</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mt-5" style="border-left: 5px solid #198754 !important; background-color: #ffffff;">
        <div class="card-body p-4">
            <h5 class="fw-bold text-success mb-3" style="font-family: 'Kantumruy Pro', sans-serif;">
                <i class="fa-solid fa-book-open-reader me-2"></i>សៀវភៅណែនាំបច្ចេកទេសដាំដុះវិទ្យាសាស្ត្រ
            </h5>
            <p class="text-muted small">ការណែនាំពិសេសពីអ្នកជំនាញកសិកម្ម សម្រាប់គ្រាប់ពូជបាច់ប្រភេទនេះ៖</p>

            <div class="row g-3 mt-1">
                <div class="col-12 bg-light p-3 rounded-3 border shadow-sm">
                    <strong class="text-dark d-block mb-2">
                        <i class="fa-solid fa-seedling text-success me-1.5"></i> របៀបបណ្តុះ និងវិធីសាបដាំដុះ៖
                    </strong>
                    <span class="text-secondary small" style="white-space: pre-line; line-height: 1.6;">
                        {{ $traceability->cultivation_guide ?? "១. ត្រូវត្រាំគ្រាប់ពូជក្នុងទឹកក្តៅឧណ្ហៗ រយៈពេល ២ម៉ោង មុនសាបលើដីកំប៉ុស្តិ៍\n២. រៀបចំដីថ្នាលឱ្យធូរ មានជីជាតិល្អ និងរក្សាដីឱ្យមានសំណើមល្មម។" }}
                    </span>
                </div>

                <div class="col-sm-6 bg-light p-3 rounded-3 border shadow-sm">
                    <strong class="text-dark d-block mb-1">
                        <i class="fa-solid fa-droplet text-primary me-1.5"></i> របបស្រោចទឹក (Watering Schedule)៖
                    </strong>
                    <span class="text-secondary small">
                        {{ $traceability->watering_schedule ?? 'ស្រោចទឹករាល់ព្រឹក (ម្តងក្នុងមួយថ្ងៃ) ឬប្រើប្រព័ន្ធដំណក់ទឹក' }}
                    </span>
                </div>

                <div class="col-sm-6 bg-light p-3 rounded-3 border shadow-sm">
                    <strong class="text-dark d-block mb-1">
                        <i class="fa-solid fa-flask text-warning me-1.5"></i> កម្រិត pH ដីសមស្រប (Soil pH)៖
                    </strong>
                    <span class="text-secondary font-monospace fw-bold small">
                        pH {{ $traceability->soil_ph ?? '6.0 - 6.8 (ដីធូរ មានជីវជាតិខ្ពស់)' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5 border-top pt-4">
        <a href="{{ url('/') }}" class="btn btn-outline-success fw-bold rounded-pill px-4 shadow-sm">
            <i class="fa-solid fa-arrow-left me-1.5"></i> ត្រលប់ទៅទំព័រដើមវិញ
        </a>
    </div>
</div>
@endsection
