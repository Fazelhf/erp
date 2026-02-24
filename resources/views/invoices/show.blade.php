@extends('layouts.app')

@section('title', 'مشاهده فاکتور')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2 fw-bold">مشاهده فاکتور</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">فاکتورها</a></li>
                    <li class="breadcrumb-item active">{{ $invoice->invoice_number }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">فاکتور شماره {{ $invoice->invoice_number }}</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil ms-1"></i>
                            ویرایش
                        </a>
                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('آیا از حذف این فاکتور اطمینان دارید؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash ms-1"></i>
                                حذف
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-md-6">
                            <h6 class="text-muted mb-2">اطلاعات مشتری</h6>
                            <p class="mb-1"><strong>نام:</strong> {{ $invoice->customer->name }}</p>
                            @if($invoice->customer->company_name)
                                <p class="mb-1"><strong>شرکت:</strong> {{ $invoice->customer->company_name }}</p>
                            @endif
                            @if($invoice->customer->mobile)
                                <p class="mb-1"><strong>موبایل:</strong> {{ $invoice->customer->mobile }}</p>
                            @endif
                            @if($invoice->customer->address)
                                <p class="mb-0"><strong>آدرس:</strong> {{ $invoice->customer->address }}</p>
                            @endif
                        </div>

                        <div class="col-12 col-md-6">
                            <h6 class="text-muted mb-2">اطلاعات فاکتور</h6>
                            <p class="mb-1"><strong>تاریخ:</strong> {{ \App\Helpers\DateHelper::toJalali($invoice->invoice_date) }}</p>
                            @if($invoice->due_date)
                                <p class="mb-1"><strong>سررسید:</strong> {{ \App\Helpers\DateHelper::toJalali($invoice->due_date) }}</p>
                            @endif
                            <p class="mb-1">
                                <strong>وضعیت:</strong>
                                @php
                                    $status = strtolower(trim($invoice->status));
                                    $badgeClass = match($status) {
                                        'paid' => 'bg-success text-white',
                                        'cancelled' => 'bg-danger text-white',
                                        'sent' => 'bg-info text-dark',
                                        default => 'bg-secondary text-white',
                                    };
                                    $statusLabel = match($status) {
                                        'paid' => 'پرداخت شده',
                                        'cancelled' => 'لغو شده',
                                        'sent' => 'ارسال شده',
                                        'draft' => 'پیش‌نویس',
                                        default => $invoice->status,
                                    };
                                @endphp
                                <span class="badge rounded-pill px-3 py-2 {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </p>
                            <p class="mb-0"><strong>ایجاد کننده:</strong> {{ $invoice->user->name }}</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>محصول</th>
                                    <th>تعداد</th>
                                    <th>قیمت واحد</th>
                                    <th>تخفیف</th>
                                    <th>مالیات</th>
                                    <th>جمع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ number_format($item->quantity) }} {{ $item->product->unit ?? '' }}</td>
                                    <td>{{ number_format($item->unit_price) }} تومان</td>
                                    <td>{{ number_format($item->discount) }} تومان</td>
                                    <td>{{ number_format($item->tax) }} تومان</td>
                                    <td class="fw-bold">{{ number_format($item->total) }} تومان</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">جمع کل:</td>
                                    <td colspan="2" class="fw-bold">{{ number_format($invoice->subtotal) }} تومان</td>
                                </tr>
                                @if($invoice->discount > 0)
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">تخفیف:</td>
                                    <td colspan="2" class="fw-bold text-danger">- {{ number_format($invoice->discount) }} تومان</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="5" class="text-end fw-bold">مالیات (9%):</td>
                                    <td colspan="2" class="fw-bold">{{ number_format($invoice->tax) }} تومان</td>
                                </tr>
                                <tr class="table-primary">
                                    <td colspan="5" class="text-end fw-bold fs-5">مبلغ قابل پرداخت:</td>
                                    <td colspan="2" class="fw-bold fs-5">{{ number_format($invoice->total) }} تومان</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($invoice->notes)
                    <div class="mt-3">
                        <h6 class="text-muted mb-2">یادداشت:</h6>
                        <p class="mb-0">{{ $invoice->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection