@extends('layouts.app')

@section('title', 'مشاهده محصول')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2 fw-bold">مشاهده محصول</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">محصولات</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">اطلاعات محصول</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil ms-1"></i>
                            ویرایش
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('آیا از حذف این محصول اطمینان دارید؟')">
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
                            <strong class="text-muted d-block mb-1">نام محصول</strong>
                            <p class="mb-0 fw-bold">{{ $product->name }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">کد محصول (SKU)</strong>
                            <p class="mb-0">{{ $product->sku ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">دسته‌بندی</strong>
                            <p class="mb-0">{{ $product->category ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">واحد</strong>
                            <p class="mb-0">{{ $product->unit }}</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">قیمت</strong>
                            <p class="mb-0 fw-bold text-primary">{{ number_format($product->price) }} تومان</p>
                        </div>
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">موجودی</strong>
                            <p class="mb-0">
                                @if($product->stock > 0)
                                    <span class="badge bg-success fs-6">{{ number_format($product->stock) }} {{ $product->unit }}</span>
                                @else
                                    <span class="badge bg-danger fs-6">ناموجود</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-12 col-md-6">
                            <strong class="text-muted d-block mb-1">وضعیت</strong>
                            <p class="mb-0">
                                @if($product->is_active)
                                    <span class="badge bg-success">فعال</span>
                                @else
                                    <span class="badge bg-secondary">غیرفعال</span>
                                @endif
                            </p>
                        </div>
                        @if($product->description)
                        <div class="col-12">
                            <strong class="text-muted d-block mb-1">توضیحات</strong>
                            <p class="mb-0">{{ $product->description }}</p>
                        </div>
                        @endif
                        @if($product->notes)
                        <div class="col-12">
                            <strong class="text-muted d-block mb-1">یادداشت</strong>
                            <p class="mb-0">{{ $product->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

