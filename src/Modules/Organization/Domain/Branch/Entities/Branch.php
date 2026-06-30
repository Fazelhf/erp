<?php

declare(strict_types=1);

namespace Modules\Organization\Domain\Branch\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Organization\Domain\Company\Entities\Company;
use Modules\Organization\Domain\Department\Entities\Department;

class Branch extends Model
{
    use SoftDeletes;

    protected $table = 'branches';

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'address',
        'phone',
        'is_headquarters',
        'is_active',
    ];

    protected $casts = [
        'is_headquarters' => 'boolean',
        'is_active'       => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}
