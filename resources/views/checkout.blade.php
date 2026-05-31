@extends('layouts.app')

@section('content')
<div class="container py-5 text-dark">
    <div class="mb-4">
        <a href="{{ url('/cart') }}" class="btn btn-light btn-sm border fw-bold rounded-pill px-3 shadow-sm text-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> ត្រលប់ទៅកាន់កន្ត្រកទំនិញ
        </a>
    </div>

    <div class="row g-5">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white" style="border-top: 5px solid #198754 !important;">
                <h5 class="fw-bold text-success mb-4" style="font-family: 'Kantumruy Pro', sans-serif;">
                    <i class="fa-solid fa-truck-fast me-2"></i>ព័ត៌មានដឹកជញ្ជូនទំនិញ (Shipping Address)
                </h5>

                <form action="{{ url('/checkout') }}" method="POST" class="m-0">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">ឈ្មោះពេញអ្នកទទួល (Full Name) <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control rounded-3 p-2.5 fw-bold"
                               value="{{ old('customer_name', auth()->check() ? auth()->user()->name : '') }}" required placeholder="ឧ. សុខ ពិសិដ្ឋ">
                        <small class="text-muted" style="font-size: 11px;">* សូមវាយឈ្មោះពិតប្រាកដសម្រាប់អ្នកទទួលទំនិញ</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">អាសយដ្ឋានអ៊ីមែល (Email Address)</label>
                        <input type="email" class="form-control rounded-3 p-2.5"
                               value="{{ auth()->check() ? auth()->user()->email : 'customer@example.com' }}" readonly style="background-color: #f8f9fa;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">លេខទូរស័ព្ទទំនាក់ទំនង (Phone Number) <span class="text-danger">*</span></label>
                        <input type="text" name="customer_phone" class="form-control rounded-3 p-2.5 font-monospace fw-bold"
                               value="{{ old('customer_phone', auth()->check() && isset(auth()->user()->phone) ? auth()->user()->phone : '') }}" required placeholder="ឧ. 012345678">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">អាសយដ្ឋានដឹកជញ្ជូនលម្អិត (Shipping Address) <span class="text-danger">*</span></label>
                        <textarea name="delivery_address" class="form-control rounded-3 p-2.5 small" rows="3" required
                                  placeholder="សូមបញ្ជាក់៖ ផ្ទះលេខ, ផ្លូវ, ភូមិ, ឃុំ, ស្រុក, ខេត្ត... (ឧ. ភូមិតាក្រាម ឃុំតាក្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង)">{{ old('delivery_address', auth()->check() && isset(auth()->user()->address) ? auth()->user()->address : '') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill p-2.5 shadow-sm btn-lg" style="font-size: 16px;">
                        <i class="fa-solid fa-circle-check me-2"></i>រក្សាទុក និងបញ្ជាក់ការបញ្ជាទិញផ្លូវការ
                    </button>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-light border">
                <h5 class="fw-bold text-dark mb-4" style="font-family: 'Kantumruy Pro', sans-serif;">
                    <i class="fa-solid fa-basket-shopping text-success me-2"></i>គ្រាប់ពូជក្នុងកន្ត្រក (Items in Cart)
                </h5>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle m-0">
                        <thead>
                            <tr class="border-bottom" style="font-size: 13px;">
                                <th class="text-muted pb-2">គ្រាប់ពូជ</th>
                                <th class="text-muted text-center pb-2">ចំនួន</th>
                                <th class="text-muted text-end pb-2">តម្លៃ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @if(session('cart') && count(session('cart')) > 0)
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity']; @endphp
                                    <tr class="border-bottom-dashed" style="font-size: 14.5px;">
                                        <td class="py-3 fw-bold text-dark">
                                            {{ $details['name'] }}
                                            <small class="text-muted d-block font-monospace" style="font-size: 11px; font-weight: normal;">${{ number_format($details['price'], 2) }} / កញ្ចប់</small>
                                        </td>
                                        <td class="text-center py-3 text-muted font-monospace fw-bold">x{{ $details['quantity'] }}</td>
                                        <td class="text-end py-3 fw-bold text-success font-monospace">${{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-folder-open d-block fs-3 mb-2 text-secondary"></i>
                                        មិនមានទំនិញក្នុងកន្ត្រកឡើយ
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-2 border-muted border-opacity-25">
                    <h5 class="fw-bold text-dark m-0" style="font-family: 'Kantumruy Pro', sans-serif;">ទឹកប្រាក់សរុប៖</h5>
                    <h4 class="fw-bold text-danger m-0 font-monospace">${{ number_format($total, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
