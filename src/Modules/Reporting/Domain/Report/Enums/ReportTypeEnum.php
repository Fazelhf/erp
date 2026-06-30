<?php

declare(strict_types=1);

namespace Modules\Reporting\Domain\Report\Enums;

enum ReportTypeEnum: string
{
    case SalesReport       = 'sales';
    case PurchaseReport    = 'purchase';
    case InventoryReport   = 'inventory';
    case FinancialSummary  = 'financial_summary';
    case PayrollReport     = 'payroll';
    case CustomerStatement = 'customer_statement';
    case AuditReport       = 'audit';
    case Custom            = 'custom';

    public function label(): string
    {
        return match($this) {
            self::SalesReport       => 'گزارش فروش',
            self::PurchaseReport    => 'گزارش خرید',
            self::InventoryReport   => 'گزارش موجودی',
            self::FinancialSummary  => 'خلاصه مالی',
            self::PayrollReport     => 'گزارش حقوق',
            self::CustomerStatement => 'صورت‌حساب مشتری',
            self::AuditReport       => 'گزارش حسابرسی',
            self::Custom            => 'گزارش سفارشی',
        };
    }
}
