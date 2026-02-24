@extends('layouts.app')

@section('title', 'مشاهده مشتری')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2 fw-bold">مشاهده مشتری</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">مشتریان</a></li>
                    <li class="breadcrumb-item active">{{ $customer->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">اطلاعات مشتری</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil ms-1"></i>
                            ویرایش
                        </a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('آیا از حذف این مشتری اطمینان دارید؟')">
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
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">نام و نام خانوادگی</strong>
                            <p class="mb-0">{{ $customer->name }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">نام شرکت</strong>
                            <p class="mb-0">{{ $customer->company_name ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">ایمیل</strong>
                            <p class="mb-0">{{ $customer->email ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">موبایل</strong>
                            <p class="mb-0">{{ $customer->mobile ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">تلفن</strong>
                            <p class="mb-0">{{ $customer->phone ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">وضعیت</strong>
                            <p class="mb-0">
                                @if($customer->is_active)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-secondary">غیرفعال</span>
                                @endif
                            </p>
                        </div>
                        @if($customer->national_id)
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">کد ملی</strong>
                            <p class="mb-0">{{ $customer->national_id }}</p>
                        </div>
                        @endif
                        @if($customer->economic_code)
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">کد اقتصادی</strong>
                            <p class="mb-0">{{ $customer->economic_code }}</p>
                        </div>
                        @endif
                        @if($customer->address)
                        <div class="col-12">
                            <strong class="text-muted d-block mb-1">آدرس</strong>
                            <p class="mb-0">{{ $customer->address }}</p>
                        </div>
                        @endif
                        @if($customer->notes)
                        <div class="col-12">
                            <strong class="text-muted d-block mb-1">یادداشت</strong>
                            <p class="mb-0">{{ $customer->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">فاکتورهای مشتری</h5>
                </div>
                <div class="card-body">
                    @if($customer->invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>شماره فاکتور</th>
                                        <th>تاریخ</th>
                                        <th>مبلغ</th>
                                        <th>وضعیت</th>
                                        <th class="text-center">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customer->invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ \App\Helpers\DateHelper::toJalali($invoice->invoice_date) }}</td>
                                        <td>{{ number_format($invoice->total) }} تومان</td>
                                        <td>
                                            @php
                                                $statusClasses = [
                                                    'draft' => 'bg-secondary',
                                                    'sent' => 'bg-info',
                                                    'paid' => 'bg-success',
                                                    'cancelled' => 'bg-danger'
                                                ];
                                                $statusLabels = [
                                                    'draft' => 'پیش‌نویس',
                                                    'sent' => 'ارسال شده',
                                                    'paid' => 'پرداخت شده',
                                                    'cancelled' => 'لغو شده'
                                                ];
                                            @endphp
                                            <span class="badge {{ $statusClasses[$invoice->status] ?? 'bg-secondary' }}">
                                                {{ $statusLabels[$invoice->status] ?? $invoice->status }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-3 mb-0">هیچ فاکتوری ثبت نشده است</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

