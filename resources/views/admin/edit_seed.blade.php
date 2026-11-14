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
                        <div class="input-group">
                            <span class="input-group-text bg-light text-success border-end-0"><i class="fa-solid fa-percent"></i></span>
                            <input type="number" name="germination_rate" class="form-control font-monospace border-start-0" min="0" max="100" value="{{ old('germination_rate', $seed->traceability?->germination_rate ?? 95) }}" required>
                        </div>
                    </div>

                    @php
                        $editHarvest = old('harvest_date', $seed->traceability?->harvest_date);
                        try {
                            if ($editHarvest) $editHarvest = \Carbon\Carbon::parse($editHarvest)->format('Y-m-d');
                        } catch (\Exception $e) {}
                        if (!$editHarvest) $editHarvest = date('Y-m-d');

                        $editExpiry = old('expiry_date', $seed->traceability?->expiry_date);
                        try {
                            if ($editExpiry) $editExpiry = \Carbon\Carbon::parse($editExpiry)->format('Y-m-d');
                        } catch (\Exception $e) {}
                        if (!$editExpiry) $editExpiry = date('Y-m-d', strtotime('+1 year'));
                    @endphp

                    <!-- 📅 កាលបរិច្ឆេទប្រមូលផល (Harvest Date) -->
                    <div class="col-md-4">
                        <label for="harvest_date" class="form-label fw-bold text-dark d-flex justify-content-between align-items-center mb-1">
                            <span><i class="fa-solid fa-calendar-check text-success me-1.5"></i>កាលបរិច្ឆេទប្រមូលផល <span class="text-danger">*</span></span>
                        </label>
                        <div class="input-group shadow-sm rounded-3 overflow-hidden">
                            <input type="date" name="harvest_date" id="harvest_date" class="form-control font-monospace fw-bold text-dark" value="{{ $editHarvest }}" required>
                        </div>
                        <div class="d-flex align-items-center gap-1 mt-1.5 flex-wrap">
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2 text-muted rounded-pill" style="font-size: 11px;" onclick="setHarvestPreset(0)">ថ្ងៃនេះ</button>
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2 text-muted rounded-pill" style="font-size: 11px;" onclick="setHarvestPreset(-1)">ម្សិលមិញ</button>
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2 text-muted rounded-pill" style="font-size: 11px;" onclick="setHarvestPreset(-7)">-៧ ថ្ងៃ</button>
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2 text-muted rounded-pill" style="font-size: 11px;" onclick="setHarvestPreset(-30)">-១ ខែ</button>
                        </div>
                    </div>

                    <!-- 📅 កាលបរិច្ឆេទផុតកំណត់ (Expiry Date) -->
                    <div class="col-md-4">
                        <label for="expiry_date" class="form-label fw-bold text-dark d-flex justify-content-between align-items-center mb-1">
                            <span><i class="fa-solid fa-calendar-xmark text-danger me-1.5"></i>កាលបរិច្ឆេទផុតកំណត់ <span class="text-danger">*</span></span>
                        </label>
                        <div class="input-group shadow-sm rounded-3 overflow-hidden">
                            <input type="date" name="expiry_date" id="expiry_date" class="form-control font-monospace fw-bold text-dark" value="{{ $editExpiry }}" required>
                        </div>
                        <div class="d-flex align-items-center gap-1 mt-1.5 flex-wrap">
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2 text-muted rounded-pill" style="font-size: 11px;" onclick="setExpiryPreset(6)">+៦ ខែ</button>
                            <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 fw-bold rounded-pill" style="font-size: 11px;" onclick="setExpiryPreset(12)" title="ស្តង់ដារគ្រាប់ពូជបន្លែ (១ ឆ្នាំ)">+១ ឆ្នាំ ★</button>
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2 text-muted rounded-pill" style="font-size: 11px;" onclick="setExpiryPreset(18)">+១៨ ខែ</button>
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2 text-muted rounded-pill" style="font-size: 11px;" onclick="setExpiryPreset(24)">+២ ឆ្នាំ</button>
                        </div>
                    </div>

                    <!-- 💡 សុពលភាពគ្រាប់ពូជ (Dynamic Shelf-Life Live Preview Banner) -->
                    <div class="col-12 mt-2">
                        <div id="shelf_life_banner" class="p-2.5 rounded-3 bg-light border d-flex align-items-center justify-content-between flex-wrap gap-2 shadow-sm" style="font-size: 12.5px;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-1" id="shelf_life_badge">
                                    <i class="fa-solid fa-hourglass-half me-1"></i>សុពលភាព៖ ៣៦៥ ថ្ងៃ
                                </span>
                                <span class="text-muted" id="shelf_life_text">គិតចាប់ពីថ្ងៃប្រមូលផល ដល់ថ្ងៃផុតកំណត់</span>
                            </div>
                            <small class="text-muted font-monospace" id="date_range_display">
                                <i class="fa-regular fa-calendar me-1"></i>--/--/---- → --/--/----
                            </small>
                        </div>
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

    <script>
        function setHarvestPreset(daysOffset) {
            const d = new Date();
            d.setDate(d.getDate() + daysOffset);
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            const harvestInput = document.getElementById('harvest_date');
            if (harvestInput) {
                harvestInput.value = `${yyyy}-${mm}-${dd}`;
                setExpiryPreset(12);
                updateShelfLifePreview();
            }
        }

        function setExpiryPreset(monthsToAdd) {
            const harvestInput = document.getElementById('harvest_date');
            const expiryInput = document.getElementById('expiry_date');
            if (!expiryInput) return;

            let baseDate = new Date();
            if (harvestInput && harvestInput.value) {
                const parts = harvestInput.value.split('-');
                if (parts.length === 3) {
                    baseDate = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
                }
            }

            baseDate.setMonth(baseDate.getMonth() + monthsToAdd);
            const yyyy = baseDate.getFullYear();
            const mm = String(baseDate.getMonth() + 1).padStart(2, '0');
            const dd = String(baseDate.getDate()).padStart(2, '0');
            expiryInput.value = `${yyyy}-${mm}-${dd}`;
            updateShelfLifePreview();
        }

        function updateShelfLifePreview() {
            const harvestInput = document.getElementById('harvest_date');
            const expiryInput = document.getElementById('expiry_date');
            const badge = document.getElementById('shelf_life_badge');
            const text = document.getElementById('shelf_life_text');
            const display = document.getElementById('date_range_display');

            if (!harvestInput || !expiryInput || !badge) return;

            const hVal = harvestInput.value;
            const eVal = expiryInput.value;
            if (!hVal || !eVal) return;

            const hDate = new Date(hVal);
            const eDate = new Date(eVal);
            const diffDays = Math.ceil((eDate - hDate) / (1000 * 60 * 60 * 24));

            if (diffDays <= 0) {
                badge.className = 'badge bg-danger bg-opacity-15 text-danger rounded-pill px-2.5 py-1';
                badge.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i>កាលបរិច្ឆេទមិនត្រឹមត្រូវ!';
                text.innerText = 'កាលបរិច្ឆេទផុតកំណត់ត្រូវតែក្រោយថ្ងៃប្រមូលផល';
                if (display) display.innerText = `${hVal} → ${eVal}`;
                return;
            }

            let durationText = '';
            const months = Math.round(diffDays / 30.4375);
            if (months === 12) {
                durationText = `${diffDays} ថ្ងៃ (១ ឆ្នាំពេញ)`;
            } else if (months === 24) {
                durationText = `${diffDays} ថ្ងៃ (២ ឆ្នាំពេញ)`;
            } else if (months === 6) {
                durationText = `${diffDays} ថ្ងៃ (៦ ខែ)`;
            } else {
                durationText = `${diffDays} ថ្ងៃ (~${months} ខែ)`;
            }

            badge.className = 'badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-1';
            badge.innerHTML = `<i class="fa-solid fa-hourglass-half me-1"></i>សុពលភាព៖ ${durationText}`;
            text.innerText = 'គ្រាប់ពូជរក្សាគុណភាពបានល្អសម្រាប់ដាំដុះ';

            if (display) {
                const formatDMY = (d) => {
                    const day = String(d.getDate()).padStart(2, '0');
                    const mo = String(d.getMonth() + 1).padStart(2, '0');
                    return `${day}/${mo}/${d.getFullYear()}`;
                };
                display.innerHTML = `<i class="fa-regular fa-calendar me-1"></i>${formatDMY(hDate)} → ${formatDMY(eDate)}`;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const hInput = document.getElementById('harvest_date');
            const eInput = document.getElementById('expiry_date');
            if (hInput) {
                hInput.addEventListener('change', function() {
                    setExpiryPreset(12);
                    updateShelfLifePreview();
                });
            }
            if (eInput) {
                eInput.addEventListener('change', updateShelfLifePreview);
                eInput.addEventListener('input', updateShelfLifePreview);
            }
            updateShelfLifePreview();
        });
    </script>
@endsection
