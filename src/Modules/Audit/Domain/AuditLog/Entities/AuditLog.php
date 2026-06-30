<?php

declare(strict_types=1);

namespace Modules\Audit\Domain\AuditLog\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Audit\Domain\AuditLog\Enums\AuditActionEnum;

class AuditLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'company_id',
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'action'     => AuditActionEnum::class,
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public static function record(
        string $action,
        string $auditableType,
        int    $auditableId,
        array  $oldValues = [],
        array  $newValues = [],
    ): self {
        return self::create([
            'company_id'      => auth()->user()?->company_id,
            'user_id'         => auth()->id(),
            'action'          => $action,
            'auditable_type'  => $auditableType,
            'auditable_id'    => $auditableId,
            'old_values'      => $oldValues,
            'new_values'      => $newValues,
            'ip_address'      => request()->ip(),
            'user_agent'      => request()->userAgent(),
        ]);
    }
}
