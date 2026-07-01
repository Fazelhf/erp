<?php

declare(strict_types=1);

namespace Modules\CRM\Domain\Customer\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CRM\Domain\Customer\Enums\CustomerTypeEnum;
use Shared\Domain\Models\Concerns\HasCompanyScope;

class Customer extends Model
{
    use HasCompanyScope, SoftDeletes;

    protected $fillable = [
        'company_id',
        'type',
        'name',
        'national_id',
        'phone',
        'email',
        'address',
        'is_active',
    ];

    protected $casts = [
        'type'      => CustomerTypeEnum::class,
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
