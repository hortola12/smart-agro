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

<!-- Header Title -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">
            <i class="fa-solid fa-users text-warning me-2"></i>គ្រប់គ្រងបញ្ជីអតិថិជន & តួនាទី (Users & Roles)
        </h4>
        <p class="text-muted small mb-0">ពិនិត្យមើលបញ្ជីគណនីអតិថិជន ប្រវត្តិបញ្ជាទិញ និងគ្រប់គ្រងសិទ្ធិតួនាទី (Admin / Customer)</p>
    </div>
</div>

<!-- 📊 4 Quick Stats KPI Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-primary">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-bold mb-1">គណនីសរុប (All Users)</div>
                    <h4 class="fw-bold mb-0 text-dark font-monospace">{{ $totalUsersCount }} <span class="fs-6 text-muted fw-normal">នាក់</span></h4>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-users fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-success">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-bold mb-1">អតិថិជនកសិករ (Farmers)</div>
                    <h4 class="fw-bold mb-0 text-success font-monospace">{{ $totalCustomersCount }} <span class="fs-6 text-muted fw-normal">នាក់</span></h4>
                </div>
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-user-check fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-danger">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-bold mb-1">អ្នកគ្រប់គ្រង (Admins)</div>
                    <h4 class="fw-bold mb-0 text-danger font-monospace">{{ $totalAdminsCount ?? 1 }} <span class="fs-6 text-muted fw-normal">នាក់</span></h4>
                </div>
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-shield-halved fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-4 border-warning">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-muted small fw-bold mb-1">ការកម្ម៉ង់សរុប (Orders)</div>
                    <h4 class="fw-bold mb-0 text-dark font-monospace">{{ $totalOrdersCount }} <span class="fs-6 text-muted fw-normal">លើក</span></h4>
                </div>
                <div class="bg-warning bg-opacity-15 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-bag-shopping fs-5"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🔍 Filter Card -->
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
                <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>👤 អតិថិជន (Customers)</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>🛡️ អ្នកគ្រប់គ្រង (Admins)</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-success fw-bold rounded-pill px-3 w-100">
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

<!-- 📋 Customers & Roles Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 14px;">
            <thead class="table-light text-muted small fw-bold">
                <tr>
                    <th class="ps-4" width="6%"># ID</th>
                    <th width="23%">ព័ត៌មានអតិថិជន & កាលបរិច្ឆេទ</th>
                    <th width="20%">អ៊ីមែល (Email)</th>
                    <th class="text-center" width="15%">តួនាទី (Role & Access)</th>
                    <th class="text-center" width="12%">ការកម្ម៉ង់</th>
                    <th class="text-center" width="12%">ទំហំទឹកប្រាក់ទិញ</th>
                    <th class="text-center" width="22%">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td class="ps-4 font-monospace text-muted small fw-bold">#{{ $customer->id }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold shadow-xs text-white"
                                style="width: 40px; height: 40px; font-size: 13px; background: linear-gradient(135deg, {{ $customer->role === 'admin' ? '#dc3545, #b02a37' : '#198754, #146c43' }});">
                                {{ mb_substr($customer->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark d-flex align-items-center" style="font-family: 'Kantumruy Pro', sans-serif;">
                                    <span>{{ $customer->name }}</span>
                                    @if($customer->id === auth()->id())
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1.5 font-monospace" style="font-size: 9.5px;">អ្នក</span>
                                    @endif
                                </div>
                                <small class="text-muted" style="font-size: 11px;">
                                    <i class="fa-regular fa-calendar-check text-muted me-1"></i>
                                    ចុះឈ្មោះ៖ {{ $customer->created_at ? $customer->created_at->format('d/m/Y') : '-' }}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="mailto:{{ $customer->email }}" class="text-decoration-none text-muted small fw-bold">
                            <i class="fa-regular fa-envelope me-1 text-primary"></i>{{ $customer->email }}
                        </a>
                    </td>
                    <td class="text-center">
                        @if($customer->id === auth()->id())
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill px-3 py-1.5 fw-bold font-monospace" style="font-size: 11.5px;">
                            <i class="fa-solid fa-lock me-1"></i>Admin (ម្ចាស់គណនី)
                        </span>
                        @else
                        <!-- Interactive Role Selector Dropdown -->
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-sm rounded-pill px-3 py-1 fw-bold dropdown-toggle border shadow-xs d-inline-flex align-items-center gap-1.5 {{ $customer->role === 'admin' ? 'btn-danger text-white' : 'btn-light border-secondary border-opacity-25 text-dark' }}"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                style="font-size: 12px;">
                                @if($customer->role === 'admin')
                                <i class="fa-solid fa-shield-halved text-warning"></i>
                                <span>Admin</span>
                                @else
                                <i class="fa-solid fa-user text-success"></i>
                                <span>អតិថិជន</span>
                                @endif
                            </button>
                            <ul class="dropdown-menu shadow-lg border-0 rounded-4 p-2 mt-1" style="font-size: 13px; min-width: 210px;">
                                <li class="px-2 py-1 mb-1">
                                    <small class="text-muted fw-bold text-uppercase d-block" style="font-size: 10px;">កំណត់តួនាទី / Set Role</small>
                                </li>
                                <li>
                                    <button type="button"
                                        class="dropdown-item rounded-3 py-2 d-flex align-items-center justify-content-between {{ $customer->role === 'admin' ? 'active bg-danger text-white fw-bold' : '' }}"
                                        onclick="openRoleModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', '{{ $customer->email }}', 'admin')">
                                        <span><i class="fa-solid fa-shield-halved text-warning me-2"></i>អ្នកគ្រប់គ្រង (Admin)</span>
                                        @if($customer->role === 'admin')
                                        <i class="fa-solid fa-check small"></i>
                                        @endif
                                    </button>
                                </li>
                                <li>
                                    <button type="button"
                                        class="dropdown-item rounded-3 py-2 d-flex align-items-center justify-content-between mt-1 {{ $customer->role === 'customer' ? 'active bg-success text-white fw-bold' : '' }}"
                                        onclick="openRoleModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', '{{ $customer->email }}', 'customer')">
                                        <span><i class="fa-solid fa-user text-primary me-2"></i>អតិថិជន (Customer)</span>
                                        @if($customer->role === 'customer')
                                        <i class="fa-solid fa-check small"></i>
                                        @endif
                                    </button>
                                </li>
                            </ul>
                        </div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $customer->orders_count > 0 ? 'bg-success bg-opacity-10 text-success border border-success border-opacity-25' : 'bg-light text-muted border' }} rounded-pill px-3 py-1.5 fw-bold font-monospace">
                            <i class="fa-solid fa-receipt me-1"></i>{{ $customer->orders_count }} លើក
                        </span>
                    </td>
                    <td class="text-center fw-bold font-monospace {{ ($customer->orders_sum_total_price ?? 0) > 0 ? 'text-success' : 'text-muted' }}">
                        ${{ number_format($customer->orders_sum_total_price ?? 0, 2) }}
                    </td>
                    <td class="text-end pe-4">
                        <a href="{{ url('/admin/orders?search=' . urlencode($customer->name)) }}"
                            class="btn btn-sm btn-outline-success rounded-pill px-3 py-1.5 fw-bold shadow-xs"
                            title="ពិនិត្យមើលការកម្ម៉ង់របស់អតិថិជននេះ">
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

<!-- 🛡️ MODERN ROLE CONFIRMATION MODAL -->
<div class="modal fade" id="changeRoleModal" tabindex="-1" aria-labelledby="changeRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center p-3 mb-2" id="modalRoleIconBg" style="width: 54px; height: 54px;">
                    <i class="fa-solid fs-4" id="modalRoleIcon"></i>
                </div>
            </div>
            <div class="modal-body px-4 pt-2 pb-4">
                <h5 class="fw-bold text-dark mb-1" id="modalRoleTitle" style="font-family: 'Kantumruy Pro', sans-serif;">
                    ផ្លាស់ប្តូរតួនាទីគណនី
                </h5>
                <p class="text-muted small mb-3">
                    តើអ្នកពិតជាចង់ផ្លាស់ប្តូរតួនាទីរបស់អ្នកប្រើប្រាស់នេះមែនទេ?
                </p>

                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="d-flex align-items-center mb-2">
                        <span class="text-muted small fw-bold me-2" style="width: 80px;">ឈ្មោះ៖</span>
                        <span class="fw-bold text-dark" id="modalUserName">---</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="text-muted small fw-bold me-2" style="width: 80px;">អ៊ីមែល៖</span>
                        <span class="text-muted font-monospace small" id="modalUserEmail">---</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="text-muted small fw-bold me-2" style="width: 80px;">តួនាទីថ្មី៖</span>
                        <span id="modalNewRoleBadge" class="badge rounded-pill px-3 py-1 font-monospace fw-bold">---</span>
                    </div>
                </div>

                <div class="alert small mb-0 rounded-3 p-3" id="modalPrivilegeAlert">
                    <!-- Populated by JavaScript -->
                </div>
            </div>
            <div class="modal-footer border-top bg-light py-2.5 px-4 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light rounded-pill px-4 fw-bold border" data-bs-dismiss="modal">
                    បោះបង់
                </button>
                <form id="changeRoleForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="role" id="modalRoleInput" value="">
                    <button type="submit" class="btn rounded-pill px-4 fw-bold shadow-sm" id="modalConfirmBtn">
                        <i class="fa-solid fa-check me-1.5"></i> បញ្ជាក់ការផ្លាស់ប្តូរ
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openRoleModal(userId, userName, userEmail, targetRole) {
        document.getElementById('modalUserName').textContent = userName;
        document.getElementById('modalUserEmail').textContent = userEmail;
        document.getElementById('modalRoleInput').value = targetRole;
        document.getElementById('changeRoleForm').action = `/admin/customers/role/${userId}`;

        const iconBg = document.getElementById('modalRoleIconBg');
        const icon = document.getElementById('modalRoleIcon');
        const title = document.getElementById('modalRoleTitle');
        const badge = document.getElementById('modalNewRoleBadge');
        const alertBox = document.getElementById('modalPrivilegeAlert');
        const confirmBtn = document.getElementById('modalConfirmBtn');

        if (targetRole === 'admin') {
            iconBg.className = 'rounded-circle d-flex align-items-center justify-content-center p-3 mb-2 bg-danger bg-opacity-10 text-danger';
            icon.className = 'fa-solid fa-shield-halved fs-4 text-danger';
            title.textContent = 'តម្លើងសិទ្ធិជា អ្នកគ្រប់គ្រង (Promote to Admin)';
            badge.className = 'badge bg-danger rounded-pill px-3 py-1 font-monospace fw-bold';
            badge.textContent = '🛡️ Admin';
            alertBox.className = 'alert alert-warning small mb-0 rounded-3 p-3';
            alertBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1.5 text-warning"></i> <strong>ការជូនដំណឹង៖</strong> គណនីនេះនឹងទទួលបានសិទ្ធិពេញលេញលើការគ្រប់គ្រងគ្រាប់ពូជ ការកម្ម៉ង់ របាយការណ៍ហិរញ្ញវត្ថុ និងការកំណត់ប្រព័ន្ធសហគមន៍ទាំងមូល។';
            confirmBtn.className = 'btn btn-danger rounded-pill px-4 fw-bold shadow-sm';
        } else {
            iconBg.className = 'rounded-circle d-flex align-items-center justify-content-center p-3 mb-2 bg-primary bg-opacity-10 text-primary';
            icon.className = 'fa-solid fa-user-minus fs-4 text-primary';
            title.textContent = 'ទម្លាក់សិទ្ធិជា អតិថិជនធម្មតា (Demote to Customer)';
            badge.className = 'badge bg-primary rounded-pill px-3 py-1 font-monospace fw-bold';
            badge.textContent = '👤 Customer';
            alertBox.className = 'alert alert-info small mb-0 rounded-3 p-3';
            alertBox.innerHTML = '<i class="fa-solid fa-circle-info me-1.5 text-primary"></i> គណនីនេះនឹងត្រូវបានដកសិទ្ធិចេញពីផ្ទាំងគ្រប់គ្រង Admin ហើយអាចត្រឹមតែធ្វើការបញ្ជាទិញ និងពិនិត្យប្រវត្តិទំនិញផ្ទាល់ខ្លួនប៉ុណ្ណោះ។';
            confirmBtn.className = 'btn btn-primary rounded-pill px-4 fw-bold shadow-sm';
        }

        const modal = new bootstrap.Modal(document.getElementById('changeRoleModal'));
        modal.show();
    }
</script>
@endsection