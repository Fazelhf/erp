@extends('layouts.app')

@section('title', 'ویرایش فاکتور')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-2 fw-bold">ویرایش فاکتور</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">داشبورد</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">فاکتورها</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a></li>
                    <li class="breadcrumb-item active">ویرایش</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">ویرایش فاکتور شماره {{ $invoice->invoice_number }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoices.update', $invoice) }}" method="POST" id="invoiceForm">
                        @csrf
                        @method('PATCH')

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <label for="customer_id" class="form-label">مشتری <span class="text-danger">*</span></label>
                                <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                                    <option value="">انتخاب مشتری</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id', $invoice->customer_id) == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} @if($customer->company_name) - {{ $customer->company_name }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="invoice_date" class="form-label">تاریخ فاکتور <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('invoice_date') is-invalid @enderror" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', \App\Helpers\DateHelper::toJalali($invoice->invoice_date)) }}" data-jdp required>
                                @error('invoice_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="due_date" class="form-label">تاریخ سررسید</label>
                                <input type="text" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ old('due_date', $invoice->due_date ? \App\Helpers\DateHelper::toJalali($invoice->due_date) : '') }}" data-jdp>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="status" class="form-label">وضعیت <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="draft" {{ old('status', $invoice->status) == 'draft' ? 'selected' : '' }}>پیش‌نویس</option>
                                    <option value="sent" {{ old('status', $invoice->status) == 'sent' ? 'selected' : '' }}>ارسال شده</option>
                                    <option value="paid" {{ old('status', $invoice->status) == 'paid' ? 'selected' : '' }}>پرداخت شده</option>
                                    <option value="cancelled" {{ old('status', $invoice->status) == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <h5 class="fw-bold mb-3">آیتم‌های فاکتور</h5>
                            <div id="itemsContainer">
                                @foreach($invoice->items as $index => $item)
                                <div class="item-row card mb-3 p-3">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label">محصول <span class="text-danger">*</span></label>
                                            <select class="form-select product-select" name="items[{{ $index }}][product_id]" required>
                                                <option value="">انتخاب محصول</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-unit="{{ $product->unit }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }} - {{ number_format($product->price) }} تومان
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label">تعداد <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control quantity-input" name="items[{{ $index }}][quantity]" min="1" value="{{ $item->quantity }}" required>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label">قیمت واحد <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" class="form-control unit-price-input" name="items[{{ $index }}][unit_price]" min="0" value="{{ $item->unit_price }}" required>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label">تخفیف</label>
                                            <input type="number" step="0.01" class="form-control discount-input" name="items[{{ $index }}][discount]" min="0" value="{{ $item->discount }}">
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label">جمع</label>
                                            <input type="text" class="form-control item-total" readonly value="{{ number_format($item->total) }} تومان">
                                            <button type="button" class="btn btn-sm btn-danger mt-2 w-100 remove-item" {{ $invoice->items->count() == 1 ? 'style="display: none;"' : '' }}>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-primary" id="addItem">
                                <i class="bi bi-plus-circle ms-1"></i>
                                افزودن آیتم
                            </button>
                        </div>

                        <hr>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6">
                                <label for="discount" class="form-label">تخفیف کل (تومان)</label>
                                <input type="number" step="0.01" class="form-control" id="totalDiscount" name="discount" min="0" value="{{ old('discount', $invoice->discount) }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="card bg-light p-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>جمع کل:</span>
                                        <span id="subtotal">{{ number_format($invoice->subtotal) }} تومان</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>تخفیف:</span>
                                        <span id="discountAmount">{{ number_format($invoice->discount) }} تومان</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>مالیات (9%):</span>
                                        <span id="taxAmount">{{ number_format($invoice->tax) }} تومان</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold fs-5">
                                        <span>قابل پرداخت:</span>
                                        <span id="totalAmount">{{ number_format($invoice->total) }} تومان</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">یادداشت</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle ms-1"></i>
                                انصراف
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle ms-1"></i>
                                ذخیره تغییرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    jalaliDatepicker.startWatch();
});

let itemIndex = {{ $invoice->items->count() }};

document.getElementById('addItem').addEventListener('click', function() {
    const container = document.getElementById('itemsContainer');
    const newItem = container.firstElementChild.cloneNode(true);
    
    newItem.querySelectorAll('input, select').forEach(input => {
        if (input.name) {
            input.name = input.name.replace(/\[\d+\]/, '[' + itemIndex + ']');
        }
        if (input.classList.contains('product-select')) {
            input.value = '';
        } else if (input.classList.contains('quantity-input')) {
            input.value = 1;
        } else if (input.classList.contains('unit-price-input')) {
            input.value = '';
        } else if (input.classList.contains('discount-input')) {
            input.value = 0;
        } else if (input.classList.contains('item-total')) {
            input.value = '0 تومان';
        }
    });
    
    newItem.querySelector('.remove-item').style.display = 'block';
    container.appendChild(newItem);
    itemIndex++;
    
    attachItemEvents(newItem);
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.remove-item')) {
        const itemRow = e.target.closest('.item-row');
        if (document.getElementById('itemsContainer').children.length > 1) {
            itemRow.remove();
            calculateTotals();
        }
    }
});

function attachItemEvents(itemRow) {
    const productSelect = itemRow.querySelector('.product-select');
    const quantityInput = itemRow.querySelector('.quantity-input');
    const unitPriceInput = itemRow.querySelector('.unit-price-input');
    const discountInput = itemRow.querySelector('.discount-input');
    
    productSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            unitPriceInput.value = selectedOption.dataset.price;
            calculateItemTotal(itemRow);
        }
    });
    
    [quantityInput, unitPriceInput, discountInput].forEach(input => {
        input.addEventListener('input', function() {
            calculateItemTotal(itemRow);
        });
    });
}

function calculateItemTotal(itemRow) {
    const quantity = parseFloat(itemRow.querySelector('.quantity-input').value) || 0;
    const unitPrice = parseFloat(itemRow.querySelector('.unit-price-input').value) || 0;
    const discount = parseFloat(itemRow.querySelector('.discount-input').value) || 0;
    
    const subtotal = (unitPrice * quantity) - discount;
    const tax = subtotal * 0.09;
    const total = subtotal + tax;
    
    itemRow.querySelector('.item-total').value = new Intl.NumberFormat('fa-IR').format(total) + ' تومان';
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    
    document.querySelectorAll('.item-row').forEach(itemRow => {
        const quantity = parseFloat(itemRow.querySelector('.quantity-input').value) || 0;
        const unitPrice = parseFloat(itemRow.querySelector('.unit-price-input').value) || 0;
        const discount = parseFloat(itemRow.querySelector('.discount-input').value) || 0;
        
        subtotal += (unitPrice * quantity) - discount;
    });
    
    const totalDiscount = parseFloat(document.getElementById('totalDiscount').value) || 0;
    const finalSubtotal = subtotal - totalDiscount;
    const tax = finalSubtotal * 0.09;
    const total = finalSubtotal + tax;
    
    document.getElementById('subtotal').textContent = new Intl.NumberFormat('fa-IR').format(subtotal) + ' تومان';
    document.getElementById('discountAmount').textContent = new Intl.NumberFormat('fa-IR').format(totalDiscount) + ' تومان';
    document.getElementById('taxAmount').textContent = new Intl.NumberFormat('fa-IR').format(tax) + ' تومان';
    document.getElementById('totalAmount').textContent = new Intl.NumberFormat('fa-IR').format(total) + ' تومان';
}

document.getElementById('totalDiscount').addEventListener('input', calculateTotals);

document.querySelectorAll('.item-row').forEach(itemRow => {
    attachItemEvents(itemRow);
    calculateItemTotal(itemRow);
});
</script>
@endpush
@endsection

