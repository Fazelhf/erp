<x-guest-layout>
    @section('title', 'ورود به سیستم')

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="text-center mb-5">
        <div class="mx-auto d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow" style="width: 80px; height: 80px; background: linear-gradient(135deg, rgba(212,175,55,0.2), rgba(212,175,55,0.1)); border: 1px solid rgba(212,175,55,0.3);">
            <i class="bi bi-person-circle fs-1" style="color: #800020;"></i>
        </div>
        <h3 class="fw-bold" style="color: #800020; letter-spacing: 1px;">ورود به حساب کاربری</h3>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">لطفاً اطلاعات خود را وارد کنید</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="mx-auto" style="max-width: 400px;">
        @csrf

        <div class="form-floating mb-4">
            <input type="email" class="form-control shadow-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="ایمیل" required autofocus autocomplete="username" style="border-radius: 0.75rem; padding-left: 3rem; border: 2px solid #e0e0e0; transition: border 0.3s ease, box-shadow 0.3s ease;">
            <label for="email">
                <i class="bi bi-envelope me-2" style="color: #800020;"></i>ایمیل
            </label>
            @error('email')
                <div class="invalid-feedback fw-bold mt-1" style="font-size: 0.85rem;">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-floating mb-4">
            <input type="password" class="form-control shadow-sm @error('password') is-invalid @enderror" id="password" name="password" placeholder="رمز عبور" required autocomplete="current-password" style="border-radius: 0.75rem; padding-left: 3rem; border: 2px solid #e0e0e0; transition: border 0.3s ease, box-shadow 0.3s ease;">
            <label for="password">
                <i class="bi bi-lock me-2" style="color: #800020;"></i>رمز عبور
            </label>
            @error('password')
                <div class="invalid-feedback fw-bold mt-1" style="font-size: 0.85rem;">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" class="form-check-input shadow-sm" id="remember" name="remember" style="border-color: #800020;">
                <label class="form-check-label text-muted" for="remember">مرا به خاطر بسپار</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none fw-medium" style="color: #800020; font-size: 0.875rem;">رمز عبور را فراموش کرده‌اید؟</a>
            @endif
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-gradient shadow py-3 fw-bold" style="background: linear-gradient(135deg, #800020 0%, #5c0013 100%); color: #d4af37; border-radius: 1rem; transition: transform 0.2s ease;">
                <i class="bi bi-box-arrow-in-right me-2" style="font-size: 1.1rem;"></i> ورود به سیستم
            </button>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                حساب کاربری ندارید؟
                <a href="{{ route('register') }}" class="fw-bold" style="color: #d4af37; text-shadow: 0 0 2px rgba(0,0,0,0.15);">ثبت نام کنید</a>
            </p>
        </div>
    </form>
</x-guest-layout>
