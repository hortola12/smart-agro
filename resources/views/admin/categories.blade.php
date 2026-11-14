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
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li class="fw-bold">{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">
                <i class="fa-solid fa-tags text-primary me-2"></i>គ្រប់គ្រងប្រភេទបន្លែ
            </h4>
            <p class="text-muted small mb-0">បន្ថែម កែប្រែ ឬលុបប្រភេទបន្លែសម្រាប់ចាត់ថ្នាក់គ្រាប់ពូជក្នុងប្រព័ន្ធ</p>
        </div>
        <button type="button" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
            <i class="fa-solid fa-plus me-2"></i>បន្ថែមប្រភេទថ្មី
        </button>
    </div>

    <!-- Search & Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
        <form action="{{ url('/admin/categories') }}" method="GET" class="row g-2 align-items-center m-0">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="ស្វែងរកតាមឈ្មោះខ្មែរ ឬអង់គ្លេស..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-bold rounded-pill px-3 w-100">
                    <i class="fa-solid fa-filter me-1"></i> ស្វែងរក
                </button>
                @if(request('search'))
                    <a href="{{ url('/admin/categories') }}" class="btn btn-light border fw-bold rounded-pill px-3" title="លុប Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div class="card admin-main-card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle admin-table mb-0" style="font-size: 14px;">
                    <thead>
                        <tr>
                            <th class="ps-4" width="8%"># ID</th>
                            <th width="22%">ឈ្មោះជាភាសាខ្មែរ</th>
                            <th width="20%">ឈ្មោះជាភាសាអង់គ្លេស</th>
                            <th>ការពិពណ៌នា</th>
                            <th class="text-center" width="15%">ចំនួនគ្រាប់ពូជ</th>
                            <th class="text-center" width="15%">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">#{{ $category->id }}</td>
                                <td>
                                    <div class="fw-bold text-dark fs-6" style="font-family: 'Kantumruy Pro', sans-serif;">
                                        🌱 {{ $category->name_kh }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border px-2.5 py-1.5 fw-semibold">
                                        {{ $category->name_en }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        {{ $category->description ?? 'មិនមានការពិពណ៌នា' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $category->seeds_count > 0 ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary' }} px-3 py-1.5 rounded-pill fw-bold">
                                        <i class="fa-solid fa-seedling me-1"></i>{{ $category->seeds_count }} មុខ
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCategoryModal{{ $category->id }}"
                                                title="កែប្រែ">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Delete Button -->
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5 py-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteCategoryModal{{ $category->id }}"
                                                title="លុប">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal for this category -->
                            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <form action="{{ url('/admin/categories/update/' . $category->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title fw-bold text-dark" style="font-family: 'Kantumruy Pro', sans-serif;">
                                                    <i class="fa-solid fa-pen-to-square text-primary me-2"></i>កែប្រែប្រភេទបន្លែ
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body py-4">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-dark">ឈ្មោះជាភាសាខ្មែរ <span class="text-danger">*</span></label>
                                                    <input type="text" name="name_kh" class="form-control rounded-3" value="{{ $category->name_kh }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold text-dark">ឈ្មោះជាភាសាអង់គ្លេស <span class="text-danger">*</span></label>
                                                    <input type="text" name="name_en" class="form-control rounded-3" value="{{ $category->name_en }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label fw-bold text-dark">ការពិពណ៌នា</label>
                                                    <textarea name="description" class="form-control rounded-3" rows="3">{{ $category->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">បោះបង់</button>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">រក្សាទុកការកែប្រែ</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal for this category -->
                            <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4 border-0 shadow">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title fw-bold text-danger" style="font-family: 'Kantumruy Pro', sans-serif;">
                                                <i class="fa-solid fa-triangle-exclamation me-2"></i>បញ្ជាក់ការលុបប្រភេទបន្លែ
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body py-3">
                                            <p class="mb-2">តើអ្នកពិតជាចង់លុបប្រភេទបន្លែ <strong>"{{ $category->name_kh }} ({{ $category->name_en }})"</strong> នេះមែនទេ?</p>
                                            @if($category->seeds_count > 0)
                                                <div class="alert alert-warning border-0 rounded-3 mb-0 small">
                                                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                                    <strong>ចំណាំ៖</strong> ប្រភេទបន្លែនេះមានគ្រាប់ពូជចំនួន <strong>{{ $category->seeds_count }} មុខ</strong> កំពុងប្រើប្រាស់។ អ្នកត្រូវតែប្តូរប្រភេទគ្រាប់ពូជទាំងនោះជាមុនសិន ទើបអាចលុបបាន។
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">បោះបង់</button>
                                            <form action="{{ url('/admin/categories/delete/' . $category->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold" {{ $category->seeds_count > 0 ? 'disabled' : '' }}>
                                                    <i class="fa-solid fa-trash me-1"></i> លុបចេញ
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-tags fa-3x mb-3 text-secondary opacity-50 d-block"></i>
                                    <span class="fs-6 fw-bold">មិនទាន់មានទិន្នន័យប្រភេទបន្លែនៅឡើយទេ</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Category Modal -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="{{ url('/admin/categories/store') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold text-dark" style="font-family: 'Kantumruy Pro', sans-serif;">
                            <i class="fa-solid fa-folder-plus text-success me-2"></i>បន្ថែមប្រភេទបន្លែថ្មី
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">ឈ្មោះជាភាសាខ្មែរ <span class="text-danger">*</span></label>
                            <input type="text" name="name_kh" class="form-control rounded-3" placeholder="ឧទាហរណ៍៖ បន្លែយកស្លឹក" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-dark">ឈ្មោះជាភាសាអង់គ្លេស <span class="text-danger">*</span></label>
                            <input type="text" name="name_en" class="form-control rounded-3" placeholder="ឧទាហរណ៍៖ Leafy Vegetables" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold text-dark">ការពិពណ៌នា</label>
                            <textarea name="description" class="form-control rounded-3" rows="3" placeholder="ព័ត៌មានលម្អិតសង្ខេបអំពីប្រភេទបន្លែ..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">បោះបង់</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">
                            <i class="fa-solid fa-save me-1"></i> រក្សាទុក
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

