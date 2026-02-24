<x-guest-layout>
    @section('title', 'تأیید رمز عبور')

    <div class="text-center mb-4 pb-2">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm" style="width: 65px; height: 65px; background-color: rgba(212, 175, 55, 0.15); border: 1px solid rgba(212, 175, 55, 0.3);">
            <i class="bi bi-shield-lock-fill fs-2" style="color: #800020;"></i>
        </div>
        <h4 class="fw-bold" style="color: #800020;">تأیید هویت</h4>
        <p class="text-muted small px-2 mt-2" style="line-height: 1.8;">
            این یک بخش امن از سیستم ERP است. برای ادامه مسیر و دسترسی به این بخش، لطفاً رمز عبور خود را وارد کنید.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="form-floating mb-4">
            <input type="password" 
                   class="form-control border-0 bg-light shadow-sm @error('password') is-invalid @enderror" 
                   id="password" 
                   name="password" 
                   placeholder="رمز عبور"
                   required 
                   autocomplete="current-password"
                   style="border-bottom: 2px solid #800020 !important; border-radius: 0.5rem 0.5rem 0 0;">
            <label for="password" class="text-muted" style="padding-right: 1.5rem;">
                <i class="bi bi-key me-1"></i> رمز عبور خود را وارد کنید
            </label>
            
            @error('password')
                <div class="invalid-feedback fw-bold mt-2" style="font-size: 0.85rem;">
                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <div class="d-grid mt-4 pt-2">
            <button type="submit" class="btn btn-primary btn-lg shadow d-flex justify-content-center align-items-center gap-2 py-3" style="border-radius: 0.75rem; transition: all 0.3s ease;">
                <i class="bi bi-shield-check fs-5" style="color: #d4af37;"></i>
                <span class="fw-bold" style="color: #d4af37; letter-spacing: 0.5px;">تأیید و ورود</span>
            </button>
        </div>
    </form>
</x-guest-layout>