<?php

declare(strict_types=1);

namespace Modules\Accounting\Application\Commands\CreateInvoice;

use Illuminate\Support\Facades\DB;
use Modules\Accounting\Domain\Invoice\Entities\Invoice;
use Modules\Accounting\Domain\Invoice\Entities\InvoiceAggregate;
use Modules\Accounting\Domain\Invoice\Entities\InvoiceItem;
use Modules\Accounting\Domain\Invoice\Events\InvoiceCreatedEvent;
use Modules\Accounting\Domain\Invoice\ValueObjects\InvoiceLineItem;
use Modules\Accounting\Domain\Invoice\ValueObjects\Money;
use Shared\Application\Bus\EventBusInterface;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class CreateInvoiceHandler implements CommandHandlerInterface
{
    public function __construct(private readonly EventBusInterface $eventBus) {}

    public function handle(object $command): Invoice
    {
        /** @var CreateInvoiceCommand $command */
        return DB::transaction(function () use ($command) {
            $invoiceNumber = $this->generateNumber($command->companyId);

            $aggregate = InvoiceAggregate::create(
                companyId:       $command->companyId,
                customerId:      $command->customerId,
                invoiceNumber:   $invoiceNumber,
                invoiceDiscount: $command->discount,
            );

            foreach ($command->items as $item) {
                $aggregate->addLineItem(new InvoiceLineItem(
                    description: $item['description'],
                    quantity:    (float) $item['quantity'],
                    unitPrice:   new Money((float) $item['unit_price']),
                    discount:    new Money((float) ($item['discount'] ?? 0)),
                    taxRate:     (float) ($item['tax_rate'] ?? 0.09),
                    productId:   $item['product_id'] ?? null,
                ));
            }

            $invoice = Invoice::create([
                'company_id'     => $aggregate->companyId(),
                'customer_id'    => $aggregate->customerId(),
                'invoice_number' => $aggregate->invoiceNumber(),
                'status'         => $aggregate->status(),
                'issue_date'     => $command->issueDate,
                'due_date'       => $command->dueDate,
                'notes'          => $command->notes,
                'subtotal'       => $aggregate->subtotal()->amount,
                'discount'       => $aggregate->invoiceDiscount(),
                'tax_amount'     => $aggregate->taxAmount()->amount,
                'total'          => $aggregate->total()->amount,
            ]);

            foreach ($aggregate->lineItems() as $lineItem) {
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'product_id'  => $lineItem->productId,
                    'description' => $lineItem->description,
                    'quantity'    => $lineItem->quantity,
                    'unit_price'  => $lineItem->unitPrice->amount,
                    'discount'    => $lineItem->discount->amount,
                    'tax_rate'    => $lineItem->taxRate,
                    'total'       => $lineItem->total->amount,
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
