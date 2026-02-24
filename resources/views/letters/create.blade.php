@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">ایجاد پیام جدید</h5>
                    <a href="{{ route('letters.inbox') }}" class="btn-close shadow-none"></a>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('letters.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="text-warning small fw-bold mb-1 uppercase">موضوع پیام</label>
                            <input type="text" name="title" class="form-control border-0 border-bottom rounded-0 px-0 shadow-none fs-5" 
                                   placeholder="موضوع را اینجا بنویسید..." required>
                        </div>

                        <div class="mb-4">
                            <label class="text-secondary small fw-bold mb-1">ارسال به</label>
                            <select name="to_user_id" class="form-select border-0 border-bottom rounded-0 px-0 shadow-none" required>
                                <option value="">انتخاب همکار...</option>
                                @foreach(\App\Models\User::all() as $user)
                                    @if($user->id !== auth()->id())
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4 border rounded-4 p-3 bg-light">
                            <textarea name="content" class="form-control border-0 bg-transparent shadow-none" 
                                      rows="8" placeholder="متن پیام شما..." style="resize: none;" required></textarea>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3 border-top pt-2">
                                <div class="d-flex gap-3 text-secondary">
                                    <label class="cursor-pointer hover-text-dark">
                                        <i class="bi bi-paperclip fs-5"></i>
                                        <input type="file" name="attachment" class="d-none">
                                    </label>
                                    <i class="bi bi-image fs-5 cursor-pointer"></i>
                                    <i class="bi bi-emoji-smile fs-5 cursor-pointer"></i>
                                </div>
                                <span class="text-muted small">فرمت‌های مجاز: PDF, JPG, PNG</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-2">
                            <a href="{{ route('letters.inbox') }}" class="btn btn-light rounded-pill px-4 fw-bold">انصراف</a>
                            <button type="submit" class="btn btn-warning text-white rounded-pill px-5 fw-bold shadow">
                                <i class="bi bi-send-fill me-2"></i> ارسال پیام
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #ffc107 !important;
    }
    .hover-text-dark:hover { color: #212529; }
</style>
@endsection