<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Accounting\Domain\Tax;

use Modules\Accounting\Domain\Invoice\ValueObjects\Money;
use Modules\Accounting\Domain\Tax\Services\TaxCalculator;
use PHPUnit\Framework\TestCase;

final class TaxCalculatorTest extends TestCase
{
    private TaxCalculator $calculator;

    protected function setUp(): void
    {
        $this->calculator = new TaxCalculator();
    }

    public function test_standard_rate_is_nine_percent(): void
    {
        $this->assertSame(0.09, $this->calculator->standardRate());
    }

    public function test_calculate_with_standard_rate(): void
    {
        $base   = new Money(1000.0, 'IRR');
        $result = $this->calculator->calculate($base);

        $this->assertEqualsWithDelta(90.0, $result->amount, 0.001);
    }

    public function test_calculate_with_custom_rate(): void
    {
        $base   = new Money(1000.0, 'IRR');
        $result = $this->calculator->calculate($base, 0.05);

        $this->assertEqualsWithDelta(50.0, $result->amount, 0.001);
    }

    public function test_calculate_with_zero_rate(): void
    {
        $base   = new Money(1000.0, 'IRR');
        $result = $this->calculator->calculate($base, 0.0);

        $this->assertSame(0.0, $result->amount);
    }

    public function test_exempt_returns_zero_tax(): void
    {
        $base   = new Money(5000.0, 'IRR');
        $result = $this->calculator->exempt($base);

        $this->assertSame(0.0, $result->amount);
    }

    public function test_result_preserves_currency(): void
    {
        $base   = new Money(1000.0, 'USD');
        $result = $this->calculator->calculate($base);

        $this->assertSame('USD', $result->currency);
    }
}
