@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

@section('admin_content')
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">គ្រប់គ្រងការកម្ម៉ង់ទិញ (Orders)</h4>
        <p class="text-muted small mb-0">ពិនិត្យមើលបញ្ជីឈ្មោះអ្នកទិញគ្រាប់ពូជ និងគ្រប់គ្រងស្ថានភាពដឹកជញ្ជូនក្នុងប្រព័ន្ធ</p>
    </div>

    <div class="card admin-main-card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle admin-table admin-dashboard-table mb-0" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th class="ps-4">លេខវិក្កយបត្រ</th>
                            <th>ព័ត៌មានអតិថិជន</th>
                            <th width="30%">គ្រាប់ពូជដែលកម្ម៉ង់ (ចំនួន x តម្លៃ)</th>
                            <th>ទឹកប្រាក់សរុប</th>
                            <th>ស្ថានភាព</th>
                            <th class="text-center" width="18%">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold text-success">#ORD-{{ $order->id }}</td>

                            <td>
                                <strong class="text-dark d-block mb-1">{{ $order->customer_name }}</strong>
                                <small class="text-muted d-block mb-1"><i class="fa-solid fa-phone me-1 text-success"></i>{{ $order->customer_phone }}</small>
                                <small class="text-muted d-block" style="font-size: 12px; max-width: 250px;"><i class="fa-solid fa-location-dot me-1 text-danger"></i>{{ $order->delivery_address }}</small>
                            </td>

                            <td>
                                <div class="order-item-list-wrapper">
                                    @foreach($order->items as $item)
                                        <div class="order-item-compact-box">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $item->seed?->image ? asset('uploads/seeds/' . $item->seed->image) : 'https://via.placeholder.com/40' }}"
                                                    class="order-item-img-mini"
                                                    alt="Seed">

                                                <div>
                                                    <span class="text-dark fw-bold d-block small mb-0" style="line-height: 1.3;">
                                                        {{ $item->seed?->name_kh ?? 'គ្រាប់ពូជលុបចេញពីប្រព័ន្ធ' }}
                                                    </span>
                                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill" style="font-size: 10px; padding: 2px 6px;">
                                                        x{{ $item->quantity }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <span class="text-muted small fw-bold">${{ number_format($item->price * $item->quantity, 2) }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            <td class="text-danger fw-bold fs-6">${{ number_format($order->total_price, 2) }}</td>

                            <td>
                                @if($order->status == 'pending')
                                    <span class="soft-badge soft-badge-warning">
                                        <i class="fa-solid fa-clock me-1"></i>រង់ចាំពិនិត្យ
                                    </span>
                                @else
                                    <span class="soft-badge soft-badge-success">
                                        <i class="fa-solid fa-circle-check me-1"></i>ដឹកជញ្ជូនរួច
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    @if($order->status == 'pending')
                                        <form action="{{ url('/admin/orders/status/' . $order->id) }}" method="POST" id="status-form-{{ $order->id }}" class="m-0">
                                            @csrf
                                            <button type="button" class="btn btn-success btn-sm admin-btn-ship shadow-sm btn-ship" data-id="{{ $order->id }}">
                                                <i class="fa-solid fa-truck me-1"></i> ដឹកជញ្ជូន
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small fw-bold px-2 py-1 bg-light rounded border"><i class="fa-solid fa-check-double text-success me-1"></i>រួចរាល់</span>
                                    @endif

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
                                <h6 class="fw-bold text-secondary">មិនទាន់មានការកម្ម៉ង់ទិញណាមួយពីកសិករនៅឡើយទេ</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        //  Pop-up សម្រាប់ប៊ូតុង "បញ្ជាក់ការដឹកជូន"
        document.querySelectorAll('.btn-ship').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'បញ្ជាក់ការដឹកជញ្ជូន?',
                    text: "តើអ្នកបានរៀបចំផ្ញើទំនិញនេះជូនអតិថិជនរួចរាល់ហើយមែនទេ?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="fa-solid fa-truck me-1"></i> រួចរាល់ហើយ',
                    cancelButtonText: 'មិនទាន់ទេ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`status-form-${orderId}`).submit();
                    }
                });
            });
        });

        //  Pop-up សម្រាប់ប៊ូតុង "លុបការកម្ម៉ង់"
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const orderId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'តើអ្នកប្រាកដទេ?',
                    text: "ប្រវត្តិវិក្កយបត្រនេះនឹងត្រូវបាត់បង់ទាំងស្រុងពីប្រព័ន្ធ!",
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
</script>
