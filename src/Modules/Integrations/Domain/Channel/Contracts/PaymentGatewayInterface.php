<?php

declare(strict_types=1);

namespace Modules\Integrations\Domain\Channel\Contracts;

interface PaymentGatewayInterface
{
    public function initiate(float $amount, string $currency, array $meta = []): array;

    public function verify(string $transactionId): array;

    public function refund(string $transactionId, float $amount): bool;
}
