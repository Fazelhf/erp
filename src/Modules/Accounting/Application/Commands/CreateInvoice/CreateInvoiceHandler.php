<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Commands\CreateInvoice;

use Illuminate\Support\Facades\DB;
use Modules\Accounting\Domain\Invoice\Entities\Invoice;
use Modules\Accounting\Domain\Invoice\Entities\InvoiceItem;
use Modules\Accounting\Domain\Invoice\Events\InvoiceCreatedEvent;
use Modules\Accounting\Domain\Tax\Services\TaxCalculationService;
use Shared\Application\Bus\EventBusInterface;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class CreateInvoiceHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly TaxCalculationService $taxService,
        private readonly EventBusInterface     $eventBus,
    ) {}

    public function handle(object $command): Invoice
    {
        /** @var CreateInvoiceCommand $command */
        return DB::transaction(function () use ($command) {
            $calculatedItems = array_map(
                fn ($item) => array_merge($item, $this->taxService->calculateItemTotal(
                    unitPrice: (float) $item['unit_price'],
                    quantity:  (float) $item['quantity'],
                    discount:  (float) ($item['discount'] ?? 0),
                )),
                $command->items,
            );

            $totals = $this->taxService->calculateInvoiceTotals($calculatedItems, $command->discount);

            $invoice = Invoice::create([
                'company_id'     => $command->companyId,
                'customer_id'    => $command->customerId,
                'invoice_number' => $this->generateNumber($command->companyId),
                'status'         => 'draft',
                'issue_date'     => $command->issueDate,
                'due_date'       => $command->dueDate,
                'notes'          => $command->notes,
                ...$totals,
            ]);

            foreach ($calculatedItems as $item) {
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'product_id'  => $item['product_id'] ?? null,
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'discount'    => $item['discount'] ?? 0,
                    'tax_rate'    => $item['tax_rate'],
                    'total'       => $item['total'],
                ]);
            }

            $this->eventBus->publish(new InvoiceCreatedEvent($invoice));

            return $invoice->load('items');
        });
    }

    private function generateNumber(int $companyId): string
    {
        $year  = now()->format('Y');
        $count = Invoice::withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->whereYear('created_at', $year)
            ->count() + 1;

        return sprintf('INV-%d-%s-%04d', $companyId, $year, $count);
    }
}
