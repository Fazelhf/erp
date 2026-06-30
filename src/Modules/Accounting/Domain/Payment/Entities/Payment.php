<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Accounting\Domain\Invoice\Entities\Invoice;

class Payment extends Model
{
    protected $fillable = [
        'company_id',
        'invoice_id',
        'amount',
        'currency',
        'method',
        'reference',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
