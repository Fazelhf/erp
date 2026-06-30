<?php

declare(strict_types=1);

namespace Modules\Dashboard\Application\Queries\GetDashboardStats;

use Modules\Accounting\Domain\Invoice\Entities\Invoice;
use Modules\Accounting\Domain\Invoice\Enums\InvoiceStatusEnum;
use Modules\CRM\Domain\Customer\Entities\Customer;
use Modules\HRM\Domain\Leave\Entities\LeaveRequest;
use Modules\HRM\Domain\Leave\Enums\LeaveStatusEnum;
use Modules\Inventory\Domain\Product\Entities\Product;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetDashboardStatsHandler implements QueryHandlerInterface
{
    public function handle(object $query): array
    {
        /** @var GetDashboardStatsQuery $query */
        $cid = $query->companyId;

        return [
            'total_customers'     => Customer::where('company_id', $cid)->count(),
            'total_products'      => Product::where('company_id', $cid)->count(),
            'low_stock_products'  => Product::where('company_id', $cid)
                                        ->whereColumn('stock_quantity', '<=', 'min_stock_level')->count(),
            'total_invoices'      => Invoice::where('company_id', $cid)->count(),
            'paid_invoices'       => Invoice::where('company_id', $cid)
                                        ->where('status', InvoiceStatusEnum::Paid)->count(),
            'pending_invoices'    => Invoice::where('company_id', $cid)
                                        ->whereNotIn('status', [InvoiceStatusEnum::Paid, InvoiceStatusEnum::Cancelled])->count(),
            'pending_leave'       => LeaveRequest::where('company_id', $cid)
                                        ->where('status', LeaveStatusEnum::Pending)->count(),
            'revenue_this_month'  => Invoice::where('company_id', $cid)
                                        ->where('status', InvoiceStatusEnum::Paid)
                                        ->whereMonth('updated_at', now()->month)
                                        ->sum('total'),
        ];
    }
}
