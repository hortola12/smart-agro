@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

@section('admin_content')

<div class="container-fluid">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">
                <i class="fa-solid fa-chart-pie text-success me-2"></i>
                ផ្ទាំងគ្រប់គ្រងទូទៅ (Dashboard)
            </h4>
            <p class="text-muted small mb-0">
                ស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រង Grow2Growth Smart Agro-Seed
            </p>
        </div>

        <div class="today-box shadow-sm fw-bold" style="font-size: 13.5px;">
            <i class="fa-solid fa-calendar-days text-success me-2"></i>
            ថ្ងៃនេះ៖ {{ date('d-M-Y') }}
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card admin-stat-card shadow-sm border-0 h-100 rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-bold d-block mb-2" style="font-size: 12px;">ចំណូលសរុប</small>
                            <h4 class="fw-bold text-dark mb-0 font-monospace">
                                ${{ number_format($total_revenue ?? 0, 2) }}
                            </h4>
                        </div>
                        <div class="admin-icon-container bg-success bg-opacity-10 text-success rounded-3 p-2">
                            <i class="fa-solid fa-sack-dollar fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card admin-stat-card shadow-sm border-0 h-100 rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-bold d-block mb-2" style="font-size: 12px;">ការកម្ម៉ង់រង់ចាំ</small>
                            <h4 class="fw-bold text-warning mb-0 font-monospace">
                                {{ $pending_orders_count ?? 0 }} <span style="font-size: 13.5px; font-family: 'Kantumruy Pro'; font-weight: 600;">វិក្កយបត្រ</span>
                            </h4>
                        </div>
                        <div class="admin-icon-container bg-warning bg-opacity-10 text-warning rounded-3 p-2">
                            <i class="fa-solid fa-clock-rotate-left fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card admin-stat-card shadow-sm border-0 h-100 rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-bold d-block mb-2" style="font-size: 12px;">គ្រាប់ពូជសរុប</small>
                            <h4 class="fw-bold text-primary mb-0 font-monospace">
                                {{ $total_seeds_count ?? 0 }} <span style="font-size: 13.5px; font-family: 'Kantumruy Pro'; font-weight: 600;">ប្រភេទ</span>
                            </h4>
                        </div>
                        <div class="admin-icon-container bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                            <i class="fa-solid fa-seedling fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card admin-stat-card shadow-sm border-0 h-100 rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-bold d-block mb-2" style="font-size: 12px;">គ្រាប់ពូជជិតអស់</small>
                            <h4 class="fw-bold text-danger mb-0 font-monospace">
                                {{ $low_stock_count ?? 0 }} <span style="font-size: 13.5px; font-family: 'Kantumruy Pro'; font-weight: 600;">មុខសញ្ញា</span>
                            </h4>
                        </div>
                        <div class="admin-icon-container bg-danger bg-opacity-10 text-danger rounded-3 p-2">
                            <i class="fa-solid fa-triangle-exclamation fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-xl-5">
            <div class="card admin-main-card border-0 shadow-sm h-100 rounded-4">
                <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 15.5px;">
                        <i class="fa-solid fa-chart-line text-success me-2"></i>
                        គ្រាប់ពូជលក់ដាច់បំផុត
                    </h5>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1.5 fw-bold" style="font-size: 11px;">
                        Live Data
                    </span>
                </div>

                <div class="card-body px-4 pb-4 pt-1">
                    <div class="table-responsive">
                        <table class="table align-middle best-seller-table mb-0" style="font-size: 13.8px;">
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
                                            <img src="{{ $item->image ? asset('uploads/seeds/' . $item->image) : 'https://via.placeholder.com/40' }}"
                                                 class="rounded-3 border shadow-sm"
                                                 width="40"
                                                 height="40"
                                                 style="object-fit: cover;">
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

        <div class="col-xl-7">
            <div class="card admin-main-card border-0 shadow-sm h-100 rounded-4">
                <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 15.5px;">
                        <i class="fa-solid fa-list-check text-success me-2"></i>
                        ការកម្ម៉ង់ថ្មីៗចុងក្រោយ
                    </h5>
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
                                            @if(strtolower(trim($order->status)) === 'pending' || $order->status === 'រង់ចាំ' || $order->status === 'កំពុងរៀបចំ')
                                                <span class="badge bg-warning bg-opacity-10 text-warning px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px; font-family: 'Kantumruy Pro';">
                                                    <i class="fa-solid fa-clock me-1"></i>រង់ចាំពិនិត្យ
                                                </span>
                                            @elseif(strtolower(trim($order->status)) === 'shipping' || $order->status === 'កំពុងដឹកជញ្ជូន')
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px; font-family: 'Kantumruy Pro';">
                                                    <i class="fa-solid fa-truck me-1"></i>កំពុងដឹកជញ្ជូន
                                                </span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 11px; font-family: 'Kantumruy Pro';">
                                                    <i class="fa-solid fa-circle-check me-1"></i>ដឹកជញ្ជូនរួច
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

@endsection
