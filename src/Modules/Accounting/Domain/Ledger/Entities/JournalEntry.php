<?php

declare(strict_types=1);

namespace Modules\Accounting\Domain\Ledger\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{
    protected $fillable = [
        'company_id',
        'entry_number',
        'reference_type',
        'reference_id',
        'description',
        'entry_date',
        'is_posted',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'is_posted'  => 'boolean',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(JournalLine::class);
    }

    public function isBalanced(): bool
    {
        $debits  = $this->lines->where('type', 'debit')->sum('amount');
        $credits = $this->lines->where('type', 'credit')->sum('amount');

        return round($debits, 2) === round($credits, 2);
    }
}
