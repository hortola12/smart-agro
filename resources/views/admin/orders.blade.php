@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

@section('admin_content')
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="fa-solid fa-circle-check fs-5 me-2 text-success"></i>
            <span class="fw-bold text-success">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="fa-solid fa-triangle-exclamation fs-5 me-2 text-danger"></i>
            <span class="fw-bold text-danger">{{ session('error') }}</span>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">គ្រប់គ្រងការកម្ម៉ង់ទិញ (Orders)</h4>
            <p class="text-muted small mb-0">ពិនិត្យមើលបញ្ជីឈ្មោះអ្នកទិញគ្រាប់ពូជ គ្រប់គ្រងស្ថានភាពដឹកជញ្ជូន និងព្រីនវិក្កយបត្រផ្លូវការ</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ url('/admin/orders/packing-manifest') }}" target="_blank" class="btn btn-primary fw-bold rounded-pill px-3 shadow-sm">
                <i class="fa-solid fa-boxes-packing me-1.5"></i> បញ្ជីរៀបចំទំនិញរួម (Packing Manifest)
            </a>
            <a href="{{ url('/admin/orders/export') }}" class="btn btn-outline-success fw-bold rounded-pill px-3 shadow-sm">
                <i class="fa-solid fa-file-csv me-1.5"></i> ទាញយក CSV
            </a>
        </div>
    </div>

    <!-- 📊 KPI សង្ខេបផ្នែកហិរញ្ញវត្ថុ & ការកម្ម៉ង់ (Financial & Order KPI Cards) -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-primary">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block">ការកម្ម៉ង់សរុប</span>
                        <h4 class="fw-bold text-dark mb-0 font-monospace">{{ $totalOrdersCount ?? $orders->total() }}</h4>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-boxes-stacked fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-success">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block">ចំណូលសរុប</span>
                        <h4 class="fw-bold text-success mb-0 font-monospace">${{ number_format($totalRevenue ?? 0, 2) }}</h4>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-sack-dollar fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-warning">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block">រង់ចាំពិនិត្យ</span>
                        <h4 class="fw-bold text-warning mb-0 font-monospace">{{ $pendingCount ?? 0 }}</h4>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-clock-rotate-left fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-info">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block">បានដឹកជោគជ័យ</span>
                        <h4 class="fw-bold text-info mb-0 font-monospace">{{ $completedCount ?? 0 }}</h4>
                    </div>
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-circle-check fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔍 ប្រអប់ស្វែងរក និង Filter ស្ថានភាព & កាលបរិច្ឆេទ -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ url('/admin/orders') }}" method="GET" class="row g-2 align-items-center m-0">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="ស្វែងរកតាមឈ្មោះ, លេខទូរស័ព្ទ ឬលេខវិក្កយបត្រ..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select bg-light">
                    <option value="all">-- ស្ថានភាពទាំងអស់ --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>🟡 រង់ចាំពិនិត្យ (Pending)</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>🔵 កំពុងរៀបចំ (Processing)</option>
                    <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>🚚 កំពុងដឹកជញ្ជូន (Shipping)</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>🟢 ជោគជ័យ (Completed)</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>🔴 បានបោះបង់ (Cancelled)</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="date_filter" class="form-select bg-light">
                    <option value="">-- កាលបរិច្ឆេទទាំងអស់ (All Time) --</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>📅 ថ្ងៃនេះ (Today)</option>
                    <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>📅 សប្ដាហ៍នេះ (This Week)</option>
                    <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>📅 ខែនេះ (This Month)</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-success fw-bold rounded-pill px-3 w-100">
                    <i class="fa-solid fa-filter me-1"></i> ស្វែងរក
                </button>
                @if(request('search') || (request('status') && request('status') !== 'all') || request('date_filter'))
                    <a href="{{ url('/admin/orders') }}" class="btn btn-light border fw-bold rounded-pill px-3" title="លុប Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="card admin-main-card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle admin-table admin-dashboard-table mb-0" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th class="ps-4">លេខវិក្កយបត្រ</th>
                            <th>ព័ត៌មានអតិថិជន</th>
                            <th width="28%">គ្រាប់ពូជដែលកម្ម៉ង់</th>
                            <th>ទឹកប្រាក់</th>
                            <th class="text-center" width="18%">ស្ថានភាព</th>
                            <th class="text-center" width="14%">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold text-success font-monospace">
                                #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                <small class="text-muted d-block" style="font-size: 11px; font-weight: normal;">
                                    {{ $order->created_at ? $order->created_at->format('d-M-Y H:i') : '' }}
                                </small>
                            </td>

                            <td>
                                <strong class="text-dark d-block mb-1">{{ $order->customer_name }}</strong>
                                <small class="text-muted d-block mb-1"><i class="fa-solid fa-phone me-1 text-success"></i>{{ $order->customer_phone }}</small>
                                <small class="text-muted d-block" style="font-size: 12px; max-width: 240px;"><i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $order->delivery_address }}</small>
                            </td>

                            <td>
                                <div class="order-item-list-wrapper">
                                    @foreach($order->items as $item)
                                        <div class="order-item-compact-box mb-1 p-1 bg-light rounded d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $item->seed?->image ? asset('uploads/seeds/' . $item->seed->image) : 'https://via.placeholder.com/35' }}"
                                                    class="rounded border" style="width: 32px; height: 32px; object-fit: cover;" alt="Seed">
                                                <span class="text-dark fw-bold small">
                                                    {{ $item->seed?->name_kh ?? 'គ្រាប់ពូជលុបចេញ' }}
                                                </span>
                                            </div>
                                            <div class="text-end font-monospace small">
                                                <span class="badge bg-secondary bg-opacity-25 text-dark me-1">x{{ $item->quantity }}</span>
                                                <span class="fw-bold text-success">${{ number_format($item->subtotal, 2) }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            <td class="text-danger fw-bold fs-6 font-monospace">${{ number_format($order->total_price, 2) }}</td>

                            <!-- 🟢 ស្ថានភាពបែបទំនើប (Interactive Status Dropdown Pill) -->
                            <td class="text-center">
                                @php
                                    $st = strtolower(trim($order->status));
                                    $statusConfigs = [
                                        'pending' => [
                                            'class' => 'status-pill-pending',
                                            'dot' => 'status-dot-pending',
                                            'icon' => 'fa-clock',
                                            'name_kh' => 'រង់ចាំពិនិត្យ',
                                            'name_en' => 'Pending',
                                            'desc' => 'កសិករទើបតែបញ្ជាទិញថ្មី',
                                            'badge_color' => 'warning',
                                        ],
                                        'processing' => [
                                            'class' => 'status-pill-processing',
                                            'dot' => 'status-dot-processing',
                                            'icon' => 'fa-boxes-packing',
                                            'name_kh' => 'កំពុងរៀបចំ',
                                            'name_en' => 'Processing',
                                            'desc' => 'វេចខ្ចប់គ្រាប់ពូជពីឃ្លាំង',
                                            'badge_color' => 'info',
                                        ],
                                        'shipping' => [
                                            'class' => 'status-pill-shipping',
                                            'dot' => 'status-dot-shipping',
                                            'icon' => 'fa-truck-fast',
                                            'name_kh' => 'កំពុងដឹកជញ្ជូន',
                                            'name_en' => 'Shipping',
                                            'desc' => 'បានប្រគល់ជូនអ្នកដឹក',
                                            'badge_color' => 'primary',
                                        ],
                                        'completed' => [
                                            'class' => 'status-pill-completed',
                                            'dot' => 'status-dot-completed',
                                            'icon' => 'fa-circle-check',
                                            'name_kh' => 'ជោគជ័យ',
                                            'name_en' => 'Completed',
                                            'desc' => 'ទំនិញបានដល់ដៃកសិករ',
                                            'badge_color' => 'success',
                                        ],
                                        'cancelled' => [
                                            'class' => 'status-pill-cancelled',
                                            'dot' => 'status-dot-cancelled',
                                            'icon' => 'fa-ban',
                                            'name_kh' => 'បានបោះបង់',
                                            'name_en' => 'Cancelled',
                                            'desc' => 'បោះបង់ & សងស្តុកចូលឃ្លាំង',
                                            'badge_color' => 'danger',
                                        ],
                                    ];
                                    $curr = $statusConfigs[$st] ?? [
                                        'class' => 'status-pill-secondary',
                                        'dot' => 'status-dot-muted',
                                        'icon' => 'fa-circle-question',
                                        'name_kh' => $order->status,
                                        'name_en' => ucfirst($order->status),
                                        'desc' => 'ស្ថានភាពទូទៅ',
                                        'badge_color' => 'secondary',
                                    ];
                                @endphp

                                <div class="dropdown d-inline-block">
                                    <button class="status-pill-trigger {{ $curr['class'] }} dropdown-toggle shadow-sm"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                            data-bs-boundary="viewport"
                                            aria-expanded="false"
                                            title="ចុចដើម្បីប្តូរស្ថានភាពបញ្ជាទិញ">
                                        <span class="status-dot-pulse {{ $curr['dot'] }}"></span>
                                        <span class="fw-bold">{{ $curr['name_kh'] }}</span>
                                        <i class="fa-solid fa-chevron-down ms-1 opacity-50" style="font-size: 9px;"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end status-dropdown-card shadow-lg border-0">
                                        <li class="status-dropdown-header d-flex align-items-center justify-content-between">
                                            <span><i class="fa-solid fa-arrows-rotate me-1.5 text-success"></i>ប្តូរស្ថានភាព</span>
                                            <span class="badge bg-light text-muted font-monospace" style="font-size: 10px;">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        </li>
                                        @foreach($statusConfigs as $sKey => $sCfg)
                                            <li>
                                                <form action="{{ url('/admin/orders/status/' . $order->id) }}" method="POST" id="status-form-{{ $order->id }}-{{ $sKey }}" class="m-0">
                                                    @csrf
                                                    <input type="hidden" name="status" value="{{ $sKey }}">
                                                    <button type="button"
                                                            class="status-option-btn {{ $st === $sKey ? 'active' : '' }}"
                                                            onclick="changeOrderStatus('{{ $order->id }}', '{{ $sKey }}', '{{ $sCfg['name_kh'] }}')">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge rounded-circle p-1.5 bg-{{ $sCfg['badge_color'] }} bg-opacity-15 text-{{ $sCfg['badge_color'] }}" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center;">
                                                                <i class="fa-solid {{ $sCfg['icon'] }}" style="font-size: 11px;"></i>
                                                            </span>
                                                            <div class="text-start">
                                                                <div class="fw-bold text-dark" style="font-size: 12px; line-height: 1.2;">
                                                                    {{ $sCfg['name_kh'] }}
                                                                    <span class="text-muted fw-normal" style="font-size: 10.5px;">({{ $sCfg['name_en'] }})</span>
                                                                </div>
                                                                <span class="text-muted" style="font-size: 10px;">{{ $sCfg['desc'] }}</span>
                                                            </div>
                                                        </div>
                                                        @if($st === $sKey)
                                                            <i class="fa-solid fa-check text-success ms-2"></i>
                                                        @endif
                                                    </button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>

                            <!-- ⚡ សកម្មភាពរៀបចំយ៉ាងស្អាត (Clean Icon Action Buttons) -->
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-1.5 flex-wrap">
                                    <!-- 👁️ មើលព័ត៌មានលម្អិត -->
                                    <button type="button" class="admin-action-btn text-info shadow-sm"
                                            title="មើលព័ត៌មានលម្អិត (Quick View)"
                                            data-bs-toggle="modal" data-bs-target="#orderDetailModal{{ $order->id }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                    <!-- 🖨️ ប៊ូតុងព្រីនវិក្កយបត្រ -->
                                    <button type="button" class="admin-action-btn text-primary shadow-sm"
                                            title="ព្រីនវិក្កយបត្រផ្លូវការ"
                                            onclick="printReceipt({{ json_encode([
                                                'id' => str_pad($order->id, 5, '0', STR_PAD_LEFT),
                                                'date' => $order->created_at ? $order->created_at->format('d-M-Y H:i') : '',
                                                'customer_name' => $order->customer_name,
                                                'customer_phone' => $order->customer_phone,
                                                'delivery_address' => $order->delivery_address,
                                                'total_price' => number_format($order->total_price, 2),
                                                'status' => $order->status,
                                                'items' => $order->items->map(fn($it) => [
                                                    'name' => $it->seed?->name_kh ?? 'គ្រាប់ពូជ',
                                                    'batch' => $it->seed?->batch_number ?? '---',
                                                    'quantity' => $it->quantity,
                                                    'price' => number_format($it->price, 2),
                                                    'subtotal' => number_format($it->subtotal, 2),
                                                ]),
                                            ]) }})">
                                        <i class="fa-solid fa-print"></i>
                                    </button>

                                    <!-- 🗑️ ប៊ូតុងលុប -->
                                    <form action="{{ url('/admin/orders/delete/' . $order->id) }}" method="POST" id="delete-form-{{ $order->id }}" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="admin-action-btn admin-action-btn-delete shadow-sm btn-delete" data-id="{{ $order->id }}" title="លុបការកម្ម៉ង់">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <!-- 🔍 Quick View Order Detail Modal -->
                                <div class="modal fade text-start" id="orderDetailModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-header border-0 pb-0 pt-3 px-4">
                                                <div>
                                                    <h5 class="modal-title fw-bold text-dark" style="font-family: 'Kantumruy Pro', sans-serif;">
                                                        <i class="fa-solid fa-receipt text-success me-2"></i>ព័ត៌មានវិក្កយបត្រ #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                                    </h5>
                                                    <small class="text-muted">កាលបរិច្ឆេទបញ្ជាទិញ៖ {{ $order->created_at ? $order->created_at->format('d/m/Y - H:i A') : '---' }}</small>
                                                </div>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body px-4 py-3">
                                                <!-- Top Customer Info Card -->
                                                <div class="p-3 bg-light rounded-3 mb-3 border">
                                                    <div class="row g-2">
                                                        <div class="col-md-4">
                                                            <small class="text-muted d-block" style="font-size: 11px;">ឈ្មោះអតិថិជន</small>
                                                            <strong class="text-dark">{{ $order->customer_name }}</strong>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <small class="text-muted d-block" style="font-size: 11px;">លេខទូរស័ព្ទ</small>
                                                            <strong class="text-dark font-monospace">{{ $order->customer_phone }}</strong>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <small class="text-muted d-block" style="font-size: 11px;">ស្ថានភាពបច្ចុប្បន្ន</small>
                                                            <span class="status-pill-trigger {{ $curr['class'] }} py-0.5 px-2.5" style="cursor: default; font-size: 11.5px;">
                                                                <span class="status-dot-pulse {{ $curr['dot'] }}"></span>
                                                                <span>{{ $curr['name_kh'] }}</span>
                                                            </span>
                                                        </div>
                                                        <div class="col-12 mt-2 pt-2 border-top">
                                                            <small class="text-muted d-block" style="font-size: 11px;">អាសយដ្ឋានដឹកជញ្ជូន</small>
                                                            <span class="text-dark"><i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $order->delivery_address }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Items Table -->
                                                <div class="table-responsive rounded-3 border">
                                                    <table class="table table-sm align-middle mb-0" style="font-size: 13px;">
                                                        <thead class="table-light text-muted small fw-bold">
                                                            <tr>
                                                                <th class="ps-3 py-2">គ្រាប់ពូជ</th>
                                                                <th class="text-center py-2">លេខបាច់ (Batch)</th>
                                                                <th class="text-center py-2">តម្លៃរាយ</th>
                                                                <th class="text-center py-2">ចំនួន</th>
                                                                <th class="text-end pe-3 py-2">សរុប</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($order->items as $it)
                                                                <tr>
                                                                    <td class="ps-3 py-2">
                                                                        <div class="d-flex align-items-center gap-2">
                                                                            <img src="{{ $it->seed?->image ? asset('uploads/seeds/' . $it->seed->image) : 'https://via.placeholder.com/35' }}"
                                                                                 class="rounded border" style="width: 32px; height: 32px; object-fit: cover;" alt="Seed">
                                                                            <span class="fw-bold text-dark">{{ $it->seed?->name_kh ?? 'គ្រាប់ពូជលុបចេញ' }}</span>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center py-2 font-monospace text-primary">#{{ $it->seed?->batch_number ?? '---' }}</td>
                                                                    <td class="text-center py-2 font-monospace">${{ number_format($it->price, 2) }}</td>
                                                                    <td class="text-center py-2 font-monospace fw-bold">x{{ $it->quantity }}</td>
                                                                    <td class="text-end pe-3 py-2 font-monospace fw-bold text-success">${{ number_format($it->subtotal, 2) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Coupon & Grand Total Breakdown -->
                                                <div class="mt-3 text-end">
                                                    @if($order->coupon_code)
                                                        <div class="small text-muted mb-1">
                                                            <span>លេខកូដបញ្ចុះតម្លៃ៖ </span>
                                                            <span class="badge bg-success bg-opacity-10 text-success font-monospace px-2 py-1 border border-success border-opacity-25">
                                                                <i class="fa-solid fa-tag me-1"></i>{{ $order->coupon_code }}
                                                            </span>
                                                            <span class="text-success font-monospace fw-bold ms-2">-${{ number_format($order->discount_amount ?? 0, 2) }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="fs-5">
                                                        <span class="text-muted" style="font-size: 14px;">ទឹកប្រាក់ទូទាត់សរុប៖ </span>
                                                        <strong class="text-danger font-monospace fs-4">${{ number_format($order->total_price, 2) }}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0 px-4 pb-3">
                                                <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">បិទ</button>
                                                <button type="button" class="btn btn-outline-primary rounded-pill px-3 fw-bold"
                                                        onclick="printReceipt({{ json_encode([
                                                            'id' => str_pad($order->id, 5, '0', STR_PAD_LEFT),
                                                            'date' => $order->created_at ? $order->created_at->format('d-M-Y H:i') : '',
                                                            'customer_name' => $order->customer_name,
                                                            'customer_phone' => $order->customer_phone,
                                                            'delivery_address' => $order->delivery_address,
                                                            'total_price' => number_format($order->total_price, 2),
                                                            'status' => $order->status,
                                                            'items' => $order->items->map(fn($it) => [
                                                                'name' => $it->seed?->name_kh ?? 'គ្រាប់ពូជ',
                                                                'batch' => $it->seed?->batch_number ?? '---',
                                                                'quantity' => $it->quantity,
                                                                'price' => number_format($it->price, 2),
                                                                'subtotal' => number_format($it->subtotal, 2),
                                                            ]),
                                                        ]) }})">
                                                    <i class="fa-solid fa-print me-1.5"></i> ព្រីនវិក្កយបត្រ
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="p-3 bg-light rounded-circle d-inline-flex mb-2 text-muted">
                                    <i class="fa-solid fa-box-open display-6"></i>
                                </div>
                                <h6 class="fw-bold text-secondary">មិនទាន់មានការកម្ម៉ង់ទិញណាមួយត្រូវតាមលក្ខខណ្ឌស្វែងរកឡើយ</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="p-3 border-top d-flex justify-content-end bg-white">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pop-up សម្រាប់ប៊ូតុង "លុបការកម្ម៉ង់"
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'តើអ្នកប្រាកដទេ?',
                    text: "ប្រវត្តិវិក្កយបត្រនេះនឹងត្រូវលុប ហើយស្តុកគ្រាប់ពូជនឹងត្រូវបូកត្រឡប់ចូលប្រព័ន្ធវិញ!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> យល់ព្រមលុប',
                    cancelButtonText: 'បោះបង់'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${orderId}`).submit();
                    }
                });
            });
        });
    });

    /**
     * ⚡ មុខងារប្តូរស្ថានភាពបញ្ជាទិញរហ័ស (Interactive Status Changer with Safety Confirmation)
     */
    function changeOrderStatus(orderId, statusKey, statusNameKh) {
        if (statusKey === 'cancelled') {
            Swal.fire({
                title: 'បញ្ជាក់ការបោះបង់ការកម្ម៉ង់?',
                text: `តើអ្នកប្រាកដថាចង់ប្តូរទៅជា "${statusNameKh}" មែនទេ? ស្តុកគ្រាប់ពូជនៃវិក្កយបត្រនេះនឹងត្រូវបូកត្រឡប់ចូលប្រព័ន្ធវិញ!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa-solid fa-ban me-1"></i> បោះបង់ការកម្ម៉ង់',
                cancelButtonText: 'មិនបោះបង់'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`status-form-${orderId}-${statusKey}`).submit();
                }
            });
        } else {
            document.getElementById(`status-form-${orderId}-${statusKey}`).submit();
        }
    }

    /**
     * 🖨️ មុខងារព្រីនវិក្កយបត្រផ្លូវការ (Official Printable Receipt)
     */
    function printReceipt(order) {
        const printWindow = window.open('', '_blank', 'width=750,height=850');
        let itemsHtml = '';
        order.items.forEach((item, index) => {
            itemsHtml += `
                <tr>
                    <td style="padding: 10px; border-bottom: 1px dashed #e2e8f0; text-align: center;">${index + 1}</td>
                    <td style="padding: 10px; border-bottom: 1px dashed #e2e8f0; font-weight: bold;">
                        ${item.name}
                        <br><small style="color: #64748b; font-family: monospace; font-size: 11px;">Batch: #${item.batch}</small>
                    </td>
                    <td style="padding: 10px; border-bottom: 1px dashed #e2e8f0; text-align: center; font-family: monospace;">$${item.price}</td>
                    <td style="padding: 10px; border-bottom: 1px dashed #e2e8f0; text-align: center; font-family: monospace; font-weight: bold;">x${item.quantity}</td>
                    <td style="padding: 10px; border-bottom: 1px dashed #e2e8f0; text-align: right; font-family: monospace; font-weight: bold; color: #198754;">$${item.subtotal}</td>
                </tr>
            `;
        });

        printWindow.document.write(`
            <html>
            <head>
                <title>Invoice - #ORD-${order.id}</title>
                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&display=swap');
                    body { font-family: 'Kantumruy Pro', sans-serif; padding: 30px; color: #1e293b; background: #fff; line-height: 1.5; font-size: 13.5px; }
                    .header { display: flex; justify-content: space-between; border-bottom: 3px solid #198754; padding-bottom: 15px; margin-bottom: 25px; }
                    .brand { font-size: 22px; font-weight: bold; color: #198754; }
                    .invoice-id { font-family: monospace; font-size: 18px; font-weight: bold; color: #0f172a; }
                    .customer-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 25px; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
                    th { background: #f1f5f9; padding: 10px; font-weight: bold; color: #475569; font-size: 13px; }
                    .total-box { text-align: right; font-size: 16px; margin-bottom: 40px; }
                    .total-amount { font-size: 22px; font-weight: bold; color: #dc2626; font-family: monospace; }
                    .footer { text-align: center; border-top: 1px dashed #cbd5e1; padding-top: 15px; color: #64748b; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class="header">
                    <div>
                        <div class="brand">🌱 Grow2Growth Agri-Tech</div>
                        <small style="color: #64748b;">Smart Agricultural Seed Marketplace & Traceability</small>
                    </div>
                    <div style="text-align: right;">
                        <div class="invoice-id">វិក្កយបត្រ #ORD-${order.id}</div>
                        <small style="color: #64748b;">កាលបរិច្ឆេទ៖ ${order.date}</small>
                    </div>
                </div>

                <div class="customer-box">
                    <strong style="display: block; margin-bottom: 8px; color: #198754;">ព័ត៌មានដឹកជញ្ជូនទំនិញ (Delivery Info)៖</strong>
                    <div><strong>អតិថិជន៖</strong> ${order.customer_name}</div>
                    <div><strong>ទូរស័ព្ទ៖</strong> <span style="font-family: monospace;">${order.customer_phone}</span></div>
                    <div><strong>អាសយដ្ឋានដឹកជញ្ជូន៖</strong> ${order.delivery_address}</div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th width="8%">ល.រ</th>
                            <th>មុខទំនិញគ្រាប់ពូជ</th>
                            <th width="15%" style="text-align: center;">តម្លៃរាយ</th>
                            <th width="12%" style="text-align: center;">ចំនួន</th>
                            <th width="18%" style="text-align: right;">សរុប</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsHtml}
                    </tbody>
                </table>

                <div class="total-box">
                    <span>ទឹកប្រាក់សរុបរួម៖ </span>
                    <span class="total-amount">$${order.total_price}</span>
                </div>

                <div class="footer">
                    <p style="margin-bottom: 4px;">សូមអរគុណសម្រាប់ការគាំទ្រគ្រាប់ពូជកសិកម្មសុទ្ធពី Grow2Growth!</p>
                    <p style="margin: 0; font-size: 11px;">លោកអ្នកអាចស្កេនលេខកូដ Batch លើកញ្ចប់ ដើម្បីមើលប្រភពកសិដ្ឋាន និងវិធីដាំដុះបានគ្រប់ពេលវេលា។</p>
                </div>

                <script>
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                        }, 300);
                    }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
