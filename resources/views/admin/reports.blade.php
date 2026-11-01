@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

<style>
@media print {
    /* Hide layout sidebars, navbars, and buttons */
    .sidebar, .admin-navbar, .no-print, .btn, .filter-card {
        display: none !important;
    }
    .main-content {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }
    .print-only {
        display: block !important;
    }
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
        break-inside: avoid;
    }
    body {
        font-size: 12px !important;
        background: #fff !important;
        color: #000 !important;
    }
}
.print-only {
    display: none;
}
</style>

@section('admin_content')
    <!-- 🖨️ OFFICIAL PRINT LETTERHEAD (ONLY SHOWN WHEN PRINTING) -->
    <div class="print-only mb-4 text-center">
        <h5 class="fw-bold mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">ព្រះរាជាណាចក្រកម្ពុជា</h5>
        <h6 class="fw-bold mb-3" style="font-family: 'Kantumruy Pro', sans-serif;">ជាតិ សាសនា ព្រះមហាក្សត្រ</h6>
        <div class="d-flex align-items-center justify-content-center mb-2">
            <i class="fa-solid fa-seedling text-success fs-3 me-2"></i>
            <h4 class="fw-bold text-success m-0" style="font-family: 'Kantumruy Pro', sans-serif;">សហគមន៍កសិកម្មសុវត្ថិភាពតាក្រាម (Grow2Growth)</h4>
        </div>
        <p class="small text-muted mb-1">ភូមិតាក្រាម ឃុំតាក្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង · ទូរស័ព្ទ៖ 088 3322 318</p>
        <hr class="border-dark my-3">
        <h5 class="fw-bold text-dark text-uppercase" style="font-family: 'Kantumruy Pro', sans-serif;">របាយការណ៍លទ្ធផលលក់គ្រាប់ពូជ និងហិរញ្ញវត្ថុ</h5>
        <p class="small text-secondary mb-3">កាលបរិច្ឆេទគិតត្រឹម៖ <strong>{{ $filterLabel }}</strong> | ថ្ងៃបោះពុម្ព៖ {{ date('d/m/Y H:i') }}</p>
    </div>

    <!-- Screen Header (Hidden on Print) -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 no-print">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">
                <i class="fa-solid fa-chart-line text-success me-2"></i>របាយការណ៍ហិរញ្ញវត្ថុ & ស្ថិតិលក់ (Reports & Analytics)
            </h4>
            <p class="text-muted small mb-0">
                ស្ថិតិបំបែកតាមមុខដំណាំ ចំណូលសរុប គ្រាប់ពូជលក់ដាច់ និងការបញ្ចុះតម្លៃ <strong>{{ $filterLabel }}</strong>
            </p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" onclick="window.print()" class="btn btn-outline-dark fw-bold rounded-pill px-3 shadow-sm">
                <i class="fa-solid fa-print me-1.5"></i> បោះពុម្ពរបាយការណ៍ A4
            </button>
            <a href="{{ route('admin.reports.export', ['date_filter' => $dateFilter]) }}" class="btn btn-outline-success fw-bold rounded-pill px-3 shadow-sm">
                <i class="fa-solid fa-file-csv me-1.5"></i> ទាញយក CSV
            </a>
        </div>
    </div>

    <!-- 🔍 FILTER TOOLBAR (Hidden on Print) -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white filter-card no-print">
        <form action="{{ url('/admin/reports') }}" method="GET" class="row g-2 align-items-center m-0">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted mb-1">ជ្រើសរើសចន្លោះពេល</label>
                <select name="date_filter" id="dateFilterSelect" class="form-select form-select-sm bg-light" onchange="toggleCustomDates(this.value)">
                    <option value="all_time" {{ $dateFilter === 'all_time' ? 'selected' : '' }}>-- គ្រប់កាលបរិច្ឆេទទាំងអស់ --</option>
                    <option value="today" {{ $dateFilter === 'today' ? 'selected' : '' }}>ថ្ងៃនេះ (Today)</option>
                    <option value="this_week" {{ $dateFilter === 'this_week' ? 'selected' : '' }}>សប្តាហ៍នេះ (This Week)</option>
                    <option value="this_month" {{ $dateFilter === 'this_month' ? 'selected' : '' }}>ខែនេះ (This Month)</option>
                    <option value="last_month" {{ $dateFilter === 'last_month' ? 'selected' : '' }}>ខែមុន (Last Month)</option>
                    <option value="this_year" {{ $dateFilter === 'this_year' ? 'selected' : '' }}>ឆ្នាំនេះ (This Year)</option>
                    <option value="custom" {{ $dateFilter === 'custom' ? 'selected' : '' }}>ចន្លោះកាលបរិច្ឆេទផ្ទាល់ខ្លួន (Custom Range)...</option>
                </select>
            </div>
            <div class="col-md-3 {{ $dateFilter === 'custom' ? '' : 'd-none' }}" id="fromDateCol">
                <label class="form-label small fw-bold text-muted mb-1">ចាប់ពីថ្ងៃ</label>
                <input type="date" name="from_date" class="form-control form-control-sm bg-light" value="{{ $fromDate }}">
            </div>
            <div class="col-md-3 {{ $dateFilter === 'custom' ? '' : 'd-none' }}" id="toDateCol">
                <label class="form-label small fw-bold text-muted mb-1">ដល់ថ្ងៃ</label>
                <input type="date" name="to_date" class="form-control form-control-sm bg-light" value="{{ $toDate }}">
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2 mt-auto pt-3">
                <button type="submit" class="btn btn-success btn-sm fw-bold rounded-pill px-3 w-100">
                    <i class="fa-solid fa-filter me-1"></i> អនុវត្តតម្រង
                </button>
                @if($dateFilter !== 'all_time')
                    <a href="{{ url('/admin/reports') }}" class="btn btn-light btn-sm border fw-bold rounded-pill px-2.5" title="លុប Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 📊 CORE KPI METRIC CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-success">
                <small class="text-muted fw-bold d-block mb-1">ចំណូលសរុប (Total Revenue)</small>
                <h3 class="fw-bold text-success mb-1 font-monospace">${{ number_format($totalRevenue, 2) }}</h3>
                <small class="text-muted font-monospace" style="font-size: 11px;">≈ {{ number_format($totalRevenue * 4100) }} ៛</small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-primary">
                <small class="text-muted fw-bold d-block mb-1">គ្រាប់ពូជលក់បាន (Packs Sold)</small>
                <h3 class="fw-bold text-primary mb-1 font-monospace">{{ number_format($totalPacksSold) }} <span class="fs-6 text-muted">កញ្ចប់</span></h3>
                <small class="text-muted" style="font-size: 11px;">គ្រប់មុខដំណាំទាំងអស់</small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-warning">
                <small class="text-muted fw-bold d-block mb-1">ការកម្ម៉ង់ទិញសរុប (Orders)</small>
                <h3 class="fw-bold text-dark mb-1 font-monospace">{{ $totalOrdersCount }} <span class="fs-6 text-muted">លើក</span></h3>
                <small class="text-success fw-bold" style="font-size: 11px;">
                    ជោគជ័យ {{ $completedOrdersCount }} · រង់ចាំ {{ $pendingOrdersCount }}
                </small>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-danger">
                <small class="text-muted fw-bold d-block mb-1">បញ្ចុះតម្លៃសរុប (Discounts)</small>
                <h3 class="fw-bold text-danger mb-1 font-monospace">${{ number_format($totalDiscounts, 2) }}</h3>
                <small class="text-muted font-monospace" style="font-size: 11px;">មធ្យម/Order: ${{ number_format($averageOrderValue, 2) }}</small>
            </div>
        </div>
    </div>

    <!-- 🥦 CATEGORY BREAKDOWN TABLE -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark m-0 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                <i class="fa-solid fa-tags text-success me-2"></i>ចំណូលបំបែកតាមប្រភេទបន្លែ (Sales by Vegetable Category)
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small fw-bold">
                    <tr>
                        <th class="ps-4">ប្រភេទបន្លែ</th>
                        <th class="text-center">ចំនួនលក់ដាច់ (កញ្ចប់)</th>
                        <th class="text-center">ចំណូលទទួលបាន ($)</th>
                        <th class="text-end pe-4">ចំណែកទីផ្សារ (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorySales as $cSale)
                        @php
                            $catShare = $totalRevenue > 0 ? round(($cSale->total_revenue / $totalRevenue) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td class="ps-4 fw-bold text-dark">
                                <span class="badge bg-success bg-opacity-10 text-success me-2">
                                    <i class="fa-solid fa-leaf"></i>
                                </span>
                                {{ $cSale->name_kh }}
                                <small class="text-muted font-monospace ms-1">({{ $cSale->name_en }})</small>
                            </td>
                            <td class="text-center font-monospace fw-bold">{{ number_format($cSale->packs_sold) }} កញ្ចប់</td>
                            <td class="text-center font-monospace fw-bold text-success">${{ number_format($cSale->total_revenue, 2) }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <div class="progress flex-grow-1 d-none d-sm-block" style="height: 6px; max-width: 80px;">
                                        <div class="progress-bar bg-success rounded" role="progressbar" style="width: {{ $catShare }}%;"></div>
                                    </div>
                                    <span class="font-monospace fw-bold small text-muted">{{ $catShare }}%</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                មិនទាន់មានទិន្នន័យលក់ក្នុងចន្លោះកាលបរិច្ឆេទនេះឡើយ
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 🌾 SEED PERFORMANCE TABLE -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-5">
        <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark m-0 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                <i class="fa-solid fa-trophy text-warning me-2"></i>លទ្ធផលលក់តាមមុខគ្រាប់ពូជ (Product Performance Ranking)
            </h6>
            <small class="text-muted">តម្រៀបតាមចំនួនកញ្ចប់លក់ដាច់ច្រើនបំផុត</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small fw-bold">
                    <tr>
                        <th class="ps-4" width="8%">ចំណាត់ថ្នាក់</th>
                        <th>ឈ្មោះគ្រាប់ពូជ & លេខបាច់</th>
                        <th class="text-center">តម្លៃឯកតា</th>
                        <th class="text-center">ស្តុកនៅសល់</th>
                        <th class="text-center">ចំនួនលក់ដាច់</th>
                        <th class="text-end pe-4">ចំណូលសរុប</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seedSales as $idx => $sSale)
                        <tr>
                            <td class="ps-4 font-monospace fw-bold">
                                @if($idx === 0)
                                    <span class="badge bg-warning text-dark rounded-circle p-2" style="width: 26px; height: 26px;">1</span>
                                @elseif($idx === 1)
                                    <span class="badge bg-secondary text-white rounded-circle p-2" style="width: 26px; height: 26px;">2</span>
                                @elseif($idx === 2)
                                    <span class="badge bg-danger bg-opacity-75 text-white rounded-circle p-2" style="width: 26px; height: 26px;">3</span>
                                @else
                                    <span class="text-muted ms-1.5">{{ $idx + 1 }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-dark small d-block" style="font-family: 'Kantumruy Pro', sans-serif;">
                                    {{ $sSale->name_kh }}
                                </span>
                                <small class="text-muted font-monospace" style="font-size: 11px;">
                                    {{ $sSale->name_en }} · <span class="text-primary">#{{ $sSale->batch_number }}</span>
                                </small>
                            </td>
                            <td class="text-center font-monospace">${{ number_format($sSale->price, 2) }}</td>
                            <td class="text-center">
                                @if($sSale->stock > 5)
                                    <span class="badge bg-success bg-opacity-10 text-success font-monospace">{{ $sSale->stock }}</span>
                                @elseif($sSale->stock > 0)
                                    <span class="badge bg-warning bg-opacity-25 text-dark font-monospace">{{ $sSale->stock }}</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger font-monospace">0</span>
                                @endif
                            </td>
                            <td class="text-center font-monospace fw-bold fs-6 text-primary">
                                {{ number_format($sSale->packs_sold) }} <small class="text-muted fw-normal" style="font-size: 11px;">កញ្ចប់</small>
                            </td>
                            <td class="text-end pe-4 font-monospace fw-bold fs-6 text-success">
                                ${{ number_format($sSale->total_revenue, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                មិនទាន់មានទិន្នន័យលក់ក្នុងចន្លោះពេលនេះឡើយ
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ✍️ PRINT SIGNATURE BLOCK (ONLY SHOWN IN PRINT MODE) -->
    <div class="print-only mt-5 pt-4">
        <div class="row text-center">
            <div class="col-6">
                <p class="mb-1 fw-bold">គណនេយ្យករ / បេឡាធិការ</p>
                <small class="text-muted d-block mb-5">(ហត្ថលេខា និងឈ្មោះ)</small>
                <p class="mt-5 border-top border-dark d-inline-block px-4">កាលបរិច្ឆេទ៖ ....../....../2026</p>
            </div>
            <div class="col-6">
                <p class="mb-1 fw-bold">ប្រធានគណៈកម្មការសហគមន៍កសិកម្ម</p>
                <small class="text-muted d-block mb-5">(ហត្ថលេខា និងត្រា)</small>
                <p class="mt-5 border-top border-dark d-inline-block px-4">កាលបរិច្ឆេទ៖ ....../....../2026</p>
            </div>
        </div>
    </div>

    <script>
    function toggleCustomDates(val) {
        const fromCol = document.getElementById('fromDateCol');
        const toCol = document.getElementById('toDateCol');
        if (val === 'custom') {
            fromCol.classList.remove('d-none');
            toCol.classList.remove('d-none');
        } else {
            fromCol.classList.add('d-none');
            toCol.classList.add('d-none');
        }
    }
    </script>
@endsection

