@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

@section('admin_content')
    <!-- ផ្ទាំងព្រួសបង្ហាញសារ Error ការពារការគាំងស្ងាត់ -->
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <h6 class="fw-bold text-danger mb-2"><i class="fa-solid fa-triangle-exclamation me-2"></i>មិនអាចរក្សាទុកបានទេ! មានកំហុសបច្គេកទេស Form ៖</h6>
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
        <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">កែប្រែព័ត៌មានគ្រាប់ពូជ</h4>
        <p class="text-muted small mb-0">កែប្រែព័ត៌មានលម្អិត តម្លៃ ចំនួនស្តុក រូបភាព និងខ្សែសង្វាក់ផលិតកម្មកសិកម្ម</p>
    </div>

    <div class="card admin-form-card border-0 shadow-sm bg-white mb-4">
        <div class="card-body p-4 admin-form-group">
            <form action="{{ url('/admin/seeds/update/' . $seed->id) }}" method="POST" enctype="multipart/form-data" class="m-0">
                @csrf
                @method('PUT')

                <!-- ផ្នែកទី ១៖ ព័ត៌មានទូទៅរបស់គ្រាប់ពូជ -->
                <h5 class="fw-bold text-dark mb-3" style="font-family: 'Kantumruy Pro', sans-serif;">
                    <i class="fa-solid fa-box-open text-success me-2"></i>១. ព័ត៌មានទូទៅរបស់គ្រាប់ពូជ
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">ឈ្មោះគ្រាប់ពូជ (ភាសាខ្មែរ) <span class="text-danger">*</span></label>
                        <input type="text" name="name_kh" class="form-control" value="{{ old('name_kh', $seed->name_kh) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Seed Name (English) <span class="text-danger">*</span></label>
                        <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $seed->name_en) }}" required>
                    </div>

                    <!-- 🟢 បន្ថែមរួមបញ្ចូល Category Dropdown ការពារកំហុស category_id cannot be null -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">ប្រភេទគ្រាប់ពូជ (Category) <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select form-control" required>
                            <option value="">-- ជ្រើសរើសប្រភេទ --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $seed->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name_kh }} ({{ $cat->name_en }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">តម្លៃ / កញ្ចប់ ($) <span class="text-danger">*</span></label>
                        <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $seed->price) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">ចំនួនក្នុងស្តុក (Stock) <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock', $seed->stock) }}" required>
                    </div>

                    <div class="col-12">
                        <label for="batch_number" class="form-label fw-bold text-dark">លេខបាច់គ្រាប់ពូជ (Batch Number For Traceability) <span class="text-danger">*</span></label>
                        <input type="text" name="batch_number" id="batch_number" class="form-control font-monospace text-success fw-bold" placeholder="ឧទាហរណ៍៖ BATCH-LET-001" value="{{ old('batch_number', $seed->batch_number ?? '') }}" required>
                        <small class="text-muted">បញ្ជាក់៖ លេខបាច់នេះនឹងត្រូវយកទៅបង្កើតជា Dynamic QR Code សម្រាប់តាមដានប្រភពដើម។</small>
                    </div>
                </div>

                <!-- ផ្នែកទី ២៖ ព័ត៌មានបច្ចេកទេស និងតាមដានប្រភពដើម (ចាក់សោរតាម Relationship) -->
                <h5 class="fw-bold text-dark mb-3 pt-2 border-top border-light" style="font-family: 'Kantumruy Pro', sans-serif;">
                    <i class="fa-solid fa-route text-success me-2"></i>២. ព័ត៌មានខ្សែសង្វាក់ផលិតកម្ម (Traceability & Batch)
                </h5>
                <div class="row g-3 mb-4">
                    <!-- 🟢 បន្ថែមប្រអប់ទីតាំងដាំដុះ Origin Location ការពារកំហុស origin_en Missing -->
                    <div class="col-md-6">
                        <label class="form-label fw-bold">ប្រភពកសិដ្ឋាន / ទីតាំងដាំដុះ (Origin Location) <span class="text-danger">*</span></label>
                        <input type="text" name="origin_kh" class="form-control" placeholder="ឧ. ភូមិតាគ្រាម ឃុំតាក្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង" value="{{ old('origin_kh', $seed->traceability?->origin_kh ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="farm_name" class="form-label fw-bold text-dark">ឈ្មោះកសិដ្ឋានផលិត (Source Farm Name)</label>
                        <input type="text" name="farm_name" id="farm_name" class="form-control" placeholder="ឧទាហរណ៍៖ កសិដ្ឋានបាត់ដំបងហ្គ្រីន" value="{{ old('farm_name', $seed->traceability?->farm_name ?? '') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">អត្រាដុះលូតលាស់ (%) <span class="text-danger">*</span></label>
                        <input type="number" name="germination_rate" class="form-control font-monospace" min="0" max="100" value="{{ old('germination_rate', $seed->traceability?->germination_rate ?? 95) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="harvest_date" class="form-label fw-bold text-dark">កាលបរិច្ឆេទប្រមូលផល <span class="text-danger">*</span></label>
                        <input type="text" name="harvest_date" id="harvest_date" class="form-control" placeholder="ឧទាហរណ៍៖ ចុងខែឧសភា ឆ្នាំ២០២៦" value="{{ old('harvest_date', $seed->traceability?->harvest_date ?? '') }}" required>
                    </div>

                    <!-- 🟢 បន្ថែមប្រអប់កាលបរិច្ឆេទផុតកំណត់ ដើម្បីដកសញ្ញា (?) ចេញពី Query -->
                    <div class="col-md-4">
                        <label for="expiry_date" class="form-label fw-bold text-dark">កាលបរិច្ឆេទផុតកំណត់ <span class="text-danger">*</span></label>
                        <input type="text" name="expiry_date" id="expiry_date" class="form-control" placeholder="ឧទាហរណ៍៖ រយៈពេល ១ឆ្នាំ" value="{{ old('expiry_date', $seed->traceability?->expiry_date ?? '') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="soil_ph" class="form-label fw-bold text-dark">កម្រិត pH ដីសមស្រប (Soil pH)</label>
                        <input type="text" name="soil_ph" id="soil_ph" class="form-control font-monospace" placeholder="ឧទាហរណ៍៖ 6.0 - 6.8" value="{{ old('soil_ph', $seed->traceability?->soil_ph ?? '') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="watering_schedule" class="form-label fw-bold text-dark">របបស្រោចទឹក (Watering Schedule)</label>
                        <input type="text" name="watering_schedule" id="watering_schedule" class="form-control" placeholder="ឧទាហរណ៍៖ ស្រោចទឹករាល់ព្រឹក" value="{{ old('watering_schedule', $seed->traceability?->watering_schedule ?? '') }}">
                    </div>

                    <div class="col-12">
                        <label for="cultivation_guide" class="form-label fw-bold text-dark">សៀវភៅណែនាំបច្ចេកទេស និងវិធីសាប (Cultivation Guide)</label>
                        <textarea name="cultivation_guide" id="cultivation_guide" class="form-control" rows="4" placeholder="រៀបរាប់ពីបច្ចេកទេសដាំដុះ...">{{ old('cultivation_guide', $seed->traceability?->cultivation_guide ?? '') }}</textarea>
                    </div>
                </div>

                <!-- ផ្នែកទី ៣៖ រូបភាពផលិតផល -->
                <div class="row g-3">
                    <div class="col-md-12 pt-2 border-top border-light">
                        <label class="form-label fw-bold">រូបភាពគ្រាប់ពូជ</label>
                        @if($seed->image)
                            <div class="mb-3 p-2 bg-light d-block d-md-inline-block rounded-3 border">
                                <img src="{{ asset('uploads/seeds/' . $seed->image) }}" class="admin-img-preview-box" alt="Current Seed" style="max-height: 120px; object-fit: cover; border-radius: 6px;">
                                <small class="text-muted d-block text-center mt-1 fw-bold" style="font-size: 11px;">រូបភាពបច្ចុប្បន្ន</small>
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted d-block mt-1" style="font-size: 12px;">
                            <i class="fa-solid fa-info-circle me-1"></i>ជ្រើសរើសឯកសាររូបភាពថ្មី ប្រសិនបើអ្នកចង់ផ្លាស់ប្តូររូបភាពចាស់។
                        </small>
                    </div>
                </div>

                <div class="mt-4 pt-2 border-top border-light text-end">
                    <button type="submit" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i>រក្សាទុកការកែប្រែទិន្នន័យ
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
