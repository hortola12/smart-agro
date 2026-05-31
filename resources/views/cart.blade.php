@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/cart-style.css') }}">

@section('content')
<div class="container py-4">
    <h4 class="fw-bold text-success mb-4 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
        <span class="cart-title-icon me-3">
            <i class="fa-solid fa-basket-shopping"></i>
        </span>
        <span>កន្ត្រកទំនិញរបស់អ្នក</span>
    </h4>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card cart-card border-0 p-4 shadow-sm mb-4 bg-white rounded-4">
                    <div class="table-responsive">
                        <table class="table align-middle cart-table m-0" style="font-size: 14.5px;">
                            <thead>
                                <tr>
                                    <th>រូបភាព</th>
                                    <th>ឈ្មោះគ្រាប់ពូជ</th>
                                    <th>តម្លៃ</th>
                                    <th width="25%">ចំនួន</th>
                                    <th>សរុប</th>
                                    <th class="text-center">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0 @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity'] @endphp
                                    <tr data-id="{{ $id }}">
                                        <td>
                                            <img src="{{ $details['image'] ? asset('uploads/seeds/' . $details['image']) : 'https://via.placeholder.com/60' }}" class="cart-product-image shadow-sm border rounded-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark d-block mb-1">{{ $details['name'] }}</span>
                                            <small class="text-muted" style="font-size: 11.5px;"><i class="fa-solid fa-circle-check text-success me-1"></i>Premium Agri Seed</small>
                                        </td>
                                        <td class="text-dark fw-bold font-monospace">${{ number_format($details['price'], 2) }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="input-group qty-input-group shadow-sm border rounded-pill overflow-hidden bg-light" style="max-width: 130px; height: 36px;">
                                                    <button class="btn btn-link text-success border-0 px-2 btn-minus text-decoration-none" type="button">
                                                        <i class="fa-solid fa-minus fs-6"></i>
                                                    </button>
                                                    <input type="number"
                                                        value="{{ $details['quantity'] }}"
                                                        class="form-control update-cart-qty border-0 bg-transparent fw-bold text-center px-0 text-dark font-monospace"
                                                        min="1"
                                                        max="{{ $details['stock'] ?? 99 }}"
                                                        readonly>
                                                    <button class="btn btn-link text-success border-0 px-2 btn-plus text-decoration-none" type="button">
                                                        <i class="fa-solid fa-plus fs-6"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-2 ms-2" style="font-size: 11px;">
                                                ស្តុក៖ <b class="text-success">{{ $details['stock'] ?? '99' }}</b> កញ្ចប់
                                            </small>
                                        </td>
                                        <td class="text-danger fw-bold font-monospace">${{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-link text-danger remove-from-cart p-2 text-decoration-none remove-btn" title="ដកទំនិញនេះចេញ">
                                                <i class="fa-solid fa-trash-can fs-5"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card summary-card border-0 p-4 shadow-sm bg-white rounded-4">
                    <h5 class="fw-bold text-dark mb-4 pb-3 border-bottom d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                        <i class="fa-solid fa-receipt text-success me-2"></i>សង្ខេបការបញ្ជាទិញ
                    </h5>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="text-secondary fw-bold">{{ __('total_amount') ?? 'ទឹកប្រាក់សរុប' }}</span>
                        <span class="total-price text-danger fw-bold fs-4 font-monospace">${{ number_format($total, 2) }}</span>
                    </div>

                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                        <i class="fa-solid fa-truck text-success me-2"></i>ព័ត៌មានដឹកជញ្ជូន (Delivery Info)
                    </h6>

                    <form action="{{ url('/checkout') }}" method="POST" class="m-0">
                        @csrf

                        <div class="form-floating mb-3">
                            <input type="text" name="customer_name" class="form-control rounded-3" id="floatingName" placeholder="ឈ្មោះ"
                                value="{{ auth()->check() ? auth()->user()->name : '' }}" required>
                            <label for="floatingName" class="text-muted small fw-bold">
                                ឈ្មោះអ្នកទទួលទំនិញ <span class="text-danger">*</span>
                            </label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="text" name="customer_phone" class="form-control rounded-3" id="floatingPhone" placeholder="លេខទូរស័ព្ទ"
                                value="{{ auth()->check() && isset(auth()->user()->phone) ? auth()->user()->phone : '' }}" required>
                            <label for="floatingPhone" class="text-muted small fw-bold">
                                លេខទូរស័ព្ទទំនាក់ទំនង <span class="text-danger">*</span>
                            </label>
                        </div>

                        <div class="form-floating mb-4">
                            <textarea name="delivery_address" class="form-control rounded-3" id="floatingAddress" placeholder="អាសយដ្ឋាន" style="height: 100px" required>{{ auth()->check() && isset(auth()->user()->address) ? auth()->user()->address : '' }}</textarea>
                            <label for="floatingAddress" class="text-muted small fw-bold">អាសយដ្ឋានដឹកជញ្ជូនលម្អិត <span class="text-danger">*</span></label>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fw-bold btn-lg rounded-pill shadow checkout-btn py-2.5" style="font-size: 16px;">
                            <i class="fa-solid fa-circle-check me-2"></i>រក្សាទុក និងបញ្ជាក់ការទិញ
                        </button>
                    </form>
                </div>
            </div>
            </div>
    @else
        <div class="alert alert-light text-center py-5 border rounded-4 shadow-sm my-4 bg-white">
            <div class="card-body py-4">
                <div class="empty-cart-icon mb-4 shadow-sm bg-success bg-opacity-10 text-success mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-basket-shopping display-5"></i>
                </div>
                <h4 class="text-dark fw-bold mb-2" style="font-family: 'Kantumruy Pro', sans-serif;">កន្ត្រកទំនិញរបស់អ្នកនៅទទេស្អាតឡើយ!</h4>
                <p class="text-muted small mb-4 px-md-5">
                    សូមលោកអ្នកត្រឡប់ទៅកាន់ទំព័រដើម ដើម្បីជ្រើសរើសគ្រាប់ពូជបន្លែដែលលោកអ្នកចង់ដាំដុះ។
                </p>
                <a href="{{ url('/') }}" class="btn btn-success fw-bold rounded-pill px-4 py-2 shadow-sm">
                    <i class="fa-solid fa-arrow-left me-2"></i>ទៅទិញគ្រាប់ពូជបន្លែ
                </a>
            </div>
        </div>
    @endif
</div>

<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {

    // មុខងារកណ្តាលសម្រាប់បញ្ជូនទិន្នន័យកែប្រែទៅកាន់ Backend (Central Update Cart Function)
    function executeCartUpdate(id, newQty) {
        fetch("{{ url('/update-cart') }}", {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ id: id, quantity: newQty })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                Swal.fire({
                    title: 'សុំទោស!',
                    text: data.error,
                    icon: 'warning',
                    confirmButtonColor: '#198754',
                    confirmButtonText: 'យល់ព្រម'
                }).then(() => { window.location.reload(); });
            } else {
                window.location.reload();
            }
        });
    }

    /* --- ១. ចាប់ព្រឹត្តិការណ៍ចុចប៊ូតុង បូក (+) --- */
    document.querySelectorAll('.btn-plus').forEach(function(button) {
        button.addEventListener('click', function() {
            var row = this.closest('tr');
            var id = row.getAttribute('data-id');
            var input = row.querySelector('.update-cart-qty');
            var currentVal = parseInt(input.value);
            var maxStock = parseInt(input.getAttribute('max'));

            if (currentVal < maxStock) {
                var nextQty = currentVal + 1;
                input.value = nextQty;
                executeCartUpdate(id, nextQty); // រត់មុខងារបាញ់ AJAX ភ្លាមៗ
            } else {
                Swal.fire({
                    title: 'លើសចំនួនស្តុក!',
                    text: 'សុំទោស! គ្រាប់ពូជនេះមានក្នុងស្តុកតែ ' + maxStock + ' កញ្ចប់ប៉ុណ្ណោះ។',
                    icon: 'warning',
                    confirmButtonColor: '#198754',
                    confirmButtonText: 'យល់ព្រម'
                });
            }
        });
    });

    /* --- ២. ចាប់ព្រឹត្តិការណ៍ចុចប៊ូតុង ដក (-) --- */
    document.querySelectorAll('.btn-minus').forEach(function(button) {
        button.addEventListener('click', function() {
            var row = this.closest('tr');
            var id = row.getAttribute('data-id');
            var input = row.querySelector('.update-cart-qty');
            var currentVal = parseInt(input.value);

            if (currentVal > 1) {
                var nextQty = currentVal - 1;
                input.value = nextQty;
                executeCartUpdate(id, nextQty); // រត់មុខងារបាញ់ AJAX ភ្លាមៗ
            }
        });
    });

    /* --- ៣. ដកមុខទំនិញចេញពីកន្ត្រក (REMOVE ITEM FROM CART) --- */
    document.querySelectorAll('.remove-from-cart').forEach(function(button) {
        button.addEventListener('click', function() {
            var ele = this;
            var id = ele.closest('tr').getAttribute('data-id');

            Swal.fire({
                title: 'ដកទំនិញចេញ?',
                text: "តើអ្នកពិតជាចង់លុបមុខទំនិញនេះចេញពីកន្ត្រកមែនទេ?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'យល់ព្រមលុប',
                cancelButtonText: 'បោះបង់'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ url('/remove-from-cart') }}", {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(response => {
                        if (response.ok) { window.location.reload(); }
                    });
                }
            });
        });
    });
});
</script>
@endsection
