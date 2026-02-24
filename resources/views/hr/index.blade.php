@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f4f7f6; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-md-11">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark">مدیریت مرخصی و ماموریت</h4>
                <button class="btn btn-crimson text-white rounded-3 px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#requestModal">
                    <i class="bi bi-plus-lg me-1"></i> ثبت درخواست جدید
                </button>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 p-3">
                        <small class="text-secondary">مانده مرخصی استحقاقی</small>
                        <h4 class="fw-bold text-info mt-1">۱۴ روز</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 p-3 border-start border-crimson border-4">
                        <small class="text-secondary">درخواست‌های در جریان</small>
                        <h4 class="fw-bold text-crimson mt-1">۲ مورد</h4>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-4 shadow-sm overflow-hidden">
                <div class="p-3 border-bottom bg-light d-flex gap-4 text-secondary small fw-bold">
                    <span class="text-crimson border-bottom border-crimson border-2 pb-1 cursor-pointer">همه درخواست‌ها</span>
                    <span class="cursor-pointer">تایید شده</span>
                    <span class="cursor-pointer">رد شده</span>
                </div>

                @foreach($hrRequests as $req)
                <div class="d-flex align-items-center justify-content-between p-4 border-bottom hover-bg-light transition">
                    <div class="d-flex align-items-center gap-4">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi {{ $req->type == 'leave' ? 'bi-calendar2-check text-info' : 'bi-geo-alt text-primary' }} fs-3"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-6">{{ $req->type == 'leave' ? 'مرخصی استحقاقی' : 'ماموریت بازرگانی' }}</div>
                            <div class="text-muted small mt-1">
                                <i class="bi bi-clock me-1"></i> از {{ $req->start_date }} تا {{ $req->end_date }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-5">
                        <div class="text-center">
                            <div class="small text-secondary mb-1">وضعیت</div>
                            @if($req->status == 'pending')
                                <span class="badge rounded-pill bg-crimson-subtle text-crimson px-3 border border-crimson">بررسی نشده</span>
                            @elseif($req->status == 'approved')
                                <span class="badge rounded-pill bg-success-subtle text-success px-3 border border-success">تایید نهایی</span>
                            @else
                                <span class="badge rounded-pill bg-danger-subtle text-danger px-3 border border-danger">عدم موافقت</span>
                            @endif
                        </div>
                        <i class="bi bi-chevron-left fs-5 text-muted"></i>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>

<style>
    /* تعریف رنگ زرشکی اختصاصی */
    .text-crimson { color: #990033 !important; }
    .bg-crimson { background-color: #990033 !important; }
    .btn-crimson { background-color: #990033; border-color: #990033; }
    .btn-crimson:hover { background-color: #80002b; border-color: #80002b; color: white; }
    .border-crimson { border-color: #990033 !important; }
    .bg-crimson-subtle { background-color: rgba(153, 0, 51, 0.1) !important; }
    
    .hover-bg-light:hover { background-color: #fafafa; cursor: pointer; }
    .transition { transition: 0.3s; }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection