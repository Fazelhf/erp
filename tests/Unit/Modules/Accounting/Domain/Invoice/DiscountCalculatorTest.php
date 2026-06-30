<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Accounting\Domain\Invoice;

use Modules\Accounting\Domain\Invoice\Services\DiscountCalculator;
use Modules\Accounting\Domain\Invoice\ValueObjects\Money;
use PHPUnit\Framework\TestCase;

final class DiscountCalculatorTest extends TestCase
{
    private DiscountCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new DiscountCalculator();
    }

    public function test_percentage_discount_of_ten_percent(): void
    {
        $base   = new Money(1000.0, 'IRR');
        $result = $this->calculator->percentage($base, 10);

        $this->assertSame(100.0, $result->amount);
    }

    public function test_percentage_zero_returns_zero(): void
    {
        $base   = new Money(1000.0, 'IRR');
        $result = $this->calculator->percentage($base, 0);

        $this->assertSame(0.0, $result->amount);
    }

    public function test_percentage_hundred_returns_full_amount(): void
    {
        $base   = new Money(1000.0, 'IRR');
        $result = $this->calculator->percentage($base, 100);

        $this->assertSame(1000.0, $result->amount);
    }

    public function test_percentage_above_hundred_throws(): void
    {
        $this->expectException(\DomainException::class);

        $this->calculator->percentage(new Money(1000.0, 'IRR'), 101);
    }

    public function test_percentage_negative_throws(): void
    {
        $this->expectException(\DomainException::class);

        $this->calculator->percentage(new Money(1000.0, 'IRR'), -1);
    }

    public function test_fixed_discount_returns_discount_amount(): void
    {
        $base     = new Money(1000.0, 'IRR');
        $discount = new Money(200.0, 'IRR');
        $result   = $this->calculator->fixed($discount, $base);

        $this->assertSame(200.0, $result->amount);
    }

    public function test_fixed_discount_exceeding_base_throws(): void
    {
        $this->expectException(\DomainException::class);

        $this->calculator->fixed(
            new Money(1500.0, 'IRR'),
            new Money(1000.0, 'IRR'),
        );
    }

    public function test_fixed_discount_equal_to_base_is_allowed(): void
    {
        $base   = new Money(1000.0, 'IRR');
        $result = $this->calculator->fixed($base, $base);

        $this->assertSame(1000.0, $result->amount);
    }
}
