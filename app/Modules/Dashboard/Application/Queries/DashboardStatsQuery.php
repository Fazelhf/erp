<?php

declare(strict_types=1);

namespace App\Modules\Dashboard\Application\Queries;

use App\Modules\Accounting\Domain\Enums\InvoiceStatusEnum;
use App\Modules\Accounting\Domain\Models\Invoice;
use App\Modules\CRM\Domain\Models\Customer;
use App\Modules\Inventory\Domain\Models\Product;

final class DashboardStatsQuery
{
    public function execute(): array
    {
        return [
            'customers_count'  => Customer::where('is_active', true)->count(),
            'products_count'   => Product::where('is_active', true)->count(),
            'invoices_count'   => Invoice::count(),
            'total_revenue'    => Invoice::where('status', InvoiceStatusEnum::Paid->value)->sum('total'),
            'pending_invoices' => Invoice::whereIn('status', [
                InvoiceStatusEnum::Draft->value,
                InvoiceStatusEnum::Sent->value,
            ])->count(),
            'recent_invoices'  => Invoice::with('customer')->latest()->take(5)->get(),
            'low_stock_count'  => Product::where('stock', '<', 10)->where('is_active', true)->count(),
            'monthly_revenue'  => Invoice::where('status', InvoiceStatusEnum::Paid->value)
                ->whereYear('invoice_date', now()->year)
                ->whereMonth('invoice_date', now()->month)
                ->sum('total'),
        ];
    }
}
