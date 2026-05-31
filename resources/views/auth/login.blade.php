<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ចូលប្រើប្រាស់ប្រព័ន្ធ - Grow2Growth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth-style.css') }}">
</head>
<body>

    <div class="auth-premium-card">
        <div class="auth-header-wrapper">
            <div class="auth-brand-badge">
                <i class="fa-solid fa-leaf me-1.5"></i> GROW2GROW PLATFORM
            </div>
            <h2 class="auth-main-title">ចូលប្រើប្រាស់ប្រព័ន្ធ</h2>
            <p class="auth-desc-text">សូមបំពេញព័ត៌មានគណនីរបស់អ្នក ដើម្បីចូលទៅកាន់ផ្ទាំងគ្រប់គ្រង។</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="input-block-custom">
                <label for="email">អ៊ីមែល (Email Address)</label>
                <input id="email" type="email" class="field-control-custom @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="ឈ្មោះគណនី@gmail.com">
                @error('email')
                    <span class="invalid-feedback fw-bold mt-1" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="input-block-custom">
                <label for="password">លេខកូដសម្ងាត់ (Password)</label>
                <input id="password" type="password" class="field-control-custom @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                @error('password')
                    <span class="invalid-feedback fw-bold mt-1" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4" style="font-size: 13.5px;">
                <div class="form-check m-0 d-flex align-items-center gap-1.5">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} style="cursor: pointer;">
                    <label class="form-check-label text-muted" for="remember" style="cursor: pointer;">ចងចាំគណនីខ្ញុំ</label>
                </div>
                <a class="text-success fw-bold text-decoration-none" href="#">ភ្លេចលេខកូដ?</a>
            </div>

            <button type="submit" class="btn-premium-auth">
                ចូលប្រើប្រាស់ប្រព័ន្ធ <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>
            </button>

            <p class="auth-bottom-nav mb-0">
                មិនទាន់មានគណនីមែនទេ?<a href="{{ route('register') }}">បង្កើតគណនីថ្មី</a>
            </p>
        </form>
    </div>

</body>
</html>
