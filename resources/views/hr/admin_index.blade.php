@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f4f7f6; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <h4 class="fw-bold text-dark mb-4">پنل تاییدات مدیریت</h4>

            <div class="bg-white rounded-4 shadow-sm overflow-hidden">
                <div class="p-3 border-bottom bg-light d-flex gap-4 text-secondary small fw-bold">
                    <span class="text-crimson border-bottom border-crimson border-2 pb-1">درخواست‌های ارسالی کارمندان</span>
                </div>

                @foreach($hrRequests as $req)
                <div class="d-flex align-items-center justify-content-between p-4 border-bottom hover-bg-light transition">
                    <div class="d-flex align-items-center gap-4">
                        <div class="rounded-circle bg-crimson-subtle d-flex align-items-center justify-content-center fw-bold text-crimson" style="width: 50px; height: 50px;">
                            {{ mb_substr($req->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold fs-6">{{ $req->user->name }} <span class="text-muted fw-normal small">({{ $req->type == 'leave' ? 'مرخصی' : 'ماموریت' }})</span></div>
                            <div class="text-muted small mt-1">علت: {{ $req->reason }}</div>
                            <div class="text-secondary mt-1" style="font-size: 0.8rem;">
                                <i class="bi bi-calendar-range me-1"></i> از {{ $req->start_date }} تا {{ $req->end_date }}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        @if($req->status == 'pending')
                            <form action="{{ route('hr.admin.update', $req->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-crimson btn-sm text-white rounded-pill px-3">تایید</button>
                            </form>
                            <form action="{{ route('hr.admin.update', $req->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill px-3">رد درخواست</button>
                            </form>
                        @else
                            <span class="badge rounded-pill {{ $req->status == 'approved' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-4">
                                {{ $req->status == 'approved' ? 'تایید شده' : 'رد شده' }}
                            </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .text-crimson { color: #990033 !important; }
    .btn-crimson { background-color: #990033; border-color: #990033; }
    .btn-crimson:hover { background-color: #80002b; color: white; }
    .bg-crimson-subtle { background-color: rgba(153, 0, 51, 0.1) !important; }
    .border-crimson { border-color: #990033 !important; }
    .hover-bg-light:hover { background-color: #fafafa; }
    .transition { transition: 0.3s; }
</style>
@endsection