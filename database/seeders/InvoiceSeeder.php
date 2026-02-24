<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $users = User::all();
        $products = Product::all();
        
        if ($customers->isEmpty() || $users->isEmpty() || $products->isEmpty()) {
            return;
        }
        
        for ($i = 0; $i < 30; $i++) {
            $customer = $customers->random();
            $user = $users->random();
            $invoiceDate = now()->subDays(rand(0, 365));
            
            $invoice = Invoice::create([
                'invoice_number' => 'INV-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'invoice_date' => $invoiceDate,
                'due_date' => $invoiceDate->copy()->addDays(rand(7, 30)),
                'subtotal' => 0,
                'tax' => 0,
                'discount' => 0,
                'total' => 0,
                'status' => collect(['draft', 'sent', 'paid', 'cancelled'])->random(),
                'notes' => rand(0, 1) ? 'یادداشت تست' : null,
                'user_id' => $user->id,
            ]);
            
            $itemCount = rand(1, 5);
            $subtotal = 0;
            
            for ($j = 0; $j < $itemCount; $j++) {
                $product = $products->random();
                $quantity = rand(1, 10);
                $unitPrice = $product->price;
                $discount = rand(0, 10) * 1000;
                $itemSubtotal = ($unitPrice * $quantity) - $discount;
                $tax = $itemSubtotal * 0.09;
                $total = $itemSubtotal + $tax;
                
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount' => $discount,
                    'tax' => $tax,
                    'total' => $total,
                    'description' => null,
                ]);
                
                $subtotal += $itemSubtotal;
            }
            
            $discount = rand(0, 5) * 10000;
            $tax = ($subtotal - $discount) * 0.09;
            $total = $subtotal - $discount + $tax;
            
            $invoice->update([
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
            ]);
        }
    }
}
