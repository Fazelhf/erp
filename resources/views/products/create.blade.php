@extends('layouts.app')

@section('title', 'افزودن محصول جدید')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2 fw-bold">افزودن محصول جدید</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">محصولات</a></li>
                    <li class="breadcrumb-item active">افزودن جدید</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">اطلاعات محصول</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label">نام محصول <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="sku" class="form-label">کد محصول (SKU)</label>
                                <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku') }}">
                                @error('sku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="category" class="form-label">دسته‌بندی</label>
                                <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" list="categories">
                                <datalist id="categories">
                                    <option value="الکترونیک">
                                    <option value="پوشاک">
                                    <option value="غذایی">
                                    <option value="خودرو">
                                    <option value="لوازم خانگی">
                                    <option value="کتاب">
                                    <option value="ورزشی">
                                    <option value="بهداشتی">
                                </datalist>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="unit" class="form-label">واحد <span class="text-danger">*</span></label>
                                <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                                    <option value="عدد" {{ old('unit', 'عدد') == 'عدد' ? 'selected' : '' }}>عدد</option>
                                    <option value="کیلوگرم" {{ old('unit') == 'کیلوگرم' ? 'selected' : '' }}>کیلوگرم</option>
                                    <option value="گرم" {{ old('unit') == 'گرم' ? 'selected' : '' }}>گرم</option>
                                    <option value="لیتر" {{ old('unit') == 'لیتر' ? 'selected' : '' }}>لیتر</option>
                                    <option value="متر" {{ old('unit') == 'متر' ? 'selected' : '' }}>متر</option>
                                    <option value="بسته" {{ old('unit') == 'بسته' ? 'selected' : '' }}>بسته</option>
                                    <option value="کارتن" {{ old('unit') == 'کارتن' ? 'selected' : '' }}>کارتن</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="price" class="form-label">قیمت (تومان) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="stock" class="form-label">موجودی <span class="text-danger">*</span></label>
                                <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="description" class="form-label">توضیحات</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label">یادداشت</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        فعال
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle ms-1"></i>
                                انصراف
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle ms-1"></i>
                                ذخیره
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

