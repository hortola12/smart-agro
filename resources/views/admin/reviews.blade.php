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
            <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">គ្រប់គ្រងមតិយោបល់ និងការវាយតម្លៃ (Reviews & Ratings)</h4>
            <p class="text-muted small mb-0">ពិនិត្យ តាមដាន និងគ្រប់គ្រងមតិយោបល់របស់កសិករចំពោះគុណភាពគ្រាប់ពូជ</p>
        </div>
    </div>

    <!-- 📊 STATS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-4 bg-primary bg-opacity-10 text-primary p-3 me-3 fs-3">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block">មតិវាយតម្លៃសរុប</small>
                        <h3 class="fw-bold text-dark m-0 font-monospace">{{ $totalReviews }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center">
                    <div class="rounded-4 bg-warning bg-opacity-15 text-warning p-3 me-3 fs-3">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <div>
                        <small class="text-muted fw-bold d-block">ពិន្ទុមធ្យមប្រព័ន្ធ (Average)</small>
                        <h3 class="fw-bold text-warning m-0 font-monospace">{{ number_format($avgRating, 1) }} / 5.0</h3>
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
                        <small class="text-muted fw-bold d-block">ស្ថានភាពគុណភាពគ្រាប់ពូជ</small>
                        <h5 class="fw-bold text-success m-0">កសិករទុកចិត្តខ្ពស់</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔍 SEARCH & FILTER -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ url('/admin/reviews') }}" method="GET" class="row g-2 align-items-center m-0">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="ស្វែងរកតាមឈ្មោះកសិករ គ្រាប់ពូជ ឬខ្លឹមសារ..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="rating" class="form-select bg-light">
                    <option value="">-- កម្រិតផ្កាយទាំងអស់ (All Ratings) --</option>
                    <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ ៥ ផ្កាយ (5 Stars)</option>
                    <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ ៤ ផ្កាយ (4 Stars)</option>
                    <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ ៣ ផ្កាយ (3 Stars)</option>
                    <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>⭐⭐ ២ ផ្កាយ (2 Stars)</option>
                    <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>⭐ ១ ផ្កាយ (1 Star)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-success fw-bold rounded-pill px-3 w-100">
                    <i class="fa-solid fa-filter me-1"></i> ស្វែងរក
                </button>
                @if(request('search') || request('rating'))
                    <a href="{{ url('/admin/reviews') }}" class="btn btn-light border fw-bold rounded-pill px-3" title="លុប Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 📋 REVIEWS TABLE -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small fw-bold">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>គ្រាប់ពូជ</th>
                        <th>កសិករ / អ្នកវាយតម្លៃ</th>
                        <th>កម្រិតផ្កាយ</th>
                        <th style="min-width: 250px;">មតិយោបល់</th>
                        <th>កាលបរិច្ឆេទ</th>
                        <th class="text-end pe-4">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="ps-4 text-muted font-monospace small">#{{ $review->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($review->seed?->image)
                                        <img src="{{ asset('uploads/seeds/' . $review->seed->image) }}" class="rounded-3 me-2 border" style="width: 42px; height: 42px; object-fit: cover;">
                                    @else
                                        <div class="rounded-3 me-2 bg-light border d-flex align-items-center justify-content-center text-muted" style="width: 42px; height: 42px;">
                                            <i class="fa-solid fa-seedling text-success"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <a href="{{ url('/seed-detail/' . ($review->seed?->id ?? '')) }}" target="_blank" class="text-dark fw-bold text-decoration-none small d-block">
                                            {{ $review->seed?->name_kh ?? '---' }}
                                        </a>
                                        <small class="text-muted font-monospace" style="font-size: 11px;">#{{ $review->seed?->batch_number ?? '---' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-dark small d-block">{{ $review->customer_name }}</span>
                                @if($review->customer_phone)
                                    <small class="text-muted font-monospace" style="font-size: 11px;"><i class="fa-solid fa-phone me-1"></i>{{ $review->customer_phone }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="text-warning small">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star {{ $i <= $review->rating ? '' : 'text-muted opacity-25' }}"></i>
                                    @endfor
                                </div>
                                <span class="badge bg-warning bg-opacity-25 text-dark fw-bold font-monospace" style="font-size: 11px;">{{ $review->rating }}.0 / 5.0</span>
                            </td>
                            <td>
                                <div class="text-dark small" style="line-height: 1.5;">
                                    {{ $review->comment }}
                                </div>
                            </td>
                            <td>
                                <small class="text-muted font-monospace" style="font-size: 11px;">
                                    {{ $review->created_at->format('d/m/Y H:i') }}
                                </small>
                            </td>
                            <td class="text-end pe-4">
                                <form action="{{ url('/admin/reviews/delete/' . $review->id) }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបមតិយោបល់នេះមែនទេ?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3" title="លុបមតិយោបល់">
                                        <i class="fa-solid fa-trash-can me-1"></i> លុប
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-regular fa-comment-dots fs-1 opacity-50 mb-2"></i>
                                <p class="mb-0 fw-bold">មិនទាន់មានមតិយោបល់របស់អតិថិជននៅឡើយទេ</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
@endsection

