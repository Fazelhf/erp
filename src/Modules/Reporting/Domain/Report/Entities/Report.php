<?php

declare(strict_types=1);

namespace Modules\Reporting\Domain\Report\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Reporting\Domain\Report\Enums\ReportFormatEnum;
use Modules\Reporting\Domain\Report\Enums\ReportTypeEnum;

class Report extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'created_by',
        'type',
        'name',
        'filters',
        'format',
        'file_path',
        'generated_at',
        'expires_at',
    ];

    protected $casts = [
        'type'         => ReportTypeEnum::class,
        'format'       => ReportFormatEnum::class,
        'filters'      => 'array',
        'generated_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }
}
