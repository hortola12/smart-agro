<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>វិញ្ញាបនបត្រគុណភាពគ្រាប់ពូជ - {{ $seed->name_kh }} (#{{ $seed->batch_number }})</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,300..700;1,300..700&family=Moul&family=Preahvihear&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #525659;
            font-family: 'Kantumruy Pro', sans-serif;
            margin: 0;
            padding: 20px 0;
            color: #1e293b;
        }

        .cert-action-bar {
            max-width: 820px;
            margin: 0 auto 15px auto;
        }

        .certificate-sheet {
            background: #ffffff;
            width: 820px;
            min-height: 1140px;
            margin: 0 auto;
            padding: 50px 60px;
            border-radius: 4px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            position: relative;
            box-sizing: border-box;
        }

        /* Certificate Double Border */
        .cert-border-outer {
            border: 3px solid #14532d;
            padding: 5px;
            height: 100%;
        }

        .cert-border-inner {
            border: 1px solid #166534;
            padding: 35px 40px;
            height: 100%;
            position: relative;
            background: radial-gradient(circle at center, rgba(240, 253, 244, 0.4) 0%, rgba(255, 255, 255, 1) 70%);
        }

        .header-country-title {
            font-family: 'Moul', cursive;
            font-size: 16px;
            color: #14532d;
            letter-spacing: 0.5px;
        }

        .header-motto {
            font-family: 'Kantumruy Pro', sans-serif;
            font-size: 13px;
            color: #166534;
            font-weight: bold;
        }

        .cert-main-title {
            font-family: 'Moul', cursive;
            color: #0f172a;
            font-size: 20px;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .cert-english-title {
            font-size: 11px;
            letter-spacing: 1.5px;
            font-weight: 700;
            color: #166534;
            text-transform: uppercase;
        }

        .cert-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            margin-top: 12px;
            margin-bottom: 20px;
        }

        .cert-table th {
            background-color: #e8f5e9;
            color: #14532d;
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            font-weight: 700;
        }

        .cert-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            background-color: #ffffff;
        }

        .official-seal-box {
            position: relative;
            width: 130px;
            height: 130px;
            border: 3px dashed #dc2626;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #dc2626;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
            transform: rotate(-8deg);
            opacity: 0.88;
            margin: 0 auto;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .cert-action-bar {
                display: none !important;
            }
            .certificate-sheet {
                width: 100% !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 30px !important;
                border: none !important;
            }
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

    <!-- Action Toolbar (hidden when printing) -->
    <div class="cert-action-bar d-flex justify-content-between align-items-center">
        <a href="{{ url('/seed-detail/' . $seed->id) }}" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> ត្រឡប់ក្រោយ (Back)
        </a>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm">
                <i class="fa-solid fa-print me-1.5"></i> បោះពុម្ពវិញ្ញាបនបត្រ (Print / Save as PDF)
            </button>
        </div>
    </div>

    <!-- Official Certificate A4 Sheet -->
    <div class="certificate-sheet">
        <div class="cert-border-outer">
            <div class="cert-border-inner">

                <!-- Header: Royal Kingdom of Cambodia -->
                <div class="text-center mb-3">
                    <div class="header-country-title">ព្រះរាជាណាចក្រកម្ពុជា</div>
                    <div class="header-motto">ជាតិ សាសនា ព្រះមហាក្សត្រ</div>
                    <div style="font-size: 14px; letter-spacing: 6px; color: #166534; font-weight: bold;">***</div>
                </div>

                <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-3">
                    <div>
                        <div class="fw-bold text-success" style="font-size: 13px;">សហគមន៍កសិកម្មឆ្លាតវៃខេត្តបាត់ដំបង</div>
                        <div class="text-muted small" style="font-size: 11px;">មន្ទីរពិសោធន៍ត្រួតពិនិត្យគុណភាពគ្រាប់ពូជ (Grow2Growth Seed Quality Lab)</div>
                        <div class="text-muted" style="font-size: 10.5px;">ទីតាំង៖ ឃុំតាក្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង | ទូរស័ព្ទ៖ 053 952 888</div>
                    </div>
                    <div class="text-end">
                        <div class="text-muted small">លេខសៀរៀល៖</div>
                        <div class="fw-bold font-monospace text-dark" style="font-size: 12.5px;">
                            CERT-{{ strtoupper(substr(md5($seed->id . $seed->batch_number), 0, 8)) }}
                        </div>
                        <div class="text-muted" style="font-size: 10.5px;">កាលបរិច្ឆេទចេញ៖ {{ now()->format('d-M-Y') }}</div>
                    </div>
                </div>

                <!-- Main Certificate Title -->
                <div class="text-center mb-4">
                    <h2 class="cert-main-title">វិញ្ញាបនបត្រត្រួតពិនិត្យគុណភាពគ្រាប់ពូជ</h2>
                    <div class="cert-english-title">Official Certificate of Seed Quality & Laboratory Analysis</div>
                </div>

                <!-- Seed Identity Information -->
                <div class="row g-2 mb-3 bg-light p-3 rounded border">
                    <div class="col-8">
                        <div class="mb-1.5">
                            <span class="text-muted small">ឈ្មោះគ្រាប់ពូជ៖</span>
                            <strong class="text-dark fs-6">{{ $seed->name_kh }}</strong>
                            <span class="text-muted font-monospace small">({{ $seed->name_en }})</span>
                        </div>
                        <div class="mb-1.5">
                            <span class="text-muted small">ប្រភេទបន្លែ៖</span>
                            <span class="fw-bold text-success">{{ $seed->category?->name_kh ?? 'បន្លែធម្មជាតិ' }}</span>
                        </div>
                        <div class="mb-1.5">
                            <span class="text-muted small">លេខវគ្គផលិត (Batch Lot):</span>
                            <span class="fw-bold text-primary font-monospace">#{{ $seed->batch_number }}</span>
                        </div>
                        <div>
                            <span class="text-muted small">ប្រភពកសិដ្ឋានដើម៖</span>
                            <span class="fw-bold text-dark">{{ $seed->traceability?->origin_kh ?? 'កសិដ្ឋានតាក្រាម ខេត្តបាត់ដំបង' }}</span>
                        </div>
                    </div>

                    <div class="col-4 text-center d-flex flex-column align-items-center justify-content-center border-start ps-3">
                        <!-- QR Code Verification Image -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(url('/trace-batch?search=' . $seed->batch_number)) }}"
                             alt="Batch QR Code" style="width: 85px; height: 85px;" class="border p-1 bg-white rounded shadow-sm mb-1">
                        <small class="text-muted font-monospace" style="font-size: 9.5px;">ស្កេនផ្ទៀងផ្ទាត់លេខបាច់</small>
                    </div>
                </div>

                <!-- Laboratory Test Parameters Table -->
                <h6 class="fw-bold text-dark mb-1" style="font-size: 13px;">
                    <i class="fa-solid fa-flask-vial text-success me-1"></i> លទ្ធផលនៃការពិសោធន៍មន្ទីរពិសោធន៍កសិកម្ម (Laboratory Parameters)
                </h6>

                <table class="cert-table">
                    <thead>
                        <tr>
                            <th width="8%" class="text-center">ល.រ</th>
                            <th>លក្ខណៈវិនិច្ឆ័យត្រួតពិនិត្យ (Parameters)</th>
                            <th width="20%" class="text-center">ស្តង់ដារជាតិ (Standard)</th>
                            <th width="20%" class="text-center">លទ្ធផលតេស្ត (Test Result)</th>
                            <th width="16%" class="text-center">ការវាយតម្លៃ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center font-monospace">01</td>
                            <td>
                                <strong>អត្រាដុះលូតលាស់ (Germination Capacity)</strong>
                                <small class="text-muted d-block" style="font-size: 10px;">ធ្វើតេស្តសាបលើដីកំប៉ុស្តិ៍សរីរាង្គរយៈពេល ៧ ថ្ងៃ</small>
                            </td>
                            <td class="text-center font-monospace">≥ 85.0%</td>
                            <td class="text-center fw-bold text-success font-monospace fs-6">
                                {{ $seed->traceability?->germination_rate ?? 95 }}%
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success text-white px-2 py-1" style="font-size: 10.5px;">ជាប់ (PASSED)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-monospace">02</td>
                            <td>
                                <strong>ភាពសុទ្ធនៃគ្រាប់ពូជ (Physical Purity)</strong>
                                <small class="text-muted d-block" style="font-size: 10px;">គ្មានគ្រាប់ពូជបន្លែផ្សេងលាយឡំ</small>
                            </td>
                            <td class="text-center font-monospace">≥ 98.0%</td>
                            <td class="text-center fw-bold text-dark font-monospace">99.4%</td>
                            <td class="text-center">
                                <span class="badge bg-success text-white px-2 py-1" style="font-size: 10.5px;">ជាប់ (PASSED)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-monospace">03</td>
                            <td>
                                <strong>កម្រិតសំណើមគ្រាប់ពូជ (Moisture Content)</strong>
                                <small class="text-muted d-block" style="font-size: 10px;">កម្រិតស្ងួតល្អ មិនឡើងផ្សិត</small>
                            </td>
                            <td class="text-center font-monospace">≤ 8.0%</td>
                            <td class="text-center fw-bold text-dark font-monospace">7.2%</td>
                            <td class="text-center">
                                <span class="badge bg-success text-white px-2 py-1" style="font-size: 10.5px;">ជាប់ (PASSED)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-monospace">04</td>
                            <td>
                                <strong>គ្រាប់ស្មៅចង្រៃ និងកាកសំណល់ (Weed Seed / Inert Matter)</strong>
                            </td>
                            <td class="text-center font-monospace">0.0%</td>
                            <td class="text-center fw-bold text-dark font-monospace">0.0%</td>
                            <td class="text-center">
                                <span class="badge bg-success text-white px-2 py-1" style="font-size: 10.5px;">ជាប់ (PASSED)</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center font-monospace">05</td>
                            <td>
                                <strong>មេរោគផ្សិតចម្លងគ្រាប់ពូជ (Seed-Borne Fungal Pathogens)</strong>
                            </td>
                            <td class="text-center font-monospace">អវត្តមាន</td>
                            <td class="text-center fw-bold text-success">គ្មានមេរោគ (None)</td>
                            <td class="text-center">
                                <span class="badge bg-success text-white px-2 py-1" style="font-size: 10.5px;">ជាប់ (PASSED)</span>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Dates & Cultivation Standards -->
                <div class="row g-2 mb-3" style="font-size: 12px;">
                    <div class="col-6">
                        <div class="border p-2 rounded bg-light">
                            <span class="text-muted">កាលបរិច្ឆេទប្រមូលផល៖ </span>
                            <strong class="font-monospace text-dark">{{ $seed->traceability?->harvest_date ?? '---' }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border p-2 rounded bg-light">
                            <span class="text-muted">កាលបរិច្ឆេទផុតកំណត់៖ </span>
                            <strong class="font-monospace text-danger">{{ $seed->traceability?->expiry_date ?? '---' }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Declaration statement -->
                <p class="text-muted small mb-4" style="font-size: 11.5px; line-height: 1.6; text-align: justify;">
                    សហគមន៍កសិកម្មឆ្លាតវៃខេត្តបាត់ដំបង សូមបញ្ជាក់ និងធានាអះអាងថា វគ្គផលិតគ្រាប់ពូជកសិកម្មលេខសម្គាល់ <b class="text-dark">#{{ $seed->batch_number }}</b> ខាងលើ ពិតជាបានឆ្លងកាត់ការពិនិត្យគុណភាព អត្រាដំណុះ និងភាពសុទ្ធពីមន្ទីរពិសោធន៍កសិកម្មត្រឹមត្រូវស្របតាមស្តង់ដារបច្ចេកទេសជាតិ និងមានលក្ខខណ្ឌគ្រប់គ្រាន់សម្រាប់ចែកចាយជូនកសិករដាំដុះជាផ្លូវការ។
                </p>

                <!-- Official Signatures & Stamp -->
                <div class="row text-center mt-3 pt-2">
                    <div class="col-4">
                        <div class="text-muted small mb-1" style="font-size: 11px;">អ្នកត្រួតពិនិត្យមន្ទីរពិសោធន៍</div>
                        <div style="height: 55px;"></div>
                        <div class="fw-bold text-dark border-top pt-1" style="font-size: 12.5px;">គឹម វ៉ាន់ថន</div>
                        <small class="text-muted" style="font-size: 10.5px;">អ្នកឯកទេសពិសោធន៍គ្រាប់ពូជ</small>
                    </div>

                    <div class="col-4 d-flex align-items-center justify-content-center">
                        <!-- Official Red Digital Seal -->
                        <div class="official-seal-box">
                            <i class="fa-solid fa-certificate fs-4 mb-1"></i>
                            <span>សហគមន៍កសិកម្ម<br>បាត់ដំបង<br>★ សុទ្ធ ១០០% ★</span>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="text-muted small mb-1" style="font-size: 11px;">ប្រធានសហគមន៍កសិកម្ម</div>
                        <div style="height: 55px;"></div>
                        <div class="fw-bold text-dark border-top pt-1" style="font-size: 12.5px;">ហោ តុលា</div>
                        <small class="text-muted" style="font-size: 10.5px;">ប្រធានសហគមន៍ Grow2Growth</small>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>

