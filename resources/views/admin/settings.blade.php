@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin-style.css') }}">

@section('admin_content')
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="fa-solid fa-circle-check fs-5 me-2 text-success"></i>
            <span class="fw-bold text-success">{{ session('success') }}</span>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">
                <i class="fa-solid fa-gear text-success me-2"></i>ការកំណត់ទូទៅសហគមន៍ & ប្រព័ន្ធ (Settings)
            </h4>
            <p class="text-muted small mb-0">កំណត់ព័ត៌មានកសិដ្ឋាន ទំនាក់ទំនង ថ្លៃដឹកជញ្ជូន គណនីបាគង និងកម្រិតជូនដំណឹងស្តុក</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        <div class="row g-4">
            <!-- 🌾 CARD 1: ព័ត៌មានទូទៅសហគមន៍ -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                    <h5 class="fw-bold text-success mb-3 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                        <i class="fa-solid fa-tractor me-2"></i>ព័ត៌មានទូទៅសហគមន៍ (Cooperative Profile)
                    </h5>
                    <hr class="text-muted opacity-25 mb-4">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">ឈ្មោះសហគមន៍ (ភាសាខ្មែរ) <span class="text-danger">*</span></label>
                        <input type="text" name="cooperative_name_kh" class="form-control rounded-3" value="{{ $settings['cooperative_name_kh'] ?? 'សហគមន៍កសិកម្មសុវត្ថិភាពតាក្រាម (Grow2Growth)' }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">ឈ្មោះសហគមន៍ (English) <span class="text-danger">*</span></label>
                        <input type="text" name="cooperative_name_en" class="form-control rounded-3" value="{{ $settings['cooperative_name_en'] ?? 'Takream Safe Agriculture Cooperative (Grow2Growth)' }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">អាសយដ្ឋានទីស្នាក់ការ (ភាសាខ្មែរ)</label>
                        <textarea name="farm_address_kh" rows="2" class="form-control rounded-3">{{ $settings['farm_address_kh'] ?? 'ភូមិតាក្រាម ឃុំតាក្រាម ស្រុកបាណន់ ខេត្តបាត់ដំបង' }}</textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-bold text-dark">អាសយដ្ឋានទីស្នាក់ការ (English)</label>
                        <textarea name="farm_address_en" rows="2" class="form-control rounded-3">{{ $settings['farm_address_en'] ?? 'Takream Village, Banan District, Battambang Province' }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 📞 CARD 2: ទំនាក់ទំនង & បណ្តាញសង្គម -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                    <h5 class="fw-bold text-success mb-3 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                        <i class="fa-solid fa-headset me-2"></i>ទំនាក់ទំនង & បណ្តាញសង្គម (Contact Channels)
                    </h5>
                    <hr class="text-muted opacity-25 mb-4">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">លេខទូរស័ព្ទ Hotline ជំនួយកសិករ <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-success"><i class="fa-solid fa-phone"></i></span>
                            <input type="text" name="hotline_phone" class="form-control rounded-end-3 font-monospace" value="{{ $settings['hotline_phone'] ?? '0883322318' }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">តំណភ្ជាប់ Telegram ជំនួយបច្ចេកទេស <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-primary"><i class="fa-brands fa-telegram"></i></span>
                            <input type="url" name="telegram_link" class="form-control rounded-end-3" value="{{ $settings['telegram_link'] ?? 'https://t.me/Hor_Tola12' }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">អ៊ីមែលផ្លូវការ (Official Email)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-danger"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="contact_email" class="form-control rounded-end-3" value="{{ $settings['contact_email'] ?? 'support@smartagro.kh' }}">
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3 mt-4">
                        <small class="text-muted d-block">
                            <i class="fa-solid fa-circle-info text-info me-1"></i>
                            ព័ត៌មានទំនាក់ទំនងទាំងនេះ នឹងបង្ហាញដោយស្វ័យប្រវត្តិនៅលើ Footer, Right Floating Dock និងក្បាលទំព័រគេហទំព័រ។
                        </small>
                    </div>
                </div>
            </div>

            <!-- ⚠️ CARD 3: កម្រិតប្រកាសអាសន្ន & ជូនដំណឹង -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                    <h5 class="fw-bold text-success mb-3 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                        <i class="fa-solid fa-bell me-2"></i>កម្រិតប្រកាសអាសន្ន & ជូនដំណឹង (Alert Thresholds)
                    </h5>
                    <hr class="text-muted opacity-25 mb-4">

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-dark">កម្រិតស្តុកតិចត្រូវជូនដំណឹង (Low Stock Threshold - កញ្ចប់)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-warning"><i class="fa-solid fa-boxes-stacked"></i></span>
                            <input type="number" name="low_stock_threshold" class="form-control rounded-end-3 font-monospace" min="1" max="100" value="{{ $settings['low_stock_threshold'] ?? '5' }}" required>
                        </div>
                        <small class="text-muted" style="font-size: 11px;">នៅពេលគ្រាប់ពូជសល់ស្មើ ឬក្រោមចំនួននេះ ប្រព័ន្ធនឹងលោត Alert លើ Navbar និង Dashboard។</small>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-bold text-dark">កម្រិតជូនដំណឹងវគ្គផលិតជិតផុតកំណត់ (Expiry Warning Days - ថ្ងៃ)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-danger"><i class="fa-solid fa-calendar-xmark"></i></span>
                            <input type="number" name="batch_expiry_warning_days" class="form-control rounded-end-3 font-monospace" min="10" max="365" value="{{ $settings['batch_expiry_warning_days'] ?? '60' }}" required>
                        </div>
                        <small class="text-muted" style="font-size: 11px;">ប្រព័ន្ធនឹងរំលឹកទុកជាមុននូវវគ្គផលិតដែលជិតផុតកំណត់ក្នុងរយៈពេលនេះ (លំនាំដើម៖ ៦០ ថ្ងៃ)។</small>
                    </div>
                </div>
            </div>

            <!-- 💳 CARD 4: គណនីបង់ប្រាក់ & សេវាដឹកជញ្ជូន -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                    <h5 class="fw-bold text-success mb-3 d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                        <i class="fa-solid fa-qrcode me-2"></i>គណនីបង់ប្រាក់បាគង & សេវាដឹកជញ្ជូន (Payment & Shipping)
                    </h5>
                    <hr class="text-muted opacity-25 mb-4">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">ឈ្មោះម្ចាស់គណនីបាគង KHQR (Account Name)</label>
                        <input type="text" name="bakong_account_name" class="form-control text-uppercase font-monospace rounded-3" value="{{ $settings['bakong_account_name'] ?? 'HOR TOLA' }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">លេខគណនី / Bakong ID</label>
                        <input type="text" name="bakong_account_number" class="form-control font-monospace rounded-3" value="{{ $settings['bakong_account_number'] ?? 'tola_hor@aclb' }}">
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-bold text-dark">តម្លៃសេវាដឹកជញ្ជូនលំនាំដើម ($)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-success"><i class="fa-solid fa-truck"></i></span>
                            <input type="number" step="0.01" name="default_shipping_fee" class="form-control rounded-end-3 font-monospace" value="{{ $settings['default_shipping_fee'] ?? '1.50' }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button Row -->
            <div class="col-12 text-end pt-2">
                <a href="{{ url('/admin/dashboard') }}" class="btn btn-light rounded-pill px-4 me-2 fw-bold border">
                    បោះបង់
                </a>
                <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm py-2.5">
                    <i class="fa-solid fa-save me-1.5"></i> រក្សាទុកការកំណត់ទាំងអស់
                </button>
            </div>
        </div>
    </form>
@endsection

