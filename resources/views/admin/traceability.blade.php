@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

@section('admin_content')
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <h6 class="fw-bold text-danger mb-2"><i class="fa-solid fa-triangle-exclamation me-2"></i>មិនអាចរក្សាទុកបានទេ! មានកំហុសបច្ចេកទេស៖</h6>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li class="fw-bold">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">គ្រប់គ្រងទិន្នន័យប្រភពដើម (Traceability)</h4>
        <p class="text-muted small mb-0">ពិនិត្យមើល កែប្រែ និងលុបព័ត៌មានកូដវគ្គផលិត (Batch) នៃខ្សែសង្វាក់គ្រាប់ពូជវៃឆ្លាត</p>
    </div>

    <div class="card admin-main-card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle admin-table admin-dashboard-table mb-0" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th class="ps-4">លេខ Batch</th>
                            <th>គ្រាប់ពូជ</th>
                            <th>ប្រភពកសិដ្ឋាន (ទីតាំង)</th>
                            <th>ព័ត៌មានកសិកម្មលម្អិត</th>
                            <th>អត្រាដុះលូតលាស់</th>
                            <th>កាលបរិច្ឆេទប្រមូលផល / ផុតកំណត់</th>
                            <th class="text-center" width="12%">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($traceabilities as $trace)
                        <tr>
                            <td class="ps-4 fw-bold text-success font-monospace">#{{ $trace->batch_number }}</td>

                            <td class="fw-bold text-dark">{{ $trace->seed?->name_kh ?? 'គ្រាប់ពូជត្រូវបានលុប' }}</td>

                            <td class="text-secondary small">{{ $trace->origin_kh }}</td>

                            <td>
                                <a href="{{ url('/admin/traceability/show/' . $trace->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold shadow-sm" style="font-size: 12px;">
                                    <i class="fa-solid fa-eye me-1"></i> មើលព័ត៌មានកសិកម្មលម្អិត
                                </a>
                            </td>

                            <td>
                                <div class="d-flex align-items-center" style="max-width: 160px;">
                                    <div class="w-100 me-2">
                                        <div class="progress" style="height: 6px; background-color: #e9ecef;">
                                            <div class="progress-bar bg-success rounded" role="progressbar" style="width: {{ $trace->germination_rate }}%;" aria-valuenow="{{ $trace->germination_rate }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-success small font-monospace">{{ $trace->germination_rate }}%</span>
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 10px;"><i class="fa-solid fa-seedling text-success me-1"></i>Growth Potential</small>
                            </td>

                            <td class="small">
                                <span class="d-block text-muted"><b>ប្រមូលផល៖</b> {{ $trace->harvest_date }}</span>
                                <span class="d-block text-muted"><b>ផុតកំណត់៖</b> {{ $trace->expiry_date }}</span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <button type="button" class="admin-action-btn admin-action-btn-edit text-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#editTraceModal-{{ $trace->id }}" title="កែប្រែព័ត៌មាន">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <form action="{{ url('/admin/traceability/delete/' . $trace->id) }}" method="POST" id="delete-trace-form-{{ $trace->id }}" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="admin-action-btn admin-action-btn-delete shadow-sm btn-delete-trace" data-id="{{ $trace->id }}" title="លុបប្រភពដើម">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="p-3 bg-light rounded-circle d-inline-flex mb-2 text-muted">
                                    <i class="fa-solid fa-map-location-dot display-6"></i>
                                </div>
                                <h6 class="fw-bold text-secondary">មិនទាន់មានទិន្នន័យប្រភពដើមណាមួយត្រូវបានបញ្ចូលនៅឡើយទេ</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach($traceabilities as $trace)
        <div class="modal fade admin-modal-custom" id="editTraceModal-{{ $trace->id }}" tabindex="-1" aria-labelledby="editTraceModalLabel-{{ $trace->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-sm">
                    <div class="modal-header text-white py-3 bg-success">
                        <h5 class="modal-title fw-bold fs-6" id="editTraceModalLabel-{{ $trace->id }}" style="font-family: 'Kantumruy Pro', sans-serif;">
                            <i class="fa-solid fa-pen-to-square me-2"></i>កែប្រែព័ត៌មានវគ្គផលិត ({{ $trace->batch_number }})
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ url('/admin/traceability/update/' . $trace->id) }}" method="POST" class="m-0">
                        @csrf
                        @method('PUT')

                        <div class="modal-body text-start p-4">
                            <div class="row g-3">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold">លេខ Batch <span class="text-danger">*</span></label>
                                    <input type="text" name="batch_number" class="form-control font-monospace @error('batch_number') is-invalid @enderror" value="{{ old('batch_number', $trace->batch_number) }}" required>
                                    @error('batch_number')
                                        <div class="invalid-feedback fw-bold small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold">ប្រភពកសិដ្ឋាន / ទីតាំងដាំដុះ (Origin Location) <span class="text-danger">*</span></label>
                                    <input type="text" name="origin_kh" class="form-control @error('origin_kh') is-invalid @enderror" placeholder="ឧទាហរណ៍៖ ឃុំតាក្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង" value="{{ old('origin_kh', $trace->origin_kh) }}" required>
                                    @error('origin_kh')
                                        <div class="invalid-feedback fw-bold small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold">អត្រាដុះលូតលាស់ (%) <span class="text-danger">*</span></label>
                                    <input type="number" name="germination_rate" class="form-control font-monospace @error('germination_rate') is-invalid @enderror" value="{{ old('germination_rate', $trace->germination_rate) }}" min="0" max="100" step="0.01" required>
                                    @error('germination_rate')
                                        <div class="invalid-feedback fw-bold small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="watering_schedule" class="form-label small fw-bold">របបស្រោចទឹក (Watering Schedule)</label>
                                    <input type="text" name="watering_schedule" id="watering_schedule" class="form-control"
                                        placeholder="ឧទាហរណ៍៖ ស្រោចទឹករាល់ព្រឹក (១ដងក្នុងមួយថ្ងៃ)"
                                        value="{{ old('watering_schedule', $trace->watering_schedule ?? 'ស្រោចទឹករាល់ព្រឹក') }}">
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold">កាលបរិច្ឆេទប្រមូលផល <span class="text-danger">*</span></label>
                                    <input type="text" name="harvest_date" class="form-control @error('harvest_date') is-invalid @enderror" placeholder="ឧ. 2026-05-29 ឬ ចុងខែមករា" value="{{ old('harvest_date', $trace->harvest_date) }}" required>
                                    @error('harvest_date')
                                        <div class="invalid-feedback fw-bold small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label class="form-label small fw-bold">កាលបរិច្ឆេទផុតកំណត់ <span class="text-danger">*</span></label>
                                    <input type="text" name="expiry_date" class="form-control @error('expiry_date') is-invalid @enderror" placeholder="ឧ. 2027-05-29 ឬ រយៈពេល ១ឆ្នាំ" value="{{ old('expiry_date', $trace->expiry_date) }}" required>
                                    @error('expiry_date')
                                        <div class="invalid-feedback fw-bold small">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="soil_ph" class="form-label small fw-bold">កម្រិត pH ដីសមស្រប (Soil pH)</label>
                                    <input type="text" name="soil_ph" id="soil_ph" class="form-control font-monospace"
                                        placeholder="ឧទាហរណ៍៖ 6.0 - 6.8"
                                        value="{{ old('soil_ph', $trace->soil_ph ?? '6.0 - 6.8') }}">
                                </div>

                                <div class="col-md-6 mb-2">
                                    <label for="farm_name" class="form-label small fw-bold">ឈ្មោះកសិដ្ឋានផលិត (Source Farm Name)</label>
                                    <input type="text" name="farm_name" id="farm_name" class="form-control"
                                        placeholder="ឧទាហរណ៍៖ សហគមន៍កសិករតាគ្រាម ឬ កសិដ្ឋានបាត់ដំបងហ្គ្រីន"
                                        value="{{ old('farm_name', $trace->farm_name ?? 'កសិដ្ឋានតាក្រាម') }}">
                                </div>

                                <div class="col-12 mb-2">
                                    <label for="cultivation_guide" class="form-label small fw-bold">សៀវភៅណែនាំបច្ចេកទេស និងវិធីសាប (Cultivation Guide)</label>
                                    <textarea name="cultivation_guide" id="cultivation_guide" class="form-control" rows="4"
                                            placeholder="រៀបរាប់ពីបច្គេកទេសដាំដុះ...">{{ old('cultivation_guide', $trace->cultivation_guide ?? "១. ត្រូវត្រាំគ្រាប់ពូជក្នុងទឹកក្តៅឧណ្ហៗ រយៈពេល ២ម៉ោង មុនសាប\n២. រក្សាសំណើមដីឱ្យបានល្អ។") }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light py-3">
                            <button type="button" class="btn btn-light border fw-bold rounded-pill px-3 text-secondary small" data-bs-dismiss="modal" style="font-size: 12px;">បោះបង់</button>
                            <button type="submit" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm small" style="font-size: 12px;">
                                <i class="fa-solid fa-floppy-disk me-1"></i> រក្សាទុកការផ្លាស់ប្ដូរ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var successMessage = "{{ session('success') }}";
            if (successMessage && successMessage.trim() !== "") {
                Swal.fire({
                    icon: 'success',
                    title: 'ជោគជ័យ!',
                    text: successMessage,
                    showConfirmButton: false,
                    timer: 2500
                });
            }

            document.querySelectorAll('.btn-delete-trace').forEach(button => {
                button.addEventListener('click', function() {
                    var traceId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'តើអ្នកប្រាកដទេ?',
                        text: "ព័ត៌មានវគ្គផលិត (Batch) នេះនឹងត្រូវលុបបាត់បង់ទាំងស្រុងពីប្រព័ន្ធ!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> យល់ព្រមលុប',
                        cancelButtonText: 'បោះបង់'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var deleteForm = document.getElementById('delete-trace-form-' + traceId);
                            if (deleteForm) deleteForm.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
