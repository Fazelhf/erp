<x-guest-layout>
    @section('title', 'بازیابی رمز عبور')

    <div class="text-center mb-4 pb-2">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm" style="width: 65px; height: 65px; background-color: rgba(212, 175, 55, 0.15); border: 1px solid rgba(212, 175, 55, 0.3);">
            <i class="bi bi-key-fill fs-2" style="color: #800020;"></i>
        </div>
        <h4 class="fw-bold" style="color: #800020;">تعیین رمز عبور جدید</h4>
        <p class="text-muted small px-2 mt-2" style="line-height: 1.8;">
            لطفاً ایمیل خود و رمز عبور جدیدتان را در کادرهای زیر وارد کنید تا اطلاعات حساب شما به‌روزرسانی شود.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-floating mb-3">
            <input type="email" 
                   class="form-control border-0 bg-light shadow-sm @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email', $request->email) }}" 
                   placeholder="ایمیل"
                   required 
                   autofocus 
                   autocomplete="username"
                   style="border-bottom: 2px solid #800020 !important; border-radius: 0.5rem 0.5rem 0 0;">
            <label for="email" class="text-muted" style="padding-right: 1.5rem;">
                <i class="bi bi-envelope me-1"></i> آدرس ایمیل
            </label>
            @error('email')
                <div class="invalid-feedback fw-bold mt-1" style="font-size: 0.85rem;">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-floating mb-3">
            <input type="password" 
                   class="form-control border-0 bg-light shadow-sm @error('password') is-invalid @enderror" 
                   id="password" 
                   name="password" 
                   placeholder="رمز عبور جدید"
                   required 
                   autocomplete="new-password"
                   style="border-bottom: 2px solid #800020 !important; border-radius: 0.5rem 0.5rem 0 0;">
            <label for="password" class="text-muted" style="padding-right: 1.5rem;">
                <i class="bi bi-lock me-1"></i> رمز عبور جدید
            </label>
            @error('password')
                <div class="invalid-feedback fw-bold mt-1" style="font-size: 0.85rem;">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="form-floating mb-4">
            <input type="password" 
                   class="form-control border-0 bg-light shadow-sm" 
                   id="password_confirmation" 
                   name="password_confirmation" 
                   placeholder="تکرار رمز عبور"
                   required 
                   autocomplete="new-password"
                   style="border-bottom: 2px solid #800020 !important; border-radius: 0.5rem 0.5rem 0 0;">
            <label for="password_confirmation" class="text-muted" style="padding-right: 1.5rem;">
                <i class="bi bi-check2-all me-1"></i> تکرار رمز عبور
            </label>
        </div>

        <div class="d-grid mt-4 pt-2">
            <button type="submit" class="btn shadow d-flex justify-content-center align-items-center gap-2 py-3" style="background: linear-gradient(135deg, #800020 0%, #5c0013 100%); border: none; border-radius: 0.75rem; transition: all 0.3s ease;">
                <i class="bi bi-shield-check fs-5" style="color: #d4af37;"></i>
                <span class="fw-bold" style="color: #d4af37; letter-spacing: 0.5px;">تغییر رمز عبور</span>
            </button>
        </div>
    </form>
</x-guest-layout>