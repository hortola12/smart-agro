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
                        <div class="input-group">
                            <span class="input-group-text bg-light text-success border-end-0"><i class="fa-solid fa-percent"></i></span>
                            <input type="number" name="germination_rate" class="form-control font-monospace border-start-0" placeholder="ឧ. 95" min="0" max="100" value="{{ old('germination_rate', 95) }}" required>
                        </div>
                    </div>

                    <!-- 📅 កាលបរិច្ឆេទប្រមូលផល (Harvest Date) -->
                    <div class="col-md-4 mb-2">
                        <label class="form-label fw-bold text-dark d-flex justify-content-between align-items-center mb-1">
                            <span><i class="fa-solid fa-calendar-check text-success me-1.5"></i>កាលបរិច្ឆេទប្រមូលផល <span class="text-danger">*</span></span>
                        </label>
                        <div class="input-group shadow-sm rounded-3 overflow-hidden">
                            <input type="date" name="harvest_date" id="harvest_date" class="form-control font-monospace fw-bold text-dark" value="{{ old('harvest_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="d-flex align-items-center gap-1 mt-1.5 flex-wrap">
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2 text-muted rounded-pill" style="font-size: 11px;" onclick="setHarvestPreset(0)">ថ្ងៃនេះ</button>
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2 text-muted rounded-pill" style="font-size: 11px;" onclick="setHarvestPreset(-1)">ម្សិលមិញ</button>
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2 text-muted rounded-pill" style="font-size: 11px;" onclick="setHarvestPreset(-7)">-៧ ថ្ងៃ</button>
                        </div>
                    </div>

                    <!-- 📅 កាលបរិច្ឆេទផុតកំណត់ (Expiry Date) -->
                    <div class="col-md-4 mb-2">
                        <label class="form-label fw-bold text-dark d-flex justify-content-between align-items-center mb-1">
                            <span><i class="fa-solid fa-calendar-xmark text-danger me-1.5"></i>កាលបរិច្ឆេទផុតកំណត់ <span class="text-danger">*</span></span>
                        </label>
                        <div class="input-group shadow-sm rounded-3 overflow-hidden">
                            <input type="date" name="expiry_date" id="expiry_date" class="form-control font-monospace fw-bold text-dark" value="{{ old('expiry_date', date('Y-m-d', strtotime('+1 year'))) }}" required>
                        </div>
                        <div class="d-flex align-items-center gap-1 mt-1.5 flex-wrap">
                            <button type="button" class="btn btn-sm btn-light border py-0 px-2 text-muted rounded-pill" style="font-size: 11px;" onclick="setExpiryPreset(6)">+៦ ខែ</button>
                            <button type="button" class="btn btn-sm btn-outline-success py-0 px-2 fw-bold rounded-pill" style="font-size: 11px;" onclick="setExpiryPreset(12)">+១ ឆ្នាំ ★</button>
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
                                <i class="fa-regular fa-calendar me-1"></i>{{ date('d/m/Y') }} → {{ date('d/m/Y', strtotime('+1 year')) }}
                            </small>
                        </div>
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
