<?php

declare(strict_types=1);

namespace Modules\Organization\Domain\Company\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Organization\Domain\Tenant\Entities\Tenant;
use Modules\Organization\Domain\Branch\Entities\Branch;

class Company extends Model
{
    use SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'tenant_id',
        'name',
        'legal_name',
        'registration_number',
        'tax_id',
        'phone',
        'email',
        'address',
        'logo',
        'currency',
        'fiscal_year_start',
        'is_active',
    ];

    protected $casts = [
        'is_active'          => 'boolean',
        'fiscal_year_start'  => 'date',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }
}
