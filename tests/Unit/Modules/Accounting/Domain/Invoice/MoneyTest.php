<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Accounting\Domain\Invoice;

use Modules\Accounting\Domain\Invoice\ValueObjects\Money;
use PHPUnit\Framework\TestCase;

final class MoneyTest extends TestCase
{
    public function test_creates_with_amount_and_currency(): void
    {
        $money = new Money(100.0, 'IRR');

        $this->assertSame(100.0, $money->amount);
        $this->assertSame('IRR', $money->currency);
    }

    public function test_add_returns_new_instance_with_sum(): void
    {
        $a = new Money(100.0, 'IRR');
        $b = new Money(50.0, 'IRR');

        $result = $a->add($b);

        $this->assertSame(150.0, $result->amount);
        $this->assertNotSame($a, $result);
    }

    public function test_subtract_returns_new_instance_with_difference(): void
    {
        $a = new Money(100.0, 'IRR');
        $b = new Money(30.0, 'IRR');

        $result = $a->subtract($b);

        $this->assertSame(70.0, $result->amount);
    }

    public function test_multiply_returns_scaled_value(): void
    {
        $money = new Money(200.0, 'IRR');

        $result = $money->multiply(0.09);

        $this->assertEqualsWithDelta(18.0, $result->amount, 0.001);
    }

    public function test_add_throws_on_currency_mismatch(): void
    {
        $this->expectException(\DomainException::class);

        (new Money(100.0, 'IRR'))->add(new Money(50.0, 'USD'));
    }

    public function test_subtract_throws_on_currency_mismatch(): void
    {
        $this->expectException(\DomainException::class);

        (new Money(100.0, 'IRR'))->subtract(new Money(50.0, 'USD'));
    }

    public function test_equals_returns_true_for_same_amount_and_currency(): void
    {
        $a = new Money(100.0, 'IRR');
        $b = new Money(100.0, 'IRR');

        $this->assertTrue($a->equals($b));
    }

    public function test_equals_returns_false_for_different_amount(): void
    {
        $this->assertFalse((new Money(100.0, 'IRR'))->equals(new Money(200.0, 'IRR')));
    }

    public function test_equals_returns_false_for_different_currency(): void
    {
        $this->assertFalse((new Money(100.0, 'IRR'))->equals(new Money(100.0, 'USD')));
    }

    public function test_multiply_by_zero_returns_zero(): void
    {
        $money = new Money(500.0, 'IRR');

        $this->assertSame(0.0, $money->multiply(0)->amount);
    }

    public function test_is_immutable(): void
    {
        $original = new Money(100.0, 'IRR');
        $original->add(new Money(50.0, 'IRR'));

        $this->assertSame(100.0, $original->amount);
    }
}
