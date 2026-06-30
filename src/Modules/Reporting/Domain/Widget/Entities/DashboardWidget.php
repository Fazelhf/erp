<?php

declare(strict_types=1);

namespace Modules\Reporting\Domain\Widget\Entities;

use Illuminate\Database\Eloquent\Model;

class DashboardWidget extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'type',
        'title',
        'data_source',
        'config',
        'position',
        'is_visible',
    ];

    protected $casts = [
        'config'     => 'array',
        'position'   => 'array',
        'is_visible' => 'boolean',
    ];
}
