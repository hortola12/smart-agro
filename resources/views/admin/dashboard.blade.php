@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

@section('admin_content')

<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="fa-solid fa-circle-check fs-5 me-2 text-success"></i>
            <span class="fw-bold text-success">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Top Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">
                <i class="fa-solid fa-chart-pie text-success me-2"></i>
                ផ្ទាំងគ្រប់គ្រងទូទៅ (Admin Dashboard)
            </h4>
            <p class="text-muted small mb-0">
                ស្ថិតិអាជីវកម្ម ស្ថានភាពការកម្ម៉ង់ និងការគ្រប់គ្រងស្តុកគ្រាប់ពូជ Grow2Growth
            </p>
        </div>

        <!-- Quick Action Shortcuts -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ url('/admin/seeds/create') }}" class="btn btn-success btn-sm fw-bold rounded-pill px-3 shadow-sm">
                <i class="fa-solid fa-plus me-1"></i> បន្ថែមគ្រាប់ពូជ
            </a>
            <a href="{{ url('/admin/categories') }}" class="btn btn-light border btn-sm fw-bold rounded-pill px-3 shadow-sm text-dark">
                <i class="fa-solid fa-tags text-primary me-1"></i> ប្រភេទបន្លែ
            </a>
            <a href="{{ url('/admin/orders/export') }}" class="btn btn-outline-success btn-sm fw-bold rounded-pill px-3 shadow-sm" title="ទាញយករបាយការណ៍លក់">
                <i class="fa-solid fa-file-csv me-1"></i> CSV កម្ម៉ង់
            </a>
            <a href="{{ url('/admin/seeds/export') }}" class="btn btn-outline-primary btn-sm fw-bold rounded-pill px-3 shadow-sm" title="ទាញយកទិន្នន័យស្តុក">
                <i class="fa-solid fa-file-csv me-1"></i> CSV ស្តុក
            </a>
        </div>
    </div>

    <!-- 4 Core KPI Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card admin-stat-card shadow-sm border-0 h-100 rounded-4 bg-white">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-bold d-block mb-1" style="font-size: 12px;">ចំណូលសរុប (Revenue)</small>
                            <h4 class="fw-bold text-dark mb-0 font-monospace">
                                ${{ number_format($total_revenue ?? 0, 2) }}
                            </h4>
                        </div>
                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-3">
                            <i class="fa-solid fa-sack-dollar fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card admin-stat-card shadow-sm border-0 h-100 rounded-4 bg-white">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-bold d-block mb-1" style="font-size: 12px;">ការកម្ម៉ង់រង់ចាំ (Pending)</small>
                            <h4 class="fw-bold text-warning mb-0 font-monospace">
                                {{ $pending_orders_count ?? 0 }} <span style="font-size: 13px; font-family: 'Kantumruy Pro'; font-weight: 600;">វិក្កយបត្រ</span>
                            </h4>
                        </div>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3">
                            <i class="fa-solid fa-clock-rotate-left fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card admin-stat-card shadow-sm border-0 h-100 rounded-4 bg-white">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-bold d-block mb-1" style="font-size: 12px;">គ្រាប់ពូជសរុប (Seeds)</small>
                            <h4 class="fw-bold text-primary mb-0 font-monospace">
                                {{ $total_seeds_count ?? 0 }} <span style="font-size: 13px; font-family: 'Kantumruy Pro'; font-weight: 600;">មុខ</span>
                            </h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3">
                            <i class="fa-solid fa-seedling fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card admin-stat-card shadow-sm border-0 h-100 rounded-4 bg-white">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-bold d-block mb-1" style="font-size: 12px;">គ្រាប់ពូជជិតអស់ស្តុក</small>
                            <h4 class="fw-bold text-danger mb-0 font-monospace">
                                {{ $low_stock_count ?? 0 }} <span style="font-size: 13px; font-family: 'Kantumruy Pro'; font-weight: 600;">មុខ</span>
                            </h4>
                        </div>
                        <div class="bg-danger bg-opacity-10 text-danger rounded-3 p-3">
                            <i class="fa-solid fa-triangle-exclamation fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 📊 VISUAL ANALYTICS ROW (Chart.js) -->
    <div class="row g-4 mb-4">
        <!-- Monthly Revenue Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-header bg-white border-0 py-3.5 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold text-dark mb-0" style="font-family: 'Kantumruy Pro', sans-serif;">
                            <i class="fa-solid fa-chart-simple text-success me-2"></i>
                            និន្នាការចំណូលប្រចាំខែ (Monthly Revenue)
                        </h6>
                        <small class="text-muted">ស្ថិតិចំណូលគិតជាដុល្លារ ($) ប្រចាំខែនីមួយៗ</small>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fw-bold font-monospace" style="font-size: 11px;">
                        {{ date('Y') }}
                    </span>
                </div>
                <div class="card-body px-4 pb-4 pt-1">
                    <div style="height: 260px;">
                        <canvas id="revenueTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Distribution Doughnut Chart -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white h-100">
                <div class="card-header bg-white border-0 py-3.5 px-4">
                    <h6 class="fw-bold text-dark mb-0" style="font-family: 'Kantumruy Pro', sans-serif;">
                        <i class="fa-solid fa-chart-pie text-primary me-2"></i>
                        សមាមាត្រតាមប្រភេទបន្លែ
                    </h6>
                    <small class="text-muted">ចំនួនគ្រាប់ពូជចែកតាមប្រភេទ</small>
                </div>
                <div class="card-body px-4 pb-4 pt-1 d-flex flex-column align-items-center justify-content-center">
                    <div style="height: 230px; width: 100%;">
                        <canvas id="categoryDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ⚠️ LOW STOCK INVENTORY ALERT WIDGET (With Quick Restock Modal) -->
    @if(isset($low_stock_seeds) && count($low_stock_seeds) > 0)
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white border-start border-danger border-4">
        <div class="card-header bg-white border-0 py-3.5 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="fw-bold text-danger mb-0" style="font-family: 'Kantumruy Pro', sans-serif;">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    ការជូនដំណឹង៖ គ្រាប់ពូជដែលជិតអស់ពីស្តុក (Low Stock Alert)
                </h6>
                <small class="text-muted">គ្រាប់ពូជដែលមានស្តុកនៅសល់ត្រឹម ៥ កញ្ចប់ ឬតិចជាងនេះ គួរតែបំពេញស្តុកបន្ថែម</small>
            </div>
            <a href="{{ url('/admin/seeds') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">
                គ្រប់គ្រងស្តុកទាំងអស់ <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 13.5px;">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ឈ្មោះគ្រាប់ពូជ</th>
                            <th>ប្រភេទ</th>
                            <th>តម្លៃ</th>
                            <th>ស្តុកបច្ចុប្បន្ន</th>
                            <th class="text-center pe-4">សកម្មភាពបន្ទាន់</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($low_stock_seeds as $lSeed)
                        <tr>
                            <td class="ps-4">
                                <strong class="text-dark">{{ $lSeed->name_kh }}</strong>
                                <small class="text-muted d-block" style="font-size: 11px;">{{ $lSeed->name_en }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-muted border rounded-pill">{{ $lSeed->category?->name_kh ?? 'N/A' }}</span>
                            </td>
                            <td class="font-monospace fw-bold text-dark">${{ number_format($lSeed->price, 2) }}</td>
                            <td>
                                @if($lSeed->stock == 0)
                                    <span class="badge bg-danger rounded-pill px-2.5 py-1 fw-bold">អស់ពីស្តុក (0)</span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1 fw-bold">នៅសល់តែ {{ $lSeed->stock }} កញ្ចប់</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <button type="button" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#dashRestock{{ $lSeed->id }}">
                                    <i class="fa-solid fa-plus me-1"></i> បន្ថែមស្តុក
                                </button>

                                <!-- Restock Modal -->
                                <div class="modal fade text-start" id="dashRestock{{ $lSeed->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-sm">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <form action="{{ url('/admin/seeds/quick-restock/' . $lSeed->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-0 pb-0">
                                                    <h6 class="modal-title fw-bold text-dark" style="font-family: 'Kantumruy Pro', sans-serif;">
                                                        <i class="fa-solid fa-boxes-packing text-warning me-1.5"></i>បន្ថែមស្តុកបន្ទាន់
                                                    </h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body py-3">
                                                    <div class="mb-2 small">គ្រាប់ពូជ៖ <strong class="text-dark">{{ $lSeed->name_kh }}</strong></div>
                                                    <div class="mb-3 small text-muted">ស្តុកបច្ចុប្បន្ន៖ <span class="badge bg-light text-dark border font-monospace">{{ $lSeed->stock }} កញ្ចប់</span></div>
                                                    <label class="form-label fw-bold small text-dark">ចំនួនបន្ថែម (កញ្ចប់) <span class="text-danger">*</span></label>
                                                    <input type="number" name="quantity" class="form-control rounded-3 font-monospace fw-bold" value="50" min="1" required>
                                                </div>
                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-light rounded-pill btn-sm px-3" data-bs-dismiss="modal">បោះបង់</button>
                                                    <button type="submit" class="btn btn-warning rounded-pill btn-sm px-3 fw-bold text-dark">
                                                        <i class="fa-solid fa-plus me-1"></i> បន្ថែមស្តុក
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Best Sellers & Recent Orders Row -->
    <div class="row g-4">
        <!-- Best Sellers -->
        <div class="col-xl-5">
            <div class="card admin-main-card border-0 shadow-sm h-100 rounded-4 bg-white">
                <div class="card-header bg-white border-0 py-3.5 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 15px; font-family: 'Kantumruy Pro', sans-serif;">
                        <i class="fa-solid fa-crown text-warning me-2"></i>
                        គ្រាប់ពូជលក់ដាច់បំផុត (Top Sellers)
                    </h6>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 fw-bold" style="font-size: 11px;">
                        Live Data
                    </span>
                </div>

                <div class="card-body px-4 pb-4 pt-1">
                    <div class="table-responsive">
                        <table class="table align-middle best-seller-table mb-0" style="font-size: 13.5px;">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-2">ចំណាត់ថ្នាក់</th>
                                    <th>រូបភាព</th>
                                    <th>ឈ្មោះគ្រាប់ពូជ</th>
                                    <th class="text-end pe-3">ចំនូនលក់</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bestSellers as $index => $item)
                                    <tr style="border-bottom: 1px dashed #edf2f7;">
                                        <td class="fw-bold text-secondary py-3">
                                            @if($index == 0)
                                                <span class="badge bg-warning text-dark rounded-circle px-2 py-1" style="font-size: 11px;">
                                                    <i class="fa-solid fa-crown me-0.5"></i> 1
                                                </span>
                                            @else
                                                <span class="ps-1.5">#{{ $index + 1 }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->image)
                                                <img src="{{ asset('uploads/seeds/' . $item->image) }}"
                                                     class="rounded-3 border shadow-sm"
                                                     width="38"
                                                     height="38"
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-3 border d-flex align-items-center justify-content-center text-success" style="width: 38px; height: 38px;">
                                                    <i class="fa-solid fa-seedling"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-dark text-truncate" style="max-width: 130px;">
                                            {{ $item->name_kh }}
                                        </td>
                                        <td class="text-end pe-3">
                                            <span class="fw-bold text-success font-monospace fs-6">
                                                {{ $item->total_sold }}
                                            </span>
                                            <span class="text-muted small" style="font-size: 11.5px;">កញ្ចប់</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">
                                            មិនទាន់មានទិន្នន័យនៃការលក់នៅឡើយទេ
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-xl-7">
            <div class="card admin-main-card border-0 shadow-sm h-100 rounded-4 bg-white">
                <div class="card-header bg-white border-0 py-3.5 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 15px; font-family: 'Kantumruy Pro', sans-serif;">
                        <i class="fa-solid fa-receipt text-success me-2"></i>
                        ការកម្ម៉ង់ថ្មីៗចុងក្រោយ
                    </h6>
                    <a href="{{ url('/admin/orders') }}" class="btn btn-light border rounded-pill fw-bold px-3 btn-sm text-secondary shadow-sm" style="font-size: 11.5px;">
                        មើលទាំងអស់ <i class="fa-solid fa-arrow-right ms-1 small"></i>
                    </a>
                </div>

                <div class="card-body p-0 pt-1">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle admin-dashboard-table mb-0" style="font-size: 13.5px;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4 py-2">លេខវិក្កយបត្រ</th>
                                    <th>អតិថិជន</th>
                                    <th>ទឹកប្រាក់</th>
                                    <th>ស្ថានភាព</th>
                                    <th class="text-center pe-4">កាលបរិច្ឆេទ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($recent_orders) && count($recent_orders) > 0)
                                    @foreach($recent_orders as $order)
                                    <tr style="border-bottom: 1px solid #edf2f7;">
                                        <td class="ps-4 fw-bold text-success font-monospace">
                                            #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                        </td>
                                        <td>
                                            <strong class="text-dark d-block mb-0" style="font-size: 13.5px;">{{ $order->customer_name }}</strong>
                                            <small class="text-muted font-monospace" style="font-size: 11px;">
                                                <i class="fa-solid fa-phone me-1"></i>{{ $order->customer_phone }}
                                            </small>
                                        </td>
                                        <td class="fw-bold text-danger font-monospace">
                                            ${{ number_format($order->total_price, 2) }}
                                        </td>
                                        <td>
                                            @if(strtolower(trim($order->status)) === 'pending')
                                                <span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                                    <i class="fa-solid fa-clock me-1"></i>រង់ចាំពិនិត្យ
                                                </span>
                                            @elseif(strtolower(trim($order->status)) === 'shipping')
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                                    <i class="fa-solid fa-truck me-1"></i>កំពុងដឹកជញ្ជូន
                                                </span>
                                            @elseif(strtolower(trim($order->status)) === 'completed')
                                                <span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                                    <i class="fa-solid fa-circle-check me-1"></i>ជោគជ័យ
                                                </span>
                                            @elseif(strtolower(trim($order->status)) === 'cancelled')
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                                    <i class="fa-solid fa-xmark me-1"></i>បានបោះបង់
                                                </span>
                                            @else
                                                <span class="badge bg-info bg-opacity-10 text-info px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px;">
                                                    <i class="fa-solid fa-boxes-packing me-1"></i>{{ ucfirst($order->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center text-muted small pe-4 font-monospace" style="font-size: 11.5px;">
                                            {{ $order->created_at ? $order->created_at->format('d-M-Y H:i') : '' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <div class="p-3 bg-light rounded-circle d-inline-flex mb-2 text-muted">
                                                <i class="fa-solid fa-folder-open display-6"></i>
                                            </div>
                                            <h6 class="fw-bold text-secondary">មិនទាន់មានការកម្ម៉ង់ថ្មីៗនៅឡើយទេ</h6>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 📈 Chart.js Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Monthly Revenue & Orders Chart
    const monthlyData = @json($monthlySales ?? []);
    const labels = monthlyData.map(item => item.month);
    const revenues = monthlyData.map(item => item.revenue);
    const orderCounts = monthlyData.map(item => item.orders);

    const ctxRevenue = document.getElementById('revenueTrendChart');
    if (ctxRevenue) {
        new Chart(ctxRevenue, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'ចំណូលសរុប ($)',
                        data: revenues,
                        backgroundColor: 'rgba(25, 135, 84, 0.75)',
                        borderColor: '#198754',
                        borderWidth: 1.5,
                        borderRadius: 8,
                        yAxisID: 'y'
                    },
                    {
                        label: 'ចំនួនការកម្ម៉ង់ (លើក)',
                        data: orderCounts,
                        type: 'line',
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.2)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#f59e0b',
                        pointRadius: 4,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: "'Kantumruy Pro', sans-serif", size: 12 }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        ticks: {
                            callback: function(val) { return '$' + val; }
                        }
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        beginAtZero: true,
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    }

    // 2. Category Distribution Chart
    const categoryData = @json($categoryStats ?? []);
    const catLabels = categoryData.map(item => item.name_kh);
    const catCounts = categoryData.map(item => item.seeds_count);

    const ctxCat = document.getElementById('categoryDistributionChart');
    if (ctxCat && catLabels.length > 0) {
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catCounts,
                    backgroundColor: [
                        '#198754',
                        '#3b82f6',
                        '#f59e0b',
                        '#ec4899',
                        '#8b5cf6',
                        '#14b8a6'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: "'Kantumruy Pro', sans-serif", size: 11 },
                            boxWidth: 12
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }
});
</script>

@endsection
