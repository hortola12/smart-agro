@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('css/traceability-search-style.css') }}">
<!-- HTML5 QR Code Library for in-browser camera scanning -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<div class="container py-4">
    <div class="row justify-content-center py-4">
        <div class="col-md-10 col-lg-8">
            <div class="card trace-card p-4 p-md-5 shadow-sm rounded-4 border bg-white position-relative overflow-hidden">

                <div class="trace-bg-circle circle-1"></div>
                <div class="trace-bg-circle circle-2"></div>

                <div class="position-relative text-center" style="z-index: 2;">

                    <div class="trace-icon-box mb-3 shadow bg-success text-white mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 70px; height: 70px;">
                        <i class="fa-solid fa-location-dot fs-3"></i>
                    </div>

                    <h2 class="fw-bold text-dark mb-2 trace-title" style="font-family: 'Kantumruy Pro', sans-serif;">
                        {{ __('menu_traceability') ?? 'តាមដានប្រភពដើមគ្រាប់ពូជ' }}
                    </h2>

                    <p class="text-muted trace-desc px-md-5 mb-4 small">
                        ស្វាគមន៍មកកាន់ប្រព័ន្ធធានាគុណភាព និងខ្សែសង្វាក់កសិកម្មវៃឆ្លាត
                        <b class="text-success">Grow2Growth</b> 🌱 <br>
                        ស្កេន QR Code នៅលើកញ្ចប់គ្រាប់ពូជ ឬវាយលេខកូដវគ្គផលិត (Batch Number) ដើម្បីពិនិត្យប្រភពកសិដ្ឋានដើម អត្រាដុះលូតលាស់ និងការណែនាំបច្ចេកទេស។
                    </p>

                    @if($errors->has('search'))
                        <div class="alert alert-danger border-0 shadow-sm rounded-pill py-2 px-4 mb-4 mx-md-5 small fw-bold text-danger d-flex align-items-center justify-content-center">
                            <i class="fa-solid fa-circle-exclamation me-2"></i>
                            <span>{{ $errors->first('search') }}</span>
                        </div>
                    @endif

                    <!-- Manual Search Form -->
                    <form action="{{ url('/trace-batch') }}"
                          method="GET"
                          id="traceSearchForm"
                          class="search-hub-wrapper rounded-pill p-2 mb-3 mx-md-5 shadow-sm border border-success bg-white d-flex align-items-center">

                        <div class="input-group align-items-center">
                            <span class="input-group-text bg-white border-0 text-success ps-3">
                                <i class="fa-solid fa-barcode fs-5"></i>
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

                    <!-- 📷 Real In-Browser Camera Scanner Button -->
                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                        <button type="button"
                                class="btn btn-outline-success fw-bold rounded-pill px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2"
                                data-bs-toggle="modal"
                                data-bs-target="#qrScannerModal">
                            <i class="fa-solid fa-camera fs-5 text-success"></i>
                            <span>ស្កេន QR Code តាមកាមេរ៉ា (Scan with Camera)</span>
                        </button>
                    </div>

                    <!-- Sample Batch Codes for quick testing -->
                    <div class="mt-2">
                        <span class="text-muted small d-block mb-2 fw-bold">
                            <i class="fa-solid fa-lightbulb text-warning me-1"></i>
                            វគ្គផលិតគំរូសម្រាប់ចុចសាកល្បងភ្លាមៗ៖
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

<!-- 📷 QR CODE CAMERA SCANNER MODAL -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-qrcode fs-5"></i>
                    <h5 class="modal-title fw-bold mb-0" id="qrScannerModalLabel" style="font-family: 'Kantumruy Pro', sans-serif;">
                        ស្កេន QR Code លើកញ្ចប់គ្រាប់ពូជ
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="text-muted small mb-3">
                    សូមតម្រង់កាមេរ៉ាទូរស័ព្ទ ឬកុំព្យូទ័ររបស់អ្នក ឱ្យចំប្រអប់ QR Code នៅលើសំបកកញ្ចប់គ្រាប់ពូជ។
                </p>

                <!-- Camera Viewport Box -->
                <div class="position-relative mx-auto rounded-4 overflow-hidden border border-2 border-success bg-dark"
                     style="max-width: 360px; min-height: 280px; box-shadow: inset 0 0 20px rgba(0,0,0,0.5);">
                    <div id="qr-reader" style="width: 100%;"></div>
                    <div id="qr-scan-placeholder" class="d-flex flex-column align-items-center justify-content-center text-white py-5">
                        <div class="spinner-border text-success mb-3" role="status"></div>
                        <span class="small">កំពុងបើកដំណើរការកាមេរ៉ា...</span>
                    </div>
                </div>

                <div id="qrScannerFeedback" class="alert alert-info border-0 rounded-pill py-2 px-3 mt-3 small mb-3 fw-bold d-none">
                    <i class="fa-solid fa-circle-check me-1.5 text-success"></i>
                    <span id="qrScannerFeedbackText"></span>
                </div>

                <!-- Fallback: Upload QR Code Image -->
                <div class="mt-3 pt-3 border-top">
                    <label for="qrImageFileInput" class="form-label small text-muted fw-bold d-block mb-2">
                        <i class="fa-solid fa-image me-1 text-primary"></i>
                        ឬ ជ្រើសរើសរូបថត QR Code ពីឧបករណ៍របស់អ្នក៖
                    </label>
                    <input type="file" id="qrImageFileInput" accept="image/*" class="form-control form-control-sm rounded-pill text-muted">
                </div>
            </div>
            <div class="modal-footer bg-light border-0 py-2.5 px-4 justify-content-between">
                <span class="text-muted small" style="font-size: 11px;">
                    <i class="fa-solid fa-shield-halved text-success me-1"></i> កាមេរ៉ាដំណើរការក្នុង Browser របស់អ្នកផ្ទាល់
                </span>
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3 fw-bold" data-bs-dismiss="modal">
                    បិទផ្ទាំង
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function fillSampleCode(code){
        document.getElementById('batchSearchInput').value = code;
    }

    let html5QrCode = null;

    function playSuccessBeep() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(800, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(1200, ctx.currentTime + 0.15);
            gain.gain.setValueAtTime(0.3, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.15);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.16);
        } catch (e) {
            // AudioContext not allowed or supported
        }
    }

    function handleDecodedBatch(decodedText) {
        playSuccessBeep();
        
        let batchCode = decodedText.trim();

        // ប្រសិនបើសារដែលស្កេនបានជា URL (ឧ. https://domain.kh/trace-batch?search=BATCH-2026-LT03 ឬ /trace?batch_number=...)
        if (batchCode.includes('http://') || batchCode.includes('https://') || batchCode.includes('/trace')) {
            try {
                const url = new URL(batchCode, window.location.origin);
                const searchParam = url.searchParams.get('search') || url.searchParams.get('batch_number');
                if (searchParam) {
                    batchCode = searchParam;
                } else {
                    // Redirect directly to the scanned URL
                    window.location.href = batchCode;
                    return;
                }
            } catch (err) {
                // If parsing URL fails, keep batchCode as-is
            }
        }

        const feedbackEl = document.getElementById('qrScannerFeedback');
        const feedbackText = document.getElementById('qrScannerFeedbackText');
        feedbackText.textContent = 'ស្កេនបានជោគជ័យ៖ ' + batchCode;
        feedbackEl.classList.remove('d-none');

        // បញ្ចូលលេខកូដក្នុងប្រអប់ស្វែងរក និងបញ្ជូន Form ភ្លាមៗ
        document.getElementById('batchSearchInput').value = batchCode;

        setTimeout(() => {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    const modalEl = document.getElementById('qrScannerModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    document.getElementById('traceSearchForm').submit();
                }).catch(() => {
                    document.getElementById('traceSearchForm').submit();
                });
            } else {
                document.getElementById('traceSearchForm').submit();
            }
        }, 600);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('qrScannerModal');

        modalEl.addEventListener('shown.bs.modal', function () {
            const placeholder = document.getElementById('qr-scan-placeholder');
            if (placeholder) placeholder.style.display = 'flex';

            html5QrCode = new Html5Qrcode("qr-reader");

            const config = {
                fps: 15,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText, decodedResult) => {
                    handleDecodedBatch(decodedText);
                },
                (errorMessage) => {
                    // Scan in-progress frame error - ignore
                }
            ).then(() => {
                if (placeholder) placeholder.style.display = 'none';
            }).catch((err) => {
                if (placeholder) {
                    placeholder.innerHTML = `
                        <i class="fa-solid fa-triangle-exclamation text-warning fs-3 mb-2"></i>
                        <span class="small text-danger fw-bold">មិនអាចបើកកាមេរ៉ាបានទេ</span>
                        <span class="text-white-50 small mt-1 px-3">សូមអនុញ្ញាត Camera Permission ឬជ្រើសរើសរូបភាពខាងក្រោម</span>
                    `;
                }
            });
        });

        modalEl.addEventListener('hidden.bs.modal', function () {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                }).catch(() => {});
            }
            const feedbackEl = document.getElementById('qrScannerFeedback');
            if (feedbackEl) feedbackEl.classList.add('d-none');
            const fileInput = document.getElementById('qrImageFileInput');
            if (fileInput) fileInput.value = '';
        });

        // Scan from uploaded file
        const fileInput = document.getElementById('qrImageFileInput');
        fileInput.addEventListener('change', function (e) {
            if (e.target.files.length === 0) return;
            const file = e.target.files[0];
            const reader = new Html5Qrcode("qr-reader");
            reader.scanFile(file, true)
                .then(decodedText => {
                    handleDecodedBatch(decodedText);
                })
                .catch(err => {
                    alert('មិនអាចរកឃើញ QR Code ក្នុងរូបភាពនេះឡើយ សូមសាកល្បងរូបភាពផ្សេងទៀត។');
                });
        });
    });
</script>

@endsection
