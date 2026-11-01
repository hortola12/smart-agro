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
                            <th class="text-center">ស្ថានភាព</th>
                            <th class="text-center" width="22%">សកម្មភាព</th>
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

                            <td class="text-center">
                                @php $st = strtolower(trim($order->status)); @endphp
                                @if($st === 'pending')
                                    <span class="badge bg-warning bg-opacity-15 text-warning-emphasis border border-warning border-opacity-25 rounded-pill px-3 py-1.5" style="font-size: 12px;">
                                        <i class="fa-solid fa-clock me-1"></i> រង់ចាំពិនិត្យ
                                    </span>
                                @elseif($st === 'processing')
                                    <span class="badge bg-info bg-opacity-15 text-info-emphasis border border-info border-opacity-25 rounded-pill px-3 py-1.5" style="font-size: 12px;">
                                        <i class="fa-solid fa-boxes-packing me-1"></i> កំពុងរៀបចំ
                                    </span>
                                @elseif($st === 'shipping')
                                    <span class="badge bg-primary bg-opacity-15 text-primary border border-primary border-opacity-25 rounded-pill px-3 py-1.5" style="font-size: 12px;">
                                        <i class="fa-solid fa-truck-fast me-1"></i> កំពុងដឹកជញ្ជូន
                                    </span>
                                @elseif($st === 'completed')
                                    <span class="badge bg-success bg-opacity-15 text-success border border-success border-opacity-25 rounded-pill px-3 py-1.5" style="font-size: 12px;">
                                        <i class="fa-solid fa-circle-check me-1"></i> ជោគជ័យ
                                    </span>
                                @elseif($st === 'cancelled')
                                    <span class="badge bg-danger bg-opacity-15 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1.5" style="font-size: 12px;">
                                        <i class="fa-solid fa-ban me-1"></i> បានបោះបង់
                                    </span>
                                @else
                                    <span class="badge bg-secondary text-white rounded-pill px-3 py-1.5">{{ $order->status }}</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-1 flex-wrap">
                                    <!-- 🖨️ ប៊ូតុងព្រីនវិក្កយបត្រ -->
                                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-2.5 shadow-sm text-secondary"
                                            title="ព្រីនវិក្កយបត្រ"
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
                                        <i class="fa-solid fa-print text-primary"></i>
                                    </button>

                                    <!-- ⚙️ Dropdown ផ្លាស់ប្តូរស្ថានភាព -->
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-outline-success dropdown-toggle rounded-pill px-2.5 fw-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 12px;">
                                            ប្តូរស្ថានភាព
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3" style="font-size: 13px;">
                                            <li><h6 class="dropdown-header text-muted">ជ្រើសរើសស្ថានភាពថ្មី៖</h6></li>
                                            <li>
                                                <form action="{{ url('/admin/orders/status/' . $order->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <input type="hidden" name="status" value="processing">
                                                    <button type="submit" class="dropdown-item py-1.5"><i class="fa-solid fa-boxes-packing text-info me-2"></i>កំពុងរៀបចំ (Processing)</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ url('/admin/orders/status/' . $order->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <input type="hidden" name="status" value="shipping">
                                                    <button type="submit" class="dropdown-item py-1.5"><i class="fa-solid fa-truck-fast text-primary me-2"></i>កំពុងដឹក (Shipping)</button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ url('/admin/orders/status/' . $order->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="dropdown-item py-1.5"><i class="fa-solid fa-circle-check text-success me-2"></i>ជោគជ័យ (Completed)</button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li>
                                                <form action="{{ url('/admin/orders/status/' . $order->id) }}" method="POST" class="m-0">
                                                    @csrf
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="dropdown-item py-1.5 text-danger"><i class="fa-solid fa-ban me-2"></i>បោះបង់ (Cancel & Restore Stock)</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- 🗑️ ប៊ូតុងលុប -->
                                    <form action="{{ url('/admin/orders/delete/' . $order->id) }}" method="POST" id="delete-form-{{ $order->id }}" class="m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="admin-action-btn admin-action-btn-delete shadow-sm btn-delete" data-id="{{ $order->id }}" title="លុបការកម្ម៉ង់">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
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
