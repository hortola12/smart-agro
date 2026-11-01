<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ចុះឈ្មោះបង្កើតគណនី - Grow2Growth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth-style.css') }}">
</head>
<body>

    <div class="auth-premium-card">
        <!-- Top Back to Home Navigation -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ url('/') }}" class="text-decoration-none text-muted small fw-bold d-inline-flex align-items-center hover-success" style="transition: all 0.2s ease;">
                <i class="fa-solid fa-arrow-left-long me-2"></i> ត្រឡប់ទៅទំព័រដើម (Home)
            </a>
            <a href="{{ url('/') }}" class="d-flex align-items-center text-success text-decoration-none fw-bold small">
                <i class="fa-solid fa-seedling me-1"></i> Grow2Growth
            </a>
        </div>

        <div class="auth-header-wrapper">
            <div class="auth-brand-badge">
                <i class="fa-solid fa-user-plus me-1.5"></i> JOIN COMMUNITY
            </div>
            <h2 class="auth-main-title">បង្កើតគណនីថ្មី</h2>
            <p class="auth-desc-text">សូមបំពេញព័ត៌មានខាងក្រោម ដើម្បីចូលរួមជាមួយសហគមន៍កសិកម្មវៃឆ្លាត។</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="input-block-custom">
                <label for="name">ឈ្មោះពេញ (Full Name)</label>
                <input id="name" type="text" class="field-control-custom @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="ឧ. ហោ តុលា">
                @error('name')
                    <span class="invalid-feedback fw-bold mt-1" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="input-block-custom">
                <label for="email">អ៊ីមែល (Email Address)</label>
                <input id="email" type="email" class="field-control-custom @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="info@gmail.com">
                @error('email')
                    <span class="invalid-feedback fw-bold mt-1" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="input-block-custom">
                <label for="password">លេខកូដសម្ងាត់ (Password)</label>
                <input id="password" type="password" class="field-control-custom @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="យ៉ាងតិច ៨ ខ្ទង់">
                @error('password')
                    <span class="invalid-feedback fw-bold mt-1" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="input-block-custom mb-4">
                <label for="password-confirm">បញ្ជាក់លេខកូដសម្ងាត់ (Confirm Password)</label>
                <input id="password-confirm" type="password" class="field-control-custom" name="password_confirmation" required autocomplete="new-password" placeholder="វាយលេខកូដសម្ងាត់ឡើងវិញ">
            </div>

            <button type="submit" class="btn-premium-auth">
                ចុះឈ្មោះបង្កើតគណនី <i class="fa-solid fa-circle-check ms-1"></i>
            </button>

            <p class="auth-bottom-nav mb-0">
                មានគណនីរួចហើយមែនទេ?<a href="{{ route('login') }}">ចូលប្រើប្រាស់</a>
            </p>
        </form>
    </div>

</body>
</html>
