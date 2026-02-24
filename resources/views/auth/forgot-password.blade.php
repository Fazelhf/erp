<x-guest-layout>
    @section('title', 'بازیابی رمز عبور')

    <div class="mb-4 text-muted">
        رمز عبور را فراموش کرده‌اید؟ مشکلی نیست. ایمیل خود را وارد کنید تا لینک بازیابی رمز عبور برای شما ارسال شود.
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">ایمیل</label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-envelope me-1"></i>
                ارسال لینک بازیابی
            </button>
        </div>
    </form>
</x-guest-layout>
