<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Application\Actions;

use App\Modules\Accounting\Domain\Exceptions\InvoiceException;
use App\Modules\Accounting\Domain\Models\Invoice;
use App\Modules\Accounting\Infrastructure\Repositories\InvoiceRepository;

final class DeleteInvoiceAction
{
    public function __construct(private readonly InvoiceRepository $repository) {}

    public function execute(Invoice $invoice): bool
    {
        if ($invoice->isPaid()) {
            throw InvoiceException::cannotDeletePaid();
        }

        return $this->repository->delete($invoice);
    }
}
