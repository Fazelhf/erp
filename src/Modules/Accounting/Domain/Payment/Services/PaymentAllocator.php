<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Payment\Services;

use Modules\Accounting\Domain\Invoice\ValueObjects\Money;

/**
 * Allocates a payment amount across multiple invoices (oldest-first by default).
 */
final class PaymentAllocator
{
    /**
     * @param  list<array{invoice_id: int, outstanding: Money}> $invoices
     * @return list<array{invoice_id: int, allocated: Money, remaining_outstanding: Money}>
     */
    public function allocate(Money $payment, array $invoices): array
    {
        $remaining  = $payment;
        $allocations = [];

        foreach ($invoices as $invoice) {
            if ($remaining->amount <= 0) {
                break;
            }

            $outstanding = $invoice['outstanding'];
            $allocated   = $remaining->amount >= $outstanding->amount
                ? $outstanding
                : new Money($remaining->amount, $remaining->currency);

            $remaining      = $remaining->subtract($allocated);
            $allocations[]  = [
                'invoice_id'           => $invoice['invoice_id'],
                'allocated'            => $allocated,
                'remaining_outstanding' => $outstanding->subtract($allocated),
            ];
        }

        return $allocations;
    }
}
