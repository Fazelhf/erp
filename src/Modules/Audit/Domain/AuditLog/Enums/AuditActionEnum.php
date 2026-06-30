<?php

declare(strict_types=1);

namespace Modules\Audit\Domain\AuditLog\Enums;

enum AuditActionEnum: string
{
    case Created = 'created';
    case Updated = 'updated';
    case Deleted = 'deleted';
    case Viewed  = 'viewed';
    case Login   = 'login';
    case Logout  = 'logout';
}
