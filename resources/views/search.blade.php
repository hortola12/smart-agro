@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/home-style.css') }}">

@section('content')
    <div class="mb-4">
        <a href="{{ url('/') }}" class="btn btn-light btn-sm border fw-bold rounded-pill px-3 shadow-sm text-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> {{ __('menu_home') ?? 'ត្រលប់ទៅទំព័រដើម' }}
        </a>
    </div>

    <h4 class="mb-4 fw-bold text-dark border-bottom pb-3" style="font-family: 'Kantumruy Pro', sans-serif;">
        <i class="fa-solid fa-magnifying-glass text-success me-2"></i>លទ្ធផលស្វែងរកសម្រាប់៖ <span class="text-success">"{{ $query }}"</span>
    </h4>

    @if($seeds->isEmpty())
        <div class="alert alert-warning text-center py-5 rounded-4 shadow-sm bg-white border border-warning border-opacity-50">
            <div class="p-3 bg-warning bg-opacity-10 rounded-circle d-inline-flex mb-3 text-warning">
                <i class="fa-solid fa-triangle-exclamation display-6"></i>
            </div>
            <h6 class="fw-bold text-dark mb-1" style="font-family: 'Kantumruy Pro', sans-serif;">រកមិនឃើញទិន្នន័យគ្រាប់ពូជឡើយ!</h6>
            <p class="text-muted small mb-0">មិនមានទិន្នន័យគ្រាប់ពូជដែលអ្នកកំពុងស្វែងរកឡើយ! សូមសាកល្បងវាយពាក្យផ្សេងទៀត។</p>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
            @foreach($seeds as $seed)
            <div class="col">
                <div class="card seed-card h-100 shadow-sm border-0 rounded-4 overflow-hidden bg-white">
                    @if($seed->image)
                        <img src="{{ asset('uploads/seeds/' . $seed->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Seed Image">
                    @else
                        <img src="https://via.placeholder.com/400x300" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Seed Image">
                    @endif

                    <div class="card-body p-3">
                        <span class="category-badge mb-2 d-inline-block">
                            @if(App::isLocale('en'))
                                {{ $seed->category?->name_en ?? 'Seed' }}
                            @else
                                {{ $seed->category?->name_kh ?? 'គ្រាប់ពូជ' }}
                            @endif
                        </span>

                        <h6 class="card-title fw-bold mb-1 text-dark" style="font-family: 'Kantumruy Pro', sans-serif; font-size: 15px;">
                            {{ App::isLocale('en') ? $seed->name_en : $seed->name_kh }}
                        </h6>

                        <p class="price-tag mb-3 fw-bold text-danger fs-5 font-monospace">
                            ${{ number_format($seed->price, 2) }}
                            <span class="fs-6 text-muted fw-normal" style="font-size: 12px;">/ {{ __('per_pack') ?? 'កញ្ចប់' }}</span>
                        </p>

                        <div class="d-grid gap-2">
                            <a href="{{ url('/seed-detail/' . $seed->id) }}" class="btn btn-success btn-sm fw-bold rounded-pill shadow-sm py-1.5" style="font-size: 13px;">
                                <i class="fa-solid fa-eye me-1"></i> {{ __('view_origin') ?? 'មើលប្រភពដើម' }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
@endsection
