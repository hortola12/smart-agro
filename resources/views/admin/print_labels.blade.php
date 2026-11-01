<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Seed Labels - {{ $seed->name_kh }} ({{ $seed->batch_number }})</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Kantumruy Pro', sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }

        .no-print {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }

        .sticker-card {
            border: 2px dashed #198754;
            border-radius: 14px;
            background-color: #ffffff;
            padding: 14px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sticker-header {
            border-bottom: 1.5px solid #198754;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }

        @media print {
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .container-print {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .sticker-card {
                border: 1px dashed #94a3b8 !important;
                box-shadow: none !important;
                page-break-inside: avoid;
            }
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

    <!-- Controls Header (Hidden when printing) -->
    <div class="no-print py-3 px-4 shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <a href="{{ url('/admin/seeds') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold me-2">
                    <i class="fa-solid fa-arrow-left me-1"></i> ត្រឡប់ក្រោយ
                </a>
                <span class="fw-bold text-dark fs-6" style="font-family: 'Kantumruy Pro', sans-serif;">
                    <i class="fa-solid fa-tags text-success me-1"></i> សន្លឹកបោះពុម្ពស្លាក QR សម្គាល់កញ្ចប់គ្រាប់ពូជ (Sticker Labels)
                </span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <form action="{{ url('/admin/seeds/print-labels/' . $seed->id) }}" method="GET" class="d-flex align-items-center gap-2 m-0">
                    <label class="small text-muted fw-bold">ចំនួនស្លាក៖</label>
                    <select name="count" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()" style="width: auto;">
                        <option value="4" {{ $count == 4 ? 'selected' : '' }}>៤ ស្លាក</option>
                        <option value="8" {{ $count == 8 ? 'selected' : '' }}>៨ ស្លាក (A4)</option>
                        <option value="12" {{ $count == 12 ? 'selected' : '' }}>១២ ស្លាក</option>
                        <option value="16" {{ $count == 16 ? 'selected' : '' }}>១៦ ស្លាក</option>
                    </select>
                </form>

                <button type="button" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm" onclick="window.print()">
                    <i class="fa-solid fa-print me-1.5"></i> បោះពុម្ពឥឡូវនេះ (Print)
                </button>
            </div>
        </div>
    </div>

    <!-- Printable Grid of Stickers -->
    <div class="container container-print pb-5">
        <div class="row row-cols-2 g-3">
            @php
                $traceUrl = url('/trace?batch_number=' . ($seed->batch_number ?? 'BATCH-' . $seed->id));
            @endphp

            @for($i = 0; $i < $count; $i++)
                <div class="col">
                    <div class="sticker-card">
                        <!-- Top Header -->
                        <div class="sticker-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-seedling text-success fs-5 me-1.5"></i>
                                <div>
                                    <div class="fw-bold text-success" style="font-size: 13px; letter-spacing: 0.3px;">Grow2Growth Agri-Tech</div>
                                    <small class="text-muted d-block" style="font-size: 9.5px;">សហគមន៍កសិកម្មឆ្លាតវៃ ខេត្តបាត់ដំបង</small>
                                </div>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" style="font-size: 10px;">
                                {{ $seed->category?->name_kh ?? 'គ្រាប់ពូជ' }}
                            </span>
                        </div>

                        <!-- Main Body -->
                        <div class="row align-items-center g-2 my-auto">
                            <!-- Left: Details -->
                            <div class="col-8">
                                <h6 class="fw-bold text-dark mb-0 text-truncate" style="font-size: 14px;">{{ $seed->name_kh }}</h6>
                                <small class="text-muted text-truncate d-block mb-2" style="font-size: 11px;">{{ $seed->name_en }}</small>

                                <div class="bg-light p-2 rounded-2 border small" style="font-size: 10.5px; line-height: 1.5;">
                                    <div><strong>លេខបាច់៖</strong> <span class="font-monospace text-primary fw-bold">#{{ $seed->batch_number ?? 'N/A' }}</span></div>
                                    <div><strong>អត្រាដំណុះ៖</strong> <span class="text-success fw-bold">{{ $seed->traceability?->germination_rate ?? 95 }}%</span> (មន្ទីរពិសោធន៍)</div>
                                    <div><strong>កាលបរិច្ឆេទប្រមូលផល៖</strong> {{ $seed->traceability?->harvest_date ?? date('Y-m-d') }}</div>
                                    <div><strong>ផុតកំណត់៖</strong> {{ $seed->traceability?->expiry_date ?? date('Y-m-d', strtotime('+1 year')) }}</div>
                                </div>
                            </div>

                            <!-- Right: QR Code -->
                            <div class="col-4 text-center">
                                <div class="p-1 bg-white border rounded d-inline-block shadow-sm">
                                    {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->margin(1)->color(25, 135, 84)->generate($traceUrl) !!}
                                </div>
                                <small class="text-muted d-block mt-1" style="font-size: 9px; font-weight: bold;">
                                    ស្កេនពិនិត្យប្រភព
                                </small>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="border-top pt-1 mt-2 d-flex justify-content-between align-items-center text-muted" style="font-size: 9px;">
                            <span><i class="fa-solid fa-tractor me-0.5 text-success"></i> {{ $seed->traceability?->farm_name ?? 'កសិដ្ឋានតាក្រាម' }}</span>
                            <span class="font-monospace fw-bold text-dark">${{ number_format($seed->price, 2) }} / កញ្ចប់</span>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

</body>
</html>

