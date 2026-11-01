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
            <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">គ្រប់គ្រងលេខកូដបញ្ចុះតម្លៃ (Coupons & Promo Codes)</h4>
            <p class="text-muted small mb-0">បង្កើត និងគ្រប់គ្រងប័ណ្ណបញ្ចុះតម្លៃជូនកសិករពេលបញ្ជាទិញគ្រាប់ពូជ</p>
        </div>
        <button class="btn btn-success fw-bold rounded-pill px-4 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#createCouponCollapse">
            <i class="fa-solid fa-plus me-1.5"></i> បង្កើតលេខកូដថ្មី
        </button>
    </div>

    <!-- 📊 STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-4 bg-primary bg-opacity-10 text-primary p-3 me-3 fs-3">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block">ប័ណ្ណបញ្ចុះតម្លៃសរុប</small>
                        <h3 class="fw-bold text-dark m-0 font-monospace">{{ $totalCoupons }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-4 bg-success bg-opacity-10 text-success p-3 me-3 fs-3">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block">កំពុងដំណើរការ (Active)</small>
                        <h3 class="fw-bold text-success m-0 font-monospace">{{ $activeCoupons }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-4 bg-warning bg-opacity-15 text-warning p-3 me-3 fs-3">
                        <i class="fa-solid fa-tags"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block">ការលើកទឹកចិត្តកសិករ</small>
                        <h5 class="fw-bold text-dark m-0">ជំរុញការបញ្ជាទិញ</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 📝 CREATE NEW COUPON (COLLAPSIBLE) -->
    <div class="collapse mb-4 {{ $errors->any() ? 'show' : '' }}" id="createCouponCollapse">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <h5 class="fw-bold text-success mb-3 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                <i class="fa-solid fa-plus-circle me-2"></i>បង្កើតប័ណ្ណបញ្ចុះតម្លៃថ្មី (Create New Coupon)
            </h5>

            @if($errors->any())
                <div class="alert alert-danger rounded-3 mb-3 small">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('/admin/coupons') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">លេខកូដបញ្ចុះតម្លៃ (Code) <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase font-monospace rounded-3" placeholder="ឧ. PROMO20" value="{{ old('code') }}" required>
                        <small class="text-muted" style="font-size: 11px;">អក្សរធំ និងលេខ (អូតូម៉ាទិក)</small>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label small fw-bold text-muted">ការពិពណ៌នា (Description)</label>
                        <input type="text" name="description" class="form-control rounded-3" placeholder="ឧ. បញ្ចុះតម្លៃពិសេសសម្រាប់រដូវដាំដុះថ្មី" value="{{ old('description') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">ប្រភេទបញ្ចុះតម្លៃ <span class="text-danger">*</span></label>
                        <select name="discount_type" class="form-select rounded-3" required>
                            <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>ភាគរយ (%)</option>
                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>ទឹកប្រាក់កំណត់ ($)</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">តម្លៃបញ្ចុះ (Value) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="discount_value" class="form-control rounded-3 font-monospace" placeholder="ឧ. 10 (ស្មើ 10% ឬ $10)" value="{{ old('discount_value') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">តម្លៃបញ្ជាទិញអប្បបរមា ($)</label>
                        <input type="number" step="0.01" name="min_order_amount" class="form-control rounded-3 font-monospace" placeholder="0.00" value="{{ old('min_order_amount', '0.00') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">កម្រិតបញ្ចុះអតិបរមា ($)</label>
                        <input type="number" step="0.01" name="max_discount_amount" class="form-control rounded-3 font-monospace" placeholder="ទុកទទេបើមិនកំណត់" value="{{ old('max_discount_amount') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">ចំនួនកំណត់នៃការប្រើប្រាស់ (Usage Limit)</label>
                        <input type="number" name="usage_limit" class="form-control rounded-3 font-monospace" placeholder="ឧ. 100 (ទុកទទេបើមិនកំណត់)" value="{{ old('usage_limit') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-muted">កាលបរិច្ឆេទផុតកំណត់ (Expiry Date)</label>
                        <input type="date" name="expires_at" class="form-control rounded-3" value="{{ old('expires_at') }}">
                    </div>

                    <div class="col-12 text-end pt-2">
                        <button type="button" class="btn btn-light rounded-pill px-4 me-2 fw-bold" data-bs-toggle="collapse" data-bs-target="#createCouponCollapse">
                            បោះបង់
                        </button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm">
                            <i class="fa-solid fa-save me-1.5"></i> រក្សាទុកលេខកូដថ្មី
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 📋 COUPONS TABLE -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small fw-bold">
                    <tr>
                        <th class="ps-4">លេខកូដ (Code)</th>
                        <th>ប្រភេទ & ទំហំបញ្ចុះតម្លៃ</th>
                        <th>លក្ខខណ្ឌអប្បបរមា</th>
                        <th>ការប្រើប្រាស់</th>
                        <th>ផុតកំណត់</th>
                        <th>ស្ថានភាព</th>
                        <th class="text-end pe-4">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $coupon)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-success bg-opacity-10 text-success fs-6 font-monospace px-3 py-2 rounded-pill me-2 border border-success border-opacity-25">
                                        <i class="fa-solid fa-tag me-1"></i>{{ $coupon->code }}
                                    </span>
                                </div>
                                @if($coupon->description)
                                    <small class="text-muted d-block mt-1">{{ $coupon->description }}</small>
                                @endif
                            </td>
                            <td>
                                @if($coupon->discount_type === 'percentage')
                                    <span class="fw-bold text-primary font-monospace fs-6">{{ $coupon->discount_value }}%</span>
                                    <small class="text-muted d-block" style="font-size: 11px;">
                                        @if($coupon->max_discount_amount)
                                            (អតិបរមា ${{ number_format($coupon->max_discount_amount, 2) }})
                                        @else
                                            (មិនកំណត់កម្រិតលើ)
                                        @endif
                                    </small>
                                @else
                                    <span class="fw-bold text-success font-monospace fs-6">${{ number_format($coupon->discount_value, 2) }}</span>
                                    <small class="text-muted d-block" style="font-size: 11px;">(បញ្ចុះតម្លៃកំណត់)</small>
                                @endif
                            </td>
                            <td>
                                @if($coupon->min_order_amount > 0)
                                    <span class="text-dark fw-bold font-monospace small">${{ number_format($coupon->min_order_amount, 2) }}</span>
                                @else
                                    <span class="text-muted small">គ្មាន</span>
                                @endif
                            </td>
                            <td>
                                <span class="font-monospace fw-bold small text-dark">{{ $coupon->used_count }}</span>
                                <span class="text-muted small">/ {{ $coupon->usage_limit ?? '∞' }}</span>
                            </td>
                            <td>
                                @if($coupon->expires_at)
                                    <span class="small font-monospace {{ $coupon->expires_at->isPast() ? 'text-danger fw-bold' : 'text-dark' }}">
                                        {{ $coupon->expires_at->format('d/m/Y') }}
                                        @if($coupon->expires_at->isPast())
                                            (ផុតកំណត់)
                                        @endif
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border rounded-pill">គ្មានផុតកំណត់</span>
                                @endif
                            </td>
                            <td>
                                @if($coupon->is_active)
                                    <span class="badge bg-success bg-opacity-15 text-success rounded-pill px-2.5 py-1 fw-bold">
                                        <i class="fa-solid fa-circle-check me-1"></i>សកម្ម
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-15 text-secondary rounded-pill px-2.5 py-1 fw-bold">
                                        <i class="fa-solid fa-circle-xmark me-1"></i>បានបិទ
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex align-items-center justify-content-end gap-1.5">
                                    <form action="{{ url('/admin/coupons/' . $coupon->id . '/toggle') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm rounded-pill px-2.5 {{ $coupon->is_active ? 'btn-outline-warning text-dark' : 'btn-outline-success' }}" title="{{ $coupon->is_active ? 'បិទដំណើរការ' : 'បើកដំណើរការ' }}">
                                            <i class="fa-solid {{ $coupon->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ url('/admin/coupons/' . $coupon->id) }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបលេខកូដបញ្ចុះតម្លៃនេះមែនទេ?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-2.5" title="លុបលេខកូដ">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-ticket fs-1 opacity-50 mb-2"></i>
                                <p class="mb-0 fw-bold">មិនទាន់មានលេខកូដបញ្ចុះតម្លៃនៅឡើយទេ</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coupons->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
@endsection

