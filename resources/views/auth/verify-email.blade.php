<x-guest-layout>
    @section('title', 'تأیید ایمیل')

    <div class="text-center mb-4 pb-2">
        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm" style="width: 75px; height: 75px; background-color: rgba(212, 175, 55, 0.15); border: 1px solid rgba(212, 175, 55, 0.3);">
            <i class="bi bi-envelope-check-fill" style="font-size: 2.2rem; color: #800020;"></i>
        </div>
        <h4 class="fw-bold" style="color: #800020;">تأیید آدرس ایمیل</h4>
        <p class="text-muted small px-2 mt-3" style="line-height: 1.8; font-size: 0.95rem;">
            به سیستم ERP خوش آمدید! قبل از شروع، لطفاً روی لینکی که به ایمیل شما ارسال کرده‌ایم کلیک کنید تا حساب کاربری‌تان فعال شود. 
            <br>
            اگر ایمیلی دریافت نکرده‌اید، روی دکمه زیر کلیک کنید تا دوباره برایتان ارسال کنیم.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert shadow-sm fade show d-flex align-items-center mb-4 py-3" role="alert" style="background-color: #f8fdfa; border-right: 4px solid #15803d; border-radius: 0.5rem; border-left: none; border-top: none; border-bottom: none;">
            <i class="bi bi-check-circle-fill ms-2 fs-5 text-success"></i>
            <div class="flex-grow-1 text-success fw-medium" style="font-size: 0.85rem;">
                یک لینک تأیید جدید به آدرس ایمیلی که در زمان ثبت‌نام وارد کردید، ارسال شد.
            </div>
            <button type="button" class="btn-close me-auto ms-0" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex flex-column gap-3 mt-4">
        
        <form method="POST" action="{{ route('verification.send') }}" class="w-100">
            @csrf
            <button type="submit" class="btn w-100 shadow d-flex justify-content-center align-items-center gap-2 py-3" style="background: linear-gradient(135deg, #800020 0%, #5c0013 100%); border: none; border-radius: 0.75rem; transition: all 0.3s ease;">
                <i class="bi bi-send-fill fs-5" style="color: #d4af37;"></i>
                <span class="fw-bold" style="color: #d4af37; letter-spacing: 0.5px;">ارسال مجدد ایمیل تأیید</span>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="w-100 text-center mt-2">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none shadow-none p-0" style="color: #6b7280; font-size: 0.9rem; transition: color 0.3s ease;" onmouseover="this.style.color='#800020'" onmouseout="this.style.color='#6b7280'">
                <i class="bi bi-box-arrow-right me-1"></i>
                خروج از حساب کاربری
            </button>
        </form>
        
    </div>
</x-guest-layout>