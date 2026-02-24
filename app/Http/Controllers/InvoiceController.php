<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Product;
use App\Helpers\DateHelper;
use App\Rules\JalaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index()
    {
        $query = Invoice::with('customer');
        
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        if (request('status')) {
            $query->where('status', request('status'));
        }
        
        $invoices = $query->latest()->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        return view('invoices.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => ['required', new JalaliDate()],
            'due_date' => ['nullable', new JalaliDate()],
            'status' => 'required|in:draft,sent,paid,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'customer_id' => $validated['customer_id'],
            'invoice_date' => DateHelper::toGregorian($validated['invoice_date']),
            'due_date' => $validated['due_date'] ? DateHelper::toGregorian($validated['due_date']) : null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
            'user_id' => auth()->id(),
            'subtotal' => 0,
            'tax' => 0,
            'discount' => 0,
            'total' => 0,
        ]);

        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $itemSubtotal = ($item['unit_price'] * $item['quantity']) - ($item['discount'] ?? 0);
            $tax = $itemSubtotal * 0.09;
            $total = $itemSubtotal + $tax;

            $invoice->items()->create([
                'product_id' => $item['product_id'],
                'product_name' => $product->name,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => $item['discount'] ?? 0,
                'tax' => $tax,
                'total' => $total,
            ]);

            $subtotal += $itemSubtotal;
        }

        $discount = $request->input('discount', 0);
        $tax = ($subtotal - $discount) * 0.09;
        $total = $subtotal - $discount + $tax;

        $invoice->update([
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
        ]);

        return redirect()->route('invoices.index')
            ->with('success', 'فاکتور با موفقیت ایجاد شد.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'user', 'items.product']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $customers = Customer::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();
        $invoice->load('items');
        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_date' => ['required', new JalaliDate()],
            'due_date' => ['nullable', new JalaliDate()],
            'status' => 'required|in:draft,sent,paid,cancelled',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        $invoice->update([
            'customer_id' => $validated['customer_id'],
            'invoice_date' => DateHelper::toGregorian($validated['invoice_date']),
            'due_date' => $validated['due_date'] ? DateHelper::toGregorian($validated['due_date']) : null,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $invoice->items()->delete();

        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $itemSubtotal = ($item['unit_price'] * $item['quantity']) - ($item['discount'] ?? 0);
            $tax = $itemSubtotal * 0.09;
            $total = $itemSubtotal + $tax;

            $invoice->items()->create([
                'product_id' => $item['product_id'],
                'product_name' => $product->name,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => $item['discount'] ?? 0,
                'tax' => $tax,
                'total' => $total,
            ]);

            $subtotal += $itemSubtotal;
        }

        $discount = $request->input('discount', 0);
        $tax = ($subtotal - $discount) * 0.09;
        $total = $subtotal - $discount + $tax;

        $invoice->update([
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
        ]);

        return redirect()->route('invoices.index')
            ->with('success', 'فاکتور با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'فاکتور با موفقیت حذف شد.');
    }
}
