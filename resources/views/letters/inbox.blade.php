@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa; min-height: 100vh; font-family: 'Tahoma', sans-serif;">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold text-dark">صندوق پیام‌ها</h3>
                <a href="{{ route('letters.create') }}" class="btn btn-warning text-white rounded-pill px-4 shadow-sm fw-bold">
                    <i class="bi bi-plus-lg me-1"></i> پیام جدید
                </a>
            </div>

            <div class="d-flex gap-4 mb-3 border-bottom pb-2">
                <div class="text-warning border-bottom border-warning border-3 pb-2 fw-bold cursor-pointer">همه پیام‌ها</div>
                <div class="text-secondary cursor-pointer">خوانده نشده</div>
                <div class="text-secondary cursor-pointer">ارسال شده</div>
                <div class="text-secondary cursor-pointer">آرشیو</div>
            </div>

            <div class="bg-white rounded-4 shadow-sm overflow-hidden">
                @foreach($letters as $action)
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom transition-all hover-shadow" style="cursor: pointer;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm" 
                             style="width: 45px; height: 45px; background: linear-gradient(135deg, #6c757d, #adb5bd);">
                            {{ mb_substr($action->sender->name, 0, 1) }}
                        </div>
                        
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-dark" style="font-size: 1rem;">{{ $action->sender->name }}</span>
                                <span class="badge rounded-pill bg-light text-secondary border fw-normal" style="font-size: 0.7rem;">داخلی</span>
                            </div>
                            <div class="text-dark mt-1" style="font-size: 0.9rem;">
                                <strong>{{ $action->letter->title }}</strong> 
                                <span class="text-secondary ms-2">— {{ Str::limit($action->letter->content, 80) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        @if($action->letter->attachment)
                            <i class="bi bi-paperclip text-secondary fs-5"></i>
                        @endif
                        <span class="text-muted small">{{ $action->created_at->diffForHumans() }}</span>
                        <div class="dropdown">
                            <button class="btn btn-link text-secondary p-0" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i>مشاهده</a></li>
                                <li><a class="dropdown-item" href="#"><i class="bi bi-archive me-2"></i>آرشیو</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i>حذف</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($letters->isEmpty())
                <div class="p-5 text-center text-muted">
                    <i class="bi bi-envelope-open fs-1 d-block mb-3"></i>
                    پیامی یافت نشد.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        background-color: #fcfcfc;
        box-shadow: inset 4px 0 0 #ffc107;
    }
    .transition-all { transition: all 0.2s ease-in-out; }
</style>
@endsection