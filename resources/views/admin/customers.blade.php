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
                <i class="fa-solid fa-users text-warning me-2"></i>គ្រប់គ្រងបញ្ជីអតិថិជន
            </h4>
            <p class="text-muted small mb-0">ពិនិត្យមើលបញ្ជីគណនីអតិថិជន និងចំនួនការកម្ម៉ង់ទិញក្នុងប្រព័ន្ធ Grow2Growth</p>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary rounded-3 p-3 me-3">
                        <i class="fa-solid fa-users fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold">គណនីសរុប (All Users)</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalUsersCount }} នាក់</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-success-subtle text-success rounded-3 p-3 me-3">
                        <i class="fa-solid fa-user-check fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold">អតិថិជនកសិករ (Customers)</div>
                        <h4 class="fw-bold mb-0 text-success">{{ $totalCustomersCount }} នាក់</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center">
                    <div class="bg-warning-subtle text-warning rounded-3 p-3 me-3">
                        <i class="fa-solid fa-bag-shopping fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-bold">ការកម្ម៉ង់សរុប (Total Orders)</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalOrdersCount }} លើក</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ url('/admin/customers') }}" method="GET" class="row g-2 align-items-center m-0">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="ស្វែងរកតាមឈ្មោះ ឬអ៊ីមែលអតិថិជន..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select bg-light">
                    <option value="all">-- តួនាទីទាំងអស់ (All Roles) --</option>
                    <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>អតិថិជន (Customer)</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>អ្នកគ្រប់គ្រង (Admin)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-bold rounded-pill px-3 w-100">
                    <i class="fa-solid fa-filter me-1"></i> ស្វែងរក
                </button>
                @if(request('search') || (request('role') && request('role') !== 'all'))
                    <a href="{{ url('/admin/customers') }}" class="btn btn-light border fw-bold rounded-pill px-3" title="លុប Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="card admin-main-card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle admin-table mb-0" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th class="ps-4" width="8%"># ID</th>
                            <th>ព័ត៌មានអតិថិជន</th>
                            <th>អ៊ីមែល (Email)</th>
                            <th class="text-center" width="12%">តួនាទី (Role)</th>
                            <th class="text-center" width="15%">ចំនួនការកម្ម៉ង់</th>
                            <th class="text-center" width="15%">ទំហំទឹកប្រាក់ទិញ</th>
                            <th class="text-center" width="15%">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#{{ $customer->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold shadow-sm" style="width: 38px; height: 38px; font-size: 15px;">
                                            {{ mb_substr($customer->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-family: 'Kantumruy Pro', sans-serif;">
                                                {{ $customer->name }}
                                            </div>
                                            <small class="text-muted">
                                                <i class="fa-regular fa-calendar-check me-1"></i>
                                                {{ $customer->created_at ? $customer->created_at->format('d/m/Y') : '-' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:{{ $customer->email }}" class="text-decoration-none text-muted fw-semibold">
                                        <i class="fa-regular fa-envelope me-1 text-primary"></i>{{ $customer->email }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    @if($customer->role === 'admin')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1.5 fw-bold">
                                            <i class="fa-solid fa-shield-halved me-1"></i>Admin
                                        </span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1.5 fw-bold">
                                            <i class="fa-solid fa-user me-1"></i>អតិថិជន
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $customer->orders_count > 0 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-light text-muted border' }} rounded-pill px-3 py-1.5 fw-bold">
                                        <i class="fa-solid fa-receipt me-1"></i>{{ $customer->orders_count }} លើក
                                    </span>
                                </td>
                                <td class="text-center fw-bold text-success">
                                    ${{ number_format($customer->orders_sum_total_price ?? 0, 2) }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('/admin/orders?search=' . urlencode($customer->name)) }}"
                                       class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold"
                                       title="មើលការកម្ម៉ង់របស់អតិថិជននេះ">
                                        <i class="fa-solid fa-bag-shopping me-1"></i> មើលការកម្ម៉ង់
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-users fa-3x mb-3 text-secondary opacity-50 d-block"></i>
                                    <span class="fs-6 fw-bold">មិនមានទិន្នន័យអតិថិជនដែលត្រូវស្វែងរកឡើយ</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $customers->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

