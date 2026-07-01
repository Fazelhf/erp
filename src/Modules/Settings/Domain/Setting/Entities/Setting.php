<?php

declare(strict_types=1);

namespace Modules\Settings\Domain\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Shared\Domain\Models\Concerns\HasCompanyScope;

class Setting extends Model
{
    use HasCompanyScope;
    protected $fillable = [
        'company_id',
        'group',
        'key',
        'value',
        'type',
    ];

    protected $casts = [
        'value' => 'string',
    ];

    public function typedValue(): mixed
    {
        return match ($this->type) {
            'boolean' => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->value,
            'float'   => (float) $this->value,
            'array'   => json_decode($this->value, true),
            default   => $this->value,
        };
    }
}
