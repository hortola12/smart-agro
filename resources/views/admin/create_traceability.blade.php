@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

@section('admin_content')
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <h6 class="fw-bold text-danger mb-2"><i class="fa-solid fa-triangle-exclamation me-2"></i>មិនអាចរក្សាទុកបានទេ! មានកំហុសបច្ចេកទេស Form ៖</h6>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li class="fw-bold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ url('/admin/seeds') }}" class="btn btn-light btn-sm border fw-bold rounded-pill px-3 shadow-sm text-secondary mb-3" style="font-size: 12.5px;">
            <i class="fa-solid fa-arrow-left me-1"></i> ត្រលប់ក្រោយ
        </a>
        <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">បន្ថែមព័ត៌មានតាមដានប្រភពដើម (Add Traceability)</h4>
        <p class="text-muted small mb-0">បញ្ចូលទិន្នន័យខ្សែសង្វាក់ផលិតកម្មសម្រាប់គ្រាប់ពូជ៖ <b class="text-success">{{ $seed->name_kh }}</b></p>
    </div>

    <div class="card admin-form-card border-0 shadow-sm bg-white mb-4">
        <div class="card-body p-4 admin-form-group">
            <form action="{{ url('/admin/seeds/traceability/store/' . $seed->id) }}" method="POST" class="m-0">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-bold">លេខកូដវគ្គផលិត (Batch Number) <span class="text-danger">*</span></label>
                        <input type="text" name="batch_number" class="form-control font-monospace text-success fw-bold" placeholder="ឧ. BATCH-2026-LET01" value="{{ old('batch_number') }}" required>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="form-label fw-bold">ប្រភពកសិដ្ឋាន / ទីតាំងដាំដុះ (Origin Location) <span class="text-danger">*</span></label>
                        <input type="text" name="origin_kh" class="form-control" placeholder="ឧ. ភូមិតាគ្រាម ឃុំតាក្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង" value="{{ old('origin_kh') }}" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="form-label fw-bold">អត្រាដុះលូតលាស់ (%) <span class="text-danger">*</span></label>
                        <input type="number" name="germination_rate" class="form-control font-monospace" placeholder="ឧ. 95" min="0" max="100" value="{{ old('germination_rate') }}" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="form-label fw-bold">កាលបរិច្ឆេទប្រមូលផល <span class="text-danger">*</span></label>
                        <input type="text" name="harvest_date" class="form-control" placeholder="ឧ. ចុងខែមករា ឬ 2026-05-29" value="{{ old('harvest_date') }}" required>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="form-label fw-bold">កាលបរិច្ឆេទផុតកំណត់ <span class="text-danger">*</span></label>
                        <input type="text" name="expiry_date" class="form-control" placeholder="ឧ. រយៈពេល ១ឆ្នាំ ឬ 2027-05-29" value="{{ old('expiry_date') }}" required>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="card border-0 shadow-sm rounded-3 bg-light bg-opacity-50">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-success mb-3" style="font-family: 'Kantumruy Pro', sans-serif;">
                                    <i class="fa-solid fa-seedling me-2"></i>ព័ត៌មានបច្ចេកទេសដាំដុះ និងប្រភពដើមគ្រាប់ពូជ
                                </h5>
                                <hr class="text-muted opacity-25 mb-4">

                                <div class="row g-3">
                                    <div class="col-md-6 mb-2">
                                        <label for="farm_name" class="form-label fw-bold text-dark">ឈ្មោះកសិដ្ឋានផលិត (Source Farm Name)</label>
                                        <input type="text" name="farm_name" id="farm_name" class="form-control"
                                            placeholder="ឧទាហរណ៍៖ សហគមន៍កសិករតាក្រាម ឬ កសិដ្ឋានបាត់ដំបងហ្គ្រីន"
                                            value="{{ old('farm_name') }}">
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="soil_ph" class="form-label fw-bold text-dark">កម្រិត pH ដីសមស្រប (Soil pH)</label>
                                        <input type="text" name="soil_ph" id="soil_ph" class="form-control font-monospace"
                                            placeholder="ឧទាហរណ៍៖ 6.0 - 6.8"
                                            value="{{ old('soil_ph') }}">
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <label for="watering_schedule" class="form-label fw-bold text-dark">របបស្រោចទឹក (Watering Schedule)</label>
                                        <input type="text" name="watering_schedule" id="watering_schedule" class="form-control"
                                            placeholder="ឧទាហរណ៍៖ ស្រោចទឹករាល់ព្រឹក (១ដងក្នុងមួយថ្ងៃ) ឬ ប្រើប្រព័ន្ធដំណក់ទឹក"
                                            value="{{ old('watering_schedule') }}">
                                    </div>

                                    <div class="col-12 mb-2">
                                        <label for="cultivation_guide" class="form-label fw-bold text-dark">សៀវភៅណែនាំបច្ចេកទេស និងវិធីសាប (Cultivation Guide)</label>
                                        <textarea name="cultivation_guide" id="cultivation_guide" class="form-control" rows="4"
                                                placeholder="រៀបរាប់ពីបច្ចេកទេសដាំដុះ ដូចជា៖ ត្រូវត្រាំទឹកក្ដៅឧណ្ហៗ ២ម៉ោង មុនសាប...">{{ old('cultivation_guide') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 border-top border-light text-end">
                    <button type="submit" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm">
                        <i class="fa-solid fa-location-dot me-2"></i>រក្សាទុកព័ត៌មានប្រភព
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
