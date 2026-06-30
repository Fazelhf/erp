<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Queries\GetInvoices;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Accounting\Domain\Invoice\Entities\Invoice;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetInvoicesHandler implements QueryHandlerInterface
{
    public function handle(object $query): LengthAwarePaginator
    {
        /** @var GetInvoicesQuery $query */
        return Invoice::query()
            ->where('company_id', $query->companyId)
            ->with('items')
            ->when($query->search, fn ($q, $s) => $q->where('invoice_number', 'like', "%{$s}%"))
            ->when($query->status, fn ($q, $s) => $q->where('status', $s))
            ->when($query->customerId, fn ($q, $c) => $q->where('customer_id', $c))
            ->latest()
            ->paginate($query->perPage);
    }
}
