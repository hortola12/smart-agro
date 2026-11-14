<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>បញ្ជីរៀបចំទំនិញរួមប្រចាំថ្ងៃ (Daily Packing Manifest) - Grow2Growth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #525659;
            font-family: 'Kantumruy Pro', sans-serif;
            margin: 0;
            padding: 20px 0;
            color: #1e293b;
        }

        .manifest-toolbar {
            max-width: 960px;
            margin: 0 auto 15px auto;
        }

        .manifest-sheet {
            background: #ffffff;
            width: 960px;
            min-height: 1200px;
            margin: 0 auto;
            padding: 40px 45px;
            border-radius: 6px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
            box-sizing: border-box;
        }

        .manifest-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .manifest-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            font-weight: 700;
        }

        .manifest-table td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            vertical-align: middle;
        }

        .checkbox-box {
            width: 18px;
            height: 18px;
            border: 2px solid #64748b;
            border-radius: 4px;
            display: inline-block;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .manifest-toolbar {
                display: none !important;
            }
            .manifest-sheet {
                width: 100% !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 20px !important;
            }
            @page {
                size: A4 portrait;
                margin: 8mm;
            }
        }
    </style>
</head>
<body>

    <!-- Action Toolbar (hidden when printing) -->
    <div class="manifest-toolbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ url('/admin/orders') }}" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold">
                <i class="fa-solid fa-arrow-left me-1"></i> ត្រឡប់ទៅ Orders
            </a>
            <!-- Filter presets -->
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ url('/admin/orders/packing-manifest?status=active') }}" class="btn {{ $status == 'active' ? 'btn-success' : 'btn-light' }} fw-bold">
                    ការកម្ម៉ង់សកម្មទាំងអស់ (Active)
                </a>
                <a href="{{ url('/admin/orders/packing-manifest?status=pending') }}" class="btn {{ $status == 'pending' ? 'btn-success' : 'btn-light' }} fw-bold">
                    រង់ចាំពិនិត្យ (Pending)
                </a>
                <a href="{{ url('/admin/orders/packing-manifest?status=processing') }}" class="btn {{ $status == 'processing' ? 'btn-success' : 'btn-light' }} fw-bold">
                    កំពុងរៀបចំ (Processing)
                </a>
            </div>
        </div>

        <button onclick="window.print()" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">
            <i class="fa-solid fa-print me-1.5"></i> បោះពុម្ពបញ្ជីរួម (Print Manifest)
        </button>
    </div>

    <!-- Printable Manifest Sheet -->
    <div class="manifest-sheet">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <div class="d-flex align-items-center gap-2.5">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-boxes-packing fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark m-0">Grow2Growth Logistics & Warehouse</h5>
                    <small class="text-success fw-bold">បញ្ជីរៀបចំទំនិញ និងចាត់ចែងដឹកជញ្ជូនប្រចាំថ្ងៃ (Daily Packing Manifest)</small>
                </div>
            </div>

            <div class="text-end">
                <div class="fw-bold text-dark font-monospace" style="font-size: 13px;">
                    កាលបរិច្ឆេទ៖ {{ now()->format('d-M-Y H:i') }}
                </div>
                <small class="text-muted">ឃ្លាំងកណ្តាល៖ បាត់ដំបង (Battambang Hub)</small>
            </div>
        </div>

        <!-- 4 Metrics Chips -->
        <div class="row g-2 mb-4 text-center">
            <div class="col-3">
                <div class="p-2 border rounded bg-light">
                    <small class="text-muted d-block" style="font-size: 11px;">ចំនួនវិក្កយបត្រត្រូវខ្ចប់</small>
                    <strong class="fs-5 text-dark font-monospace">{{ $orders->count() }}</strong>
                    <small class="text-muted">កញ្ចប់</small>
                </div>
            </div>
            <div class="col-3">
                <div class="p-2 border rounded bg-light">
                    <small class="text-muted d-block" style="font-size: 11px;">ចំនួនគ្រាប់ពូជសរុប</small>
                    <strong class="fs-5 text-success font-monospace">{{ $totalPacks }}</strong>
                    <small class="text-muted">កញ្ចប់</small>
                </div>
            </div>
            <div class="col-3">
                <div class="p-2 border rounded bg-light">
                    <small class="text-muted d-block" style="font-size: 11px;">មុខគ្រាប់ពូជខុសគ្នា</small>
                    <strong class="fs-5 text-primary font-monospace">{{ count($seedSummary) }}</strong>
                    <small class="text-muted">មុខ</small>
                </div>
            </div>
            <div class="col-3">
                <div class="p-2 border rounded bg-light">
                    <small class="text-muted d-block" style="font-size: 11px;">ទឹកប្រាក់ត្រូវទារ (COD)</small>
                    <strong class="fs-5 text-danger font-monospace">${{ number_format($totalRevenueToCollect, 2) }}</strong>
                </div>
            </div>
        </div>

        <!-- Section 1: Aggregate Seed Pull List for Warehouse Shelf Picking -->
        <div class="mb-4">
            <h6 class="fw-bold text-dark mb-2 d-flex align-items-center" style="font-size: 13.5px;">
                <i class="fa-solid fa-dolly text-success me-1.5"></i>
                ផ្នែកទី ១៖ បញ្ជីគ្រាប់ពូជដែលត្រូវដកចេញពីធ្នើរស្តុក (Warehouse Shelf Pick List)
            </h6>
            <table class="manifest-table mb-1">
                <thead>
                    <tr>
                        <th width="6%" class="text-center">ដកបាន</th>
                        <th width="8%" class="text-center">ល.រ</th>
                        <th>ឈ្មោះគ្រាប់ពូជ (Seed Name)</th>
                        <th width="24%">លេខកូដវគ្គផលិត (Batch No)</th>
                        <th width="18%" class="text-center">ចំនួនត្រូវដក (Packs)</th>
                        <th width="15%" class="text-center">ផ្ទៀងផ្ទាត់</th>
                    </tr>
                </thead>
                <tbody>
                    @php $itemIndex = 1; @endphp
                    @forelse($seedSummary as $seedId => $summary)
                        <tr>
                            <td class="text-center">
                                <span class="checkbox-box"></span>
                            </td>
                            <td class="text-center font-monospace">{{ $itemIndex++ }}</td>
                            <td>
                                <strong>{{ $summary['name'] }}</strong>
                            </td>
                            <td>
                                <span class="font-monospace text-primary">#{{ $summary['batch'] }}</span>
                            </td>
                            <td class="text-center fw-bold text-success font-monospace fs-6">
                                {{ $summary['quantity'] }} កញ្ចប់
                            </td>
                            <td class="text-center text-muted small">
                                [ &nbsp; ] រួចរាល់
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-3 text-muted">
                                គ្មានការកម្ម៉ង់ដែលត្រូវរៀបចំក្នុងពេលនេះឡើយ។
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <small class="text-muted" style="font-size: 10.5px;">* បុគ្គលិកឃ្លាំងត្រូវពិនិត្យមើលលេខបាច់ និងថ្ងៃផុតកំណត់លើកញ្ចប់ឱ្យត្រូវគ្នា មុនយកទៅវេចខ្ចប់។</small>
        </div>

        <!-- Section 2: Detailed Customer Packages List for Delivery Drivers -->
        <div class="mb-4">
            <h6 class="fw-bold text-dark mb-2 d-flex align-items-center" style="font-size: 13.5px;">
                <i class="fa-solid fa-truck text-primary me-1.5"></i>
                ផ្នែកទី ២៖ បញ្ជីវិក្កយបត្រត្រូវវេចខ្ចប់ & ប្រគល់ឱ្យអ្នកដឹកជញ្ជូន (Customer Delivery Packages)
            </h6>
            <table class="manifest-table">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">ខ្ចប់</th>
                        <th width="14%">លេខវិក្កយបត្រ</th>
                        <th width="24%">ព័ត៌មានអតិថិជន</th>
                        <th width="24%">អាសយដ្ឋានដឹកជញ្ជូន</th>
                        <th>គ្រាប់ពូជក្នុងកញ្ចប់</th>
                        <th width="12%" class="text-end">ប្រាក់ COD ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="text-center">
                                <span class="checkbox-box"></span>
                            </td>
                            <td>
                                <strong class="text-success font-monospace">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</strong>
                                <small class="text-muted d-block" style="font-size: 10px;">{{ $order->created_at ? $order->created_at->format('d/m H:i') : '' }}</small>
                            </td>
                            <td>
                                <strong>{{ $order->customer_name }}</strong>
                                <small class="text-muted d-block font-monospace"><i class="fa-solid fa-phone me-1"></i>{{ $order->customer_phone }}</small>
                            </td>
                            <td style="font-size: 11.5px;">
                                {{ $order->delivery_address }}
                            </td>
                            <td style="font-size: 11.5px;">
                                @foreach($order->items as $it)
                                    <div>• {{ $it->seed?->name_kh ?? 'គ្រាប់ពូជ' }} <b>x{{ $it->quantity }}</b></div>
                                @endforeach
                            </td>
                            <td class="text-end fw-bold text-danger font-monospace">
                                ${{ number_format($order->total_price, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-3 text-muted">គ្មានទិន្នន័យការកម្ម៉ង់ឡើយ</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer Sign-off -->
        <div class="row pt-4 border-top text-center" style="font-size: 12px;">
            <div class="col-4">
                <div class="text-muted mb-4">អ្នករៀបចំទំនិញ (Warehouse Picker)</div>
                <div class="border-bottom mx-auto" style="width: 140px;"></div>
                <small class="text-muted mt-1 d-block">ហត្ថលេខា & ឈ្មោះ</small>
            </div>
            <div class="col-4">
                <div class="text-muted mb-4">អ្នកត្រួតពិនិត្យ (Quality Checker)</div>
                <div class="border-bottom mx-auto" style="width: 140px;"></div>
                <small class="text-muted mt-1 d-block">ហត្ថលេខា & ឈ្មោះ</small>
            </div>
            <div class="col-4">
                <div class="text-muted mb-4">អ្នកទទួលដឹកជញ្ជូន (Delivery Driver)</div>
                <div class="border-bottom mx-auto" style="width: 140px;"></div>
                <small class="text-muted mt-1 d-block">ហត្ថលេខា & ឈ្មោះ</small>
            </div>
        </div>
    </div>

</body>
</html>

