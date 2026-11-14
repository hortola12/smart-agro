@extends('layouts.app')

@section('content')
<div class="container py-4 text-dark">
    <!-- Header Hero -->
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8 text-center">
            <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow mb-3" style="width: 65px; height: 65px;">
                <i class="fa-solid fa-truck-fast fs-3"></i>
            </div>
            <h3 class="fw-bold text-dark mb-2" style="font-family: 'Kantumruy Pro', sans-serif;">
                តាមដានស្ថានភាពការកម្ម៉ង់ (Order Tracking)
            </h3>
            <p class="text-muted small px-md-4 mb-4">
                សូមវាយបញ្ចូល <b class="text-success">លេខទូរស័ព្ទ</b> ដែលលោកអ្នកបានប្រើពេលបញ្ជាទិញ ឬ <b class="text-success">លេខកូដវិក្កយបត្រ (ឧ. #ORD-00001)</b> ដើម្បីពិនិត្យមើលដំណាក់កាលវេចខ្ចប់ និងដឹកជញ្ជូន។
            </p>

            <!-- Search Bar -->
            <form action="{{ url('/order-tracking') }}" method="GET" class="shadow-sm rounded-pill p-2 border border-success bg-white d-flex align-items-center mb-2 mx-auto" style="max-width: 580px;">
                <div class="input-group align-items-center">
                    <span class="input-group-text bg-white border-0 text-success ps-3">
                        <i class="fa-solid fa-magnifying-glass fs-5"></i>
                    </span>
                    <input type="text"
                           name="search"
                           class="form-control border-0 px-2 fs-6 shadow-none"
                           placeholder="វាយលេខទូរស័ព្ទ ឬលេខវិក្កយបត្រ... (ឧ. 012345678 ឬ ORD-00001)"
                           value="{{ $search }}"
                           required>
                    <button class="btn btn-success rounded-pill px-4 fw-bold" type="submit" style="font-size: 14px; height: 42px;">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> ស្វែងរក
                    </button>
                </div>
            </form>
            @if($search)
                <small class="text-muted">
                    លទ្ធផលសម្រាប់៖ <span class="fw-bold text-dark font-monospace">"{{ $search }}"</span> —
                    <a href="{{ url('/order-tracking') }}" class="text-decoration-none text-success small fw-bold">ស្វែងរកឡើងវិញ</a>
                </small>
            @endif
        </div>
    </div>

    <!-- Results Section -->
    <div class="row justify-content-center">
        <div class="col-lg-9">
            @if($search !== '' && $orders->isEmpty())
                <div class="card border-0 shadow-sm rounded-4 text-center p-5 bg-white">
                    <div class="text-muted mb-3 opacity-50">
                        <i class="fa-solid fa-box-open fa-3x"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">រកមិនឃើញទិន្នន័យការកម្ម៉ង់ឡើយ</h5>
                    <p class="text-muted small mb-4">
                        យើងមិនបានរកឃើញការកម្ម៉ង់ដែលត្រូវនឹង <span class="fw-bold text-dark">"{{ $search }}"</span> ឡើយ។ <br>
                        សូមពិនិត្យមើលលេខទូរស័ព្ទ ឬលេខវិក្កយបត្រឡើងវិញ ឬទាក់ទងមកផ្នែកបម្រើអតិថិជនរបស់យើង។
                    </p>
                    <div>
                        <a href="tel:053952888" class="btn btn-outline-success rounded-pill px-4 fw-bold btn-sm">
                            <i class="fa-solid fa-phone me-1.5"></i> ទូរស័ព្ទជំនួយ៖ 053 952 888
                        </a>
                    </div>
                </div>
            @elseif($orders->isNotEmpty())
                @foreach($orders as $order)
                    @php
                        $status = strtolower(trim($order->status));
                        // Steps: 1: Placed, 2: Processing, 3: Shipping, 4: Completed
                        $currentStep = 1;
                        if ($status === 'processing') {
                            $currentStep = 2;
                        } elseif ($status === 'shipping') {
                            $currentStep = 3;
                        } elseif ($status === 'completed' || $status === 'success') {
                            $currentStep = 4;
                        } elseif ($status === 'cancelled') {
                            $currentStep = 0;
                        }
                    @endphp

                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
                        <!-- Order Card Header -->
                        <div class="card-header bg-white border-bottom p-3 p-md-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <span class="text-muted small d-block">លេខវិក្កយបត្រ</span>
                                <span class="fw-bold text-success font-monospace fs-5">
                                    #ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                </span>
                                <span class="text-muted small ms-2">
                                    <i class="fa-regular fa-clock me-1"></i>{{ $order->created_at ? $order->created_at->format('d-M-Y H:i') : '' }}
                                </span>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                @if($status === 'cancelled')
                                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold">
                                        <i class="fa-solid fa-circle-xmark me-1"></i> បានបោះបង់ (Cancelled)
                                    </span>
                                @elseif($status === 'completed' || $status === 'success')
                                    <span class="badge bg-success rounded-pill px-3 py-2 fw-bold shadow-sm">
                                        <i class="fa-solid fa-circle-check me-1"></i> បានប្រគល់ជោគជ័យ
                                    </span>
                                @elseif($status === 'shipping')
                                    <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold shadow-sm">
                                        <i class="fa-solid fa-truck-fast me-1"></i> កំពុងដឹកជញ្ជូន
                                    </span>
                                @elseif($status === 'processing')
                                    <span class="badge bg-info text-dark rounded-pill px-3 py-2 fw-bold">
                                        <i class="fa-solid fa-boxes-packing me-1"></i> កំពុងរៀបចំវេចខ្ចប់
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-bold">
                                        <i class="fa-solid fa-hourglass-half me-1"></i> រង់ចាំពិនិត្យ (Pending)
                                    </span>
                                @endif

                                <button onclick="window.print()" class="btn btn-light btn-sm border rounded-pill px-3 fw-bold text-secondary shadow-sm" title="ព្រីនវិក្កយបត្រ">
                                    <i class="fa-solid fa-print me-1"></i> ព្រីន
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-3 p-md-4">
                            <!-- Visual Progress Stepper (Only show if not cancelled) -->
                            @if($status !== 'cancelled')
                                <div class="tracking-stepper-wrapper mb-4 py-3 px-2 bg-light rounded-4">
                                    <div class="row text-center position-relative g-2">
                                        <!-- Step 1 -->
                                        <div class="col-3">
                                            <div class="stepper-node mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm {{ $currentStep >= 1 ? 'bg-success' : 'bg-secondary' }}" style="width: 40px; height: 40px;">
                                                <i class="fa-solid fa-receipt"></i>
                                            </div>
                                            <span class="d-block fw-bold small text-dark">បញ្ជាទិញរួច</span>
                                            <small class="text-muted d-none d-md-block" style="font-size: 11px;">ទទួលការកម្ម៉ង់</small>
                                        </div>
                                        <!-- Step 2 -->
                                        <div class="col-3">
                                            <div class="stepper-node mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm {{ $currentStep >= 2 ? 'bg-success' : 'bg-secondary bg-opacity-50' }}" style="width: 40px; height: 40px;">
                                                <i class="fa-solid fa-box"></i>
                                            </div>
                                            <span class="d-block fw-bold small {{ $currentStep >= 2 ? 'text-dark' : 'text-muted' }}">រៀបចំវេចខ្ចប់</span>
                                            <small class="text-muted d-none d-md-block" style="font-size: 11px;">វេចខ្ចប់គ្រាប់ពូជ</small>
                                        </div>
                                        <!-- Step 3 -->
                                        <div class="col-3">
                                            <div class="stepper-node mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm {{ $currentStep >= 3 ? 'bg-success' : 'bg-secondary bg-opacity-50' }}" style="width: 40px; height: 40px;">
                                                <i class="fa-solid fa-truck"></i>
                                            </div>
                                            <span class="d-block fw-bold small {{ $currentStep >= 3 ? 'text-dark' : 'text-muted' }}">កំពុងដឹកជញ្ជូន</span>
                                            <small class="text-muted d-none d-md-block" style="font-size: 11px;">តាមផ្លូវទៅដល់</small>
                                        </div>
                                        <!-- Step 4 -->
                                        <div class="col-3">
                                            <div class="stepper-node mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm {{ $currentStep >= 4 ? 'bg-success' : 'bg-secondary bg-opacity-50' }}" style="width: 40px; height: 40px;">
                                                <i class="fa-solid fa-circle-check"></i>
                                            </div>
                                            <span class="d-block fw-bold small {{ $currentStep >= 4 ? 'text-dark' : 'text-muted' }}">បានប្រគល់</span>
                                            <small class="text-muted d-none d-md-block" style="font-size: 11px;">ជោគជ័យ ១០០%</small>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row g-3 mb-4">
                                <!-- Recipient Information -->
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-4 h-100 border">
                                        <h6 class="fw-bold text-success mb-2 small text-uppercase">
                                            <i class="fa-solid fa-user me-1"></i> ព័ត៌មានអ្នកទទួលទំនិញ
                                        </h6>
                                        <div class="fw-bold text-dark fs-6">{{ $order->customer_name }}</div>
                                        <div class="text-muted small mt-1">
                                            <i class="fa-solid fa-phone me-1.5 text-success"></i>
                                            <a href="tel:{{ $order->customer_phone }}" class="text-decoration-none text-dark fw-bold font-monospace">{{ $order->customer_phone }}</a>
                                        </div>
                                        <div class="text-muted small mt-2">
                                            <i class="fa-solid fa-location-dot me-1.5 text-danger"></i>
                                            <span>{{ $order->delivery_address }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Summary -->
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded-4 h-100 border d-flex flex-column justify-content-between">
                                        <h6 class="fw-bold text-success mb-2 small text-uppercase">
                                            <i class="fa-solid fa-money-bill-wave me-1"></i> ស្ថានភាពការទូទាត់
                                        </h6>
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-1 small text-muted">
                                                <span>វិធីសាស្ត្រទូទាត់៖</span>
                                                <span class="fw-bold text-dark">ទូទាត់ពេលទំនិញមកដល់ (COD)</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mb-1 small text-muted">
                                                <span>ចំនួនមុខទំនិញ៖</span>
                                                <span class="fw-bold text-dark">{{ $order->items->count() }} មុខ</span>
                                            </div>
                                        </div>
                                        <div class="border-top pt-2 mt-2 d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-dark">ទឹកប្រាក់សរុប៖</span>
                                            <span class="fw-bold text-danger fs-5 font-monospace">${{ number_format($order->total_price, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ordered Items List -->
                            <h6 class="fw-bold text-dark mb-3" style="font-family: 'Kantumruy Pro', sans-serif;">
                                <i class="fa-solid fa-seedling text-success me-1.5"></i> បញ្ជីគ្រាប់ពូជដែលបានកម្ម៉ង់
                            </h6>
                            <div class="table-responsive rounded-3 border">
                                <table class="table align-middle mb-0" style="font-size: 13.5px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3 py-2.5">មុខទំនិញ</th>
                                            <th class="py-2.5 text-center">តម្លៃរាយ</th>
                                            <th class="py-2.5 text-center">ចំនួន</th>
                                            <th class="py-2.5 text-end pe-3">សរុប</th>
                                            <th class="py-2.5 text-center">ប្រភពដើម</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td class="ps-3 py-2.5">
                                                    <div class="d-flex align-items-center gap-2.5">
                                                        <img src="{{ $item->seed?->image ? asset('uploads/seeds/' . $item->seed->image) : 'https://via.placeholder.com/45' }}"
                                                             class="rounded-3 border" style="width: 42px; height: 42px; object-fit: cover;" alt="Seed">
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $item->seed?->name_kh ?? 'គ្រាប់ពូជលុបចេញ' }}</div>
                                                            <small class="text-muted font-monospace">{{ $item->seed?->batch_number ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center font-monospace">${{ number_format($item->price, 2) }}</td>
                                                <td class="text-center fw-bold">{{ $item->quantity }} កញ្ចប់</td>
                                                <td class="text-end pe-3 fw-bold text-danger font-monospace">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                                <td class="text-center">
                                                    @if($item->seed && $item->seed->batch_number)
                                                        <a href="{{ url('/trace-batch?search=' . $item->seed->batch_number) }}" class="btn btn-outline-success btn-sm rounded-pill px-3 py-1 fw-bold" style="font-size: 11.5px;">
                                                            <i class="fa-solid fa-qrcode me-1"></i> ពិនិត្យប្រភព
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">---</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Default State: How to track guide -->
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white text-center">
                    <div class="row g-4 align-items-center justify-content-center">
                        <div class="col-md-4 text-center">
                            <div class="p-3 bg-light rounded-4">
                                <i class="fa-solid fa-mobile-screen-button text-success fa-3x mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">១. វាយលេខទូរស័ព្ទ</h6>
                                <p class="text-muted small mb-0">ប្រើប្រាស់លេខទូរស័ព្ទដែលលោកអ្នកបានចុះឈ្មោះកាលពីពេលបញ្ជាទិញ</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-3 bg-light rounded-4">
                                <i class="fa-solid fa-timeline text-primary fa-3x mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">២. មើលដំណាក់កាល</h6>
                                <p class="text-muted small mb-0">តាមដានពីពេលរៀបចំវេចខ្ចប់ រហូតដល់អ្នកដឹកជញ្ជូនយកទៅដល់ដៃ</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-3 bg-light rounded-4">
                                <i class="fa-solid fa-certificate text-warning fa-3x mb-3"></i>
                                <h6 class="fw-bold text-dark mb-1">៣. ធានាគុណភាព</h6>
                                <p class="text-muted small mb-0">ពិនិត្យមើលប្រភពដើមគ្រាប់ពូជ និងអត្រាដុះលូតលាស់ពីកសិដ្ឋាន</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

