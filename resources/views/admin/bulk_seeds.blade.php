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

    @if($errors->any())
        <div class="alert alert-danger rounded-3 mb-4 shadow-sm">
            <ul class="mb-0 small">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">
                <i class="fa-solid fa-bolt text-warning me-2"></i>កែសម្រួលស្តុក & តម្លៃជាដុំ (Bulk Fast Editor)
            </h4>
            <p class="text-muted small mb-0">កែប្រែចំនួនស្តុក និងតម្លៃលក់របស់គ្រាប់ពូជទាំងអស់ក្នុងពេលតែមួយ រួចចុច Save តែម្តង</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ url('/admin/seeds') }}" class="btn btn-light border fw-bold rounded-pill px-3 shadow-sm text-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> តារាងគ្រាប់ពូជ
            </a>
            <button type="submit" form="bulkUpdateForm" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm">
                <i class="fa-solid fa-floppy-disk me-1.5"></i> រក្សាទុកទាំងអស់ (Save All)
            </button>
        </div>
    </div>

    <!-- 🔍 FILTER BAR -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ url('/admin/seeds/bulk-update') }}" method="GET" class="row g-2 align-items-center m-0">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="ស្វែងរកតាមឈ្មោះគ្រាប់ពូជ ឬលេខបាច់..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select bg-light">
                    <option value="all">-- ប្រភេទទាំងអស់ (All Categories) --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name_kh }} ({{ $cat->name_en }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success fw-bold rounded-pill px-3 w-100">
                    <i class="fa-solid fa-filter me-1"></i> ត្រងមើល
                </button>
                @if(request('search') || (request('category_id') && request('category_id') !== 'all'))
                    <a href="{{ url('/admin/seeds/bulk-update') }}" class="btn btn-light border fw-bold rounded-pill px-3" title="លុប Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 📋 BULK EDIT FORM & TABLE -->
    <form action="{{ route('admin.seeds.bulk_update') }}" method="POST" id="bulkUpdateForm">
        @csrf
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
            <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center">
                <span class="small fw-bold text-muted">
                    <i class="fa-solid fa-list-check text-success me-1"></i>
                    មានគ្រាប់ពូជសរុប <strong>{{ $seeds->count() }}</strong> មុខក្នុងបញ្ជី
                </span>
                <span class="badge bg-warning bg-opacity-25 text-dark fw-bold rounded-pill px-3 py-1.5" style="font-size: 11px;">
                    <i class="fa-solid fa-circle-info me-1"></i>ជួរដេកដែលបានកែប្រែ នឹងលេចពណ៌លឿងដោយស្វ័យប្រវត្តិ
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="bulkSeedsTable">
                    <thead class="table-light text-muted small fw-bold">
                        <tr>
                            <th class="ps-4" width="5%">#</th>
                            <th width="30%">គ្រាប់ពូជ & ប្រភេទ</th>
                            <th width="35%">ចំនួនស្តុក (Stock Packs)</th>
                            <th width="20%">តម្លៃលក់ ($ / Pack)</th>
                            <th class="text-end pe-4" width="10%">ស្ថានភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($seeds as $idx => $seed)
                            <tr id="row-{{ $seed->id }}" data-orig-stock="{{ $seed->stock }}" data-orig-price="{{ $seed->price }}">
                                <td class="ps-4 text-muted font-monospace small">
                                    {{ $idx + 1 }}
                                    <input type="hidden" name="seeds[{{ $idx }}][id]" value="{{ $seed->id }}">
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($seed->image)
                                            <img src="{{ asset('uploads/seeds/' . $seed->image) }}" class="rounded-3 me-2.5 border" style="width: 44px; height: 44px; object-fit: cover;">
                                        @else
                                            <div class="rounded-3 me-2.5 bg-light border d-flex align-items-center justify-content-center text-muted" style="width: 44px; height: 44px;">
                                                <i class="fa-solid fa-seedling text-success"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-bold text-dark small d-block mb-0.5" style="font-family: 'Kantumruy Pro', sans-serif;">
                                                {{ $seed->name_kh }}
                                            </span>
                                            <div class="d-flex align-items-center gap-1.5">
                                                <span class="badge bg-light text-muted border font-monospace" style="font-size: 10px;">{{ $seed->category?->name_kh ?? 'បន្លែ' }}</span>
                                                <span class="text-primary font-monospace" style="font-size: 10.5px;">#{{ $seed->batch_number ?? '---' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-sm btn-light border px-2 py-1 text-muted" onclick="adjustStock({{ $idx }}, -5)" title="បន្ថយ ៥">-5</button>
                                        <button type="button" class="btn btn-sm btn-light border px-2 py-1 text-muted" onclick="adjustStock({{ $idx }}, -1)" title="បន្ថយ ១">-1</button>
                                        <input type="number"
                                               name="seeds[{{ $idx }}][stock]"
                                               id="stock-input-{{ $idx }}"
                                               class="form-control form-control-sm text-center font-monospace fw-bold stock-input mx-1"
                                               style="max-width: 85px;"
                                               min="0"
                                               value="{{ $seed->stock }}"
                                               oninput="markRowModified({{ $seed->id }}, {{ $idx }})">
                                        <button type="button" class="btn btn-sm btn-light border px-2 py-1 text-muted" onclick="adjustStock({{ $idx }}, 1)" title="ថែម ១">+1</button>
                                        <button type="button" class="btn btn-sm btn-light border px-2 py-1 text-muted" onclick="adjustStock({{ $idx }}, 5)" title="ថែម ៥">+5</button>
                                        <small class="text-muted ms-2 font-monospace" style="font-size: 11px;">(ដើម: {{ $seed->stock }})</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group input-group-sm" style="max-width: 140px;">
                                        <span class="input-group-text bg-light text-muted">$</span>
                                        <input type="number"
                                               step="0.01"
                                               min="0.01"
                                               name="seeds[{{ $idx }}][price]"
                                               id="price-input-{{ $idx }}"
                                               class="form-control form-control-sm font-monospace fw-bold text-success price-input"
                                               value="{{ number_format($seed->price, 2, '.', '') }}"
                                               oninput="markRowModified({{ $seed->id }}, {{ $idx }})">
                                    </div>
                                </td>
                                <td class="text-end pe-4" id="status-col-{{ $idx }}">
                                    @if($seed->stock > 5)
                                        <span class="status-pill-badge status-pill-completed py-1 px-3 shadow-sm">
                                            <span class="status-dot-pulse status-dot-completed"></span>
                                            <span class="fw-bold">មានស្តុក</span>
                                        </span>
                                    @elseif($seed->stock > 0)
                                        <span class="status-pill-badge status-pill-pending py-1 px-3 shadow-sm">
                                            <span class="status-dot-pulse status-dot-pending"></span>
                                            <span class="fw-bold">សល់តិច</span>
                                        </span>
                                    @else
                                        <span class="status-pill-badge status-pill-cancelled py-1 px-3 shadow-sm">
                                            <span class="status-dot-pulse status-dot-cancelled"></span>
                                            <span class="fw-bold">អស់ស្តុក</span>
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-box-open fs-1 opacity-50 mb-2"></i>
                                    <p class="mb-0 fw-bold">រកមិនឃើញគ្រាប់ពូជដែលត្រូវស្វែងរកឡើយ</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($seeds->count() > 0)
                <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                    <a href="{{ url('/admin/seeds') }}" class="btn btn-light border fw-bold rounded-pill px-3 text-secondary">
                        បោះបង់
                    </a>
                    <button type="submit" class="btn btn-success fw-bold rounded-pill px-5 shadow-sm py-2">
                        <i class="fa-solid fa-floppy-disk me-1.5"></i> រក្សាទុកការផ្លាស់ប្តូរទាំងអស់
                    </button>
                </div>
            @endif
        </div>
    </form>

    <script>
    function adjustStock(idx, delta) {
        const input = document.getElementById(`stock-input-${idx}`);
        if (!input) return;
        let val = parseInt(input.value) || 0;
        val = Math.max(0, val + delta);
        input.value = val;
        input.dispatchEvent(new Event('input'));
    }

    function markRowModified(seedId, idx) {
        const row = document.getElementById(`row-${seedId}`);
        const stockInput = document.getElementById(`stock-input-${idx}`);
        const priceInput = document.getElementById(`price-input-${idx}`);
        const statusCol = document.getElementById(`status-col-${idx}`);

        if (!row || !stockInput || !priceInput) return;

        const origStock = parseInt(row.getAttribute('data-orig-stock'));
        const origPrice = parseFloat(row.getAttribute('data-orig-price'));

        const curStock = parseInt(stockInput.value) || 0;
        const curPrice = parseFloat(priceInput.value) || 0;

        if (curStock !== origStock || Math.abs(curPrice - origPrice) > 0.001) {
            row.style.backgroundColor = '#fffbeb';
            row.classList.add('border-warning');
        } else {
            row.style.backgroundColor = '';
            row.classList.remove('border-warning');
        }

        // Live update stock status badge with modern pulse pills
        if (statusCol) {
            if (curStock > 5) {
                statusCol.innerHTML = '<span class="status-pill-badge status-pill-completed py-1 px-3 shadow-sm"><span class="status-dot-pulse status-dot-completed"></span><span class="fw-bold">មានស្តុក</span></span>';
            } else if (curStock > 0) {
                statusCol.innerHTML = '<span class="status-pill-badge status-pill-pending py-1 px-3 shadow-sm"><span class="status-dot-pulse status-dot-pending"></span><span class="fw-bold">សល់តិច</span></span>';
            } else {
                statusCol.innerHTML = '<span class="status-pill-badge status-pill-cancelled py-1 px-3 shadow-sm"><span class="status-dot-pulse status-dot-cancelled"></span><span class="fw-bold">អស់ស្តុក</span></span>';
            }
        }
    }
    </script>
@endsection

