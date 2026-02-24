@extends('layouts.app')

@section('title', 'لیست فاکتورها')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row mb-4">
        <div class="col-12 col-md-6">
            <h1 class="h3 mb-2 fw-bold">فاکتورها</h1>
            <p class="text-muted mb-0">مدیریت فاکتورهای فروش</p>
        </div>
        <div class="col-12 col-md-6 text-end mt-3 mt-md-0">
            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle ms-1"></i>
                ایجاد فاکتور جدید
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-4">
                    <h5 class="mb-0 fw-bold">لیست فاکتورها</h5>
                </div>
                <div class="col-12 col-md-4">
                    <form method="GET" action="{{ route('invoices.index') }}" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="جستجو..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-primary ms-2">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
                <div class="col-12 col-md-4">
                    <form method="GET" action="{{ route('invoices.index') }}">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">همه وضعیت‌ها</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                            <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>ارسال شده</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>پرداخت شده</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($invoices->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>شماره فاکتور</th>
                            <th>مشتری</th>
                            <th>تاریخ</th>
                            <th>سررسید</th>
                            <th>مبلغ کل</th>
                            <th>وضعیت</th>
                            <th class="text-center">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td>
                                <a href="{{ route('invoices.show', $invoice) }}" class="text-decoration-none fw-bold">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td>{{ $invoice->customer->name }}</td>
                            <td>{{ \App\Helpers\DateHelper::toJalali($invoice->invoice_date) }}</td>
                            <td>{{ $invoice->due_date ? \App\Helpers\DateHelper::toJalali($invoice->due_date) : '-' }}</td>
                            <td class="fw-bold">{{ number_format($invoice->total) }} تومان</td>
                            <td>
                                @if(strtolower($invoice->status) == 'paid')
                                    <span class="badge bg-success">پرداخت شده</span>
                                @elseif(strtolower($invoice->status) == 'cancelled')
                                    <span class="badge bg-danger">لغو شده</span>
                                @elseif(strtolower($invoice->status) == 'sent')
                                    <span class="badge bg-info text-dark">ارسال شده</span>
                                @else
                                    <span class="badge bg-secondary">پیش‌نویس</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info" title="مشاهده">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning" title="ویرایش">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('آیا از حذف این فاکتور اطمینان دارید؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white">
                {{ $invoices->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                <p class="text-muted">هیچ فاکتوری یافت نشد</p>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle ms-1"></i>
                    ایجاد فاکتور جدید
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection