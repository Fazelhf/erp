<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Invoice\ValueObjects;

final readonly class Money
{
    public function __construct(
        public float  $amount,
        public string $currency = 'IRR',
    ) {
        if ($amount < 0) {
            throw new \InvalidArgumentException("Money amount cannot be negative.");
        }
    }

    public function add(self $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(self $other): self
    {
        $this->assertSameCurrency($other);
        return new self(max(0, $this->amount - $other->amount), $this->currency);
    }

    public function multiply(float $factor): self
    {
        return new self(round($this->amount * $factor, 2), $this->currency);
    }

    public function equals(self $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }

    private function assertSameCurrency(self $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new \DomainException("Cannot operate on different currencies: {$this->currency} and {$other->currency}.");
        }
    }
}
