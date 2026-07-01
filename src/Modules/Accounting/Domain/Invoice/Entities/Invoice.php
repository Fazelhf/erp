<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Invoice\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Accounting\Domain\Invoice\Enums\InvoiceStatusEnum;
use Shared\Domain\Models\Concerns\HasCompanyScope;

class Invoice extends Model
{
    use HasCompanyScope, SoftDeletes;

    protected $fillable = [
        'company_id',
        'customer_id',
        'invoice_number',
        'status',
        'issue_date',
        'due_date',
        'subtotal',
        'discount',
        'tax_amount',
        'total',
        'notes',
    ];

    protected $casts = [
        'status'     => InvoiceStatusEnum::class,
        'issue_date' => 'date',
        'due_date'   => 'date',
        'subtotal'   => 'decimal:2',
        'discount'   => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total'      => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function isPaid(): bool
    {
        return $this->status === InvoiceStatusEnum::Paid;
    }

    public function isCancellable(): bool
    {
        return !in_array($this->status, [InvoiceStatusEnum::Paid, InvoiceStatusEnum::Cancelled]);
    }
}
