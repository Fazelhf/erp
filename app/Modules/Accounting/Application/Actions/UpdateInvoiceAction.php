<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Application\Actions;

use App\Modules\Accounting\Application\DTOs\InvoiceData;
use App\Modules\Accounting\Application\Services\InvoiceCalculationService;
use App\Modules\Accounting\Domain\Exceptions\InvoiceException;
use App\Modules\Accounting\Domain\Models\Invoice;
use App\Modules\Accounting\Infrastructure\Repositories\InvoiceRepository;
use App\Modules\Core\Helpers\DateHelper;
use App\Modules\Inventory\Domain\Models\Product;
use Illuminate\Support\Facades\DB;

final class UpdateInvoiceAction
{
    public function __construct(
        private readonly InvoiceRepository         $repository,
        private readonly InvoiceCalculationService $calculator,
    ) {}

    public function execute(Invoice $invoice, InvoiceData $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data): Invoice {
            $this->repository->update($invoice, [
                'customer_id'  => $data->customerId,
                'invoice_date' => DateHelper::toGregorian($data->invoiceDate),
                'due_date'     => $data->dueDate ? DateHelper::toGregorian($data->dueDate) : null,
                'status'       => $data->status->value,
                'notes'        => $data->notes,
            ]);

            $invoice->items()->delete();

            $itemResults = [];

            foreach ($data->items as $itemData) {
                $product = Product::findOrFail($itemData->productId);
                $calc    = $this->calculator->calculateItem($itemData);

                $invoice->items()->create([
                    'product_id'   => $itemData->productId,
                    'product_name' => $product->name,
                    'quantity'     => $itemData->quantity,
                    'unit_price'   => $itemData->unitPrice,
                    'discount'     => $itemData->discount,
                    'tax'          => $calc['tax'],
                    'total'        => $calc['total'],
                ]);

                $itemResults[] = $calc;
            }

            $totals = $this->calculator->calculateTotals($itemResults, $data->discount);

            $invoice->update($totals);

            return $invoice->fresh(['customer', 'items']);
        });
    }
}
