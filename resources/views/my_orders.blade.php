@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/order-history-style.css') }}">

@section('content')
<div class="container py-5 text-dark">

    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1 order-title" style="font-family: 'Kantumruy Pro', sans-serif;">
                <i class="fa-solid fa-box-open text-success me-2"></i>
                ប្រវត្តិបញ្ជាទិញគ្រាប់ពូជ
            </h3>
            <p class="text-muted small mb-0">
                តាមដានស្ថានភាពវេចខ្ចប់ ដឹកជញ្ជូន និងមើលវិក្កយបត្រទិញដូររបស់លោកអ្នក
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ url('/order-tracking') }}" class="btn btn-outline-success fw-bold rounded-pill px-3 py-2 shadow-sm order-btn">
                <i class="fa-solid fa-truck-fast me-1.5"></i> ស្វែងរកតាមលេខទូរស័ព្ទ
            </a>
            <a href="{{ url('/#featured-seeds-section') }}" class="btn btn-success fw-bold rounded-pill px-4 py-2 shadow-sm order-btn">
                <i class="fa-solid fa-basket-shopping me-2"></i>
                ទិញគ្រាប់ពូជបន្ថែម
            </a>
        </div>
    </div>

    <div class="card border-0 history-card shadow-sm overflow-hidden mb-5 bg-white rounded-4">
        <div class="table-responsive">
            <table class="table align-middle order-table mb-0" style="font-size: 14.5px;">
                <thead>
                    <tr class="bg-light border-bottom">
                        <th class="ps-4 py-3">លេខវិក្កយបត្រ</th>
                        <th class="py-3">កាលបរិច្ឆេទ</th>
                        <th class="py-3">អាសយដ្ឋានដឹកជញ្ជូន</th>
                        <th class="py-3 text-center">ស្ថានភាព</th>
                        <th class="py-3 text-end">ទឹកប្រាក់សរុប</th>
                        <th class="pe-4 py-3 text-center" width="22%">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr class="order-row border-bottom" style="height: 70px;">
                        <td class="ps-4 py-3 fw-bold text-dark">
                            <span class="order-id text-primary font-monospace">
                                #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>

                        <td class="text-muted font-monospace small">
                            {{ $order->created_at ? $order->created_at->format('d-M-Y H:i') : '---' }}
                        </td>

                        <td class="text-secondary text-truncate small" style="max-width: 220px;" title="{{ $order->delivery_address }}">
                            {{ $order->delivery_address }}
                        </td>

                        <td class="text-center">
                            @php
                                $orderStatus = strtolower(trim($order->status));
                            @endphp

                            @if($orderStatus === 'pending' || $order->status === 'កំពុងរៀបចំ')
                                <span class="badge custom-status-badge badge-warning-amber">
                                    <i class="fa-regular fa-clock me-1"></i> កំពុងរៀបចំ
                                </span>
                            @elseif($orderStatus === 'shipping' || $order->status === 'កំពុងដឹកជញ្ជូន')
                                <span class="badge custom-status-badge badge-primary-blue">
                                    <i class="fa-solid fa-truck me-1"></i> កំពុងដឹកជញ្ជូន
                                </span>
                            @elseif($orderStatus === 'completed' || $orderStatus === 'success' || $order->status === 'ជោគជ័យ')
                                <span class="badge custom-status-badge badge-success-green">
                                    <i class="fa-solid fa-circle-check me-1"></i> ជោគជ័យ
                                </span>
                            @else
                                <span class="badge custom-status-badge bg-secondary text-white">
                                    {{ $order->status }}
                                </span>
                            @endif
                        </td>

                        <td class="text-end fw-bold text-danger total-price font-monospace fs-6">
                            ${{ number_format($order->total_price, 2) }}
                            @if($order->coupon_code)
                                <small class="d-block text-success font-monospace fw-normal" style="font-size: 11px;">
                                    <i class="fa-solid fa-tag me-1"></i>{{ $order->coupon_code }} (-${{ number_format($order->discount_amount, 2) }})
                                </small>
                            @endif
                        </td>

                        <td class="pe-4 text-center">
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                <button type="button" class="btn btn-sm rounded-pill px-3 border fw-bold bg-white text-secondary shadow-sm custom-view-btn" style="font-size: 12.5px;"
                                        data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">
                                    <i class="fa-solid fa-eye me-1 text-success"></i> មើលលម្អិត
                                </button>

                                @if($orderStatus === 'completed' || $orderStatus === 'success')
                                    @php
                                        $firstItem = $order->items->first();
                                        $batchCode = $firstItem && $firstItem->seed ? $firstItem->seed->batch_number : null;
                                    @endphp
                                    @if($batchCode)
                                        <a href="{{ url('/trace-batch?search=' . $batchCode) }}" class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm animate-pulse" style="font-size: 12.5px;">
                                            <i class="fa-solid fa-route me-1"></i> ពិនិត្យប្រភព
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted bg-white">
                            <div class="empty-order-box py-4">
                                <div class="p-3 bg-light rounded-circle d-inline-flex mb-3 text-muted">
                                    <i class="fa-solid fa-basket-shopping display-6"></i>
                                </div>
                                <p class="fw-bold text-secondary mb-0" style="font-family: 'Kantumruy Pro', sans-serif;">លោកអ្នកមិនទាន់មានប្រវត្តិការបញ្ជាទិញនៅឡើយទេ!</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @foreach($orders as $order)
        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content order-modal border-0 shadow-lg rounded-4 overflow-hidden bg-white">

                    <div class="modal-header modal-header-green bg-success text-white py-3 px-4 rounded-top-4 border-0">
                        <h5 class="modal-title fw-bold d-flex align-items-center" style="font-size: 16px; font-family: 'Kantumruy Pro', sans-serif;">
                            <i class="fa-solid fa-receipt me-2" style="font-size: 18px;"></i>
                            ព័ត៌មានលម្អិតវិក្កយបត្រ
                            <span class="ms-2 font-monospace text-white bg-white bg-opacity-20 px-2 py-0.5 rounded" style="font-size: 14px;">
                                #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4 text-start">
                        <div class="card border-0 rounded-3 mb-4" style="background-color: #f8f9fa;">
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-dark mb-3" style="font-size: 14px; font-family: 'Kantumruy Pro', sans-serif;">
                                    <i class="fa-solid fa-truck text-success me-2"></i> ព័ត៌មានដឹកជញ្ជូន និងអ្នកទទួល
                                </h6>
                                <div class="row g-3" style="font-size: 13.5px;">
                                    <div class="col-sm-6">
                                        <span class="text-muted small d-block">ឈ្មោះអ្នកទទួល៖</span>
                                        <span class="fw-bold text-dark">{{ $order->customer_name }}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="text-muted small d-block">លេខទូរស័ព្ទ៖</span>
                                        <span class="fw-bold text-dark font-monospace">{{ $order->customer_phone }}</span>
                                    </div>
                                    <div class="col-12 border-top pt-2 mt-2 border-muted border-opacity-10">
                                        <span class="text-muted small d-block">អាសយដ្ឋានដឹកជញ្ជូនលម្អិត៖</span>
                                        <span class="text-dark fw-semibold">{{ $order->delivery_address }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold text-dark mb-3" style="font-size: 14px; font-family: 'Kantumruy Pro', sans-serif;">
                            <i class="fa-solid fa-seedling text-success me-2"></i> បញ្ជីគ្រាប់ពូជដែលបានទិញ (Order Items)
                        </h6>
                        <div class="table-responsive rounded-3 border bg-white shadow-sm">
                            <table class="table table-borderless align-middle m-0" style="font-size: 14px;">
                                <thead class="bg-light border-bottom text-muted small fw-bold" style="background-color: #f8f9fa !important;">
                                    <tr>
                                        <th class="ps-3 py-2.5">គ្រាប់ពូជ</th>
                                        <th class="text-center py-2.5">តម្លៃរាយ</th>
                                        <th class="text-center py-2.5">ចំនួន</th>
                                        <th class="text-end pe-3 py-2.5">សរុប</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($order->items as $item)
                                    <tr class="border-bottom border-bottom-dashed">
                                        <td class="ps-3 py-3 fw-bold text-dark">
                                            {{ App::isLocale('en') ? ($item->seed?->name_en ?? 'Deleted Seed Product') : ($item->seed?->name_kh ?? 'គ្រាប់ពូជត្រូវបានលុបពីប្រព័ន្ធ') }}

                                            @if($item->seed && $item->seed->batch_number)
                                                <span class="d-block text-muted mt-1 font-monospace" style="font-size: 11px; font-weight: normal;">
                                                    <i class="fa-solid fa-qrcode text-success me-1"></i> បាច់លេខ៖ {{ $item->seed->batch_number }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center text-secondary font-monospace">${{ number_format($item->price, 2) }}</td>
                                        <td class="text-center fw-bold text-dark font-monospace">x{{ $item->quantity }}</td>
                                        <td class="text-end pe-3 fw-bold text-success font-monospace">${{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($order->coupon_code)
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top small text-success">
                                <span><i class="fa-solid fa-tag me-1"></i>លេខកូដបញ្ចុះតម្លៃ ({{ $order->coupon_code }}):</span>
                                <span class="fw-bold font-monospace">- ${{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top border-2 border-muted border-opacity-25">
                            <h5 class="fw-bold text-dark m-0" style="font-size: 15.5px; font-family: 'Kantumruy Pro', sans-serif;">ទឹកប្រាក់សរុបរួម៖</h5>
                            <h4 class="fw-bold text-danger m-0 font-monospace" style="font-size: 22px;">${{ number_format($order->total_price, 2) }}</h4>
                        </div>
                    </div>

                    <div class="modal-footer bg-light border-0 py-2.5 px-4 rounded-bottom-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-success fw-bold rounded-pill px-3 btn-sm"
                                onclick="printCustomerReceipt({{ json_encode([
                                    'id' => str_pad($order->id, 5, '0', STR_PAD_LEFT),
                                    'date' => $order->created_at ? $order->created_at->format('d-M-Y H:i') : '',
                                    'customer_name' => $order->customer_name,
                                    'customer_phone' => $order->customer_phone,
                                    'delivery_address' => $order->delivery_address,
                                    'total_price' => number_format($order->total_price, 2),
                                    'items' => $order->items->map(fn($it) => [
                                        'name' => $it->seed?->name_kh ?? 'គ្រាប់ពូជ',
                                        'batch' => $it->seed?->batch_number ?? '---',
                                        'quantity' => $it->quantity,
                                        'price' => number_format($it->price, 2),
                                        'subtotal' => number_format($it->subtotal, 2),
                                    ]),
                                ]) }})">
                            <i class="fa-solid fa-print me-1"></i> ព្រីនវិក្កយបត្រ (Print Receipt)
                        </button>
                        <button type="button" class="btn btn-secondary fw-bold rounded-pill px-4 btn-sm" data-bs-dismiss="modal">
                            បិទផ្ទាំង
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

</div>

<style>
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.03); }
        100% { transform: scale(1); }
    }
    .animate-pulse {
        animation: pulse 2s infinite ease-in-out;
    }
    .border-bottom-dashed {
        border-bottom: 1px dashed #dee2e6 !important;
    }
    .custom-status-badge {
        font-family: 'Kantumruy Pro', sans-serif;
        font-size: 12px !important;
        font-weight: 700 !important;
        padding: 6px 14px !important;
        border-radius: 50px !important;
        display: inline-flex;
        align-items: center;
    }
    .badge-warning-amber {
        background-color: rgba(255, 193, 7, 0.15) !important;
        color: #ff9800 !important;
    }
    .badge-primary-blue {
        background-color: rgba(13, 110, 253, 0.15) !important;
        color: #0d6efd !important;
    }
    .badge-success-green {
        background-color: rgba(25, 135, 84, 0.15) !important;
        color: #198754 !important;
    }
</style>

<script>
    function printCustomerReceipt(order) {
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
                <title>Receipt - #ORD-${order.id}</title>
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
                        <small style="color: #64748b;">វិក្កយបត្រទិញគ្រាប់ពូជកសិកម្មផ្លូវការ (Customer Invoice)</small>
                    </div>
                    <div style="text-align: right;">
                        <div class="invoice-id">#ORD-${order.id}</div>
                        <small style="color: #64748b;">កាលបរិច្ឆេទ៖ ${order.date}</small>
                    </div>
                </div>

                <div class="customer-box">
                    <strong style="display: block; margin-bottom: 8px; color: #198754;">ព័ត៌មានអ្នកទទួលទំនិញ (Customer Delivery)៖</strong>
                    <div><strong>ឈ្មោះ៖</strong> ${order.customer_name}</div>
                    <div><strong>លេខទូរស័ព្ទ៖</strong> <span style="font-family: monospace;">${order.customer_phone}</span></div>
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

@endsection
