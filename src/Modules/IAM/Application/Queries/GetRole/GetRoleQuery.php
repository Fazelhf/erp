<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Queries\GetRole;

final readonly class GetRoleQuery
{
    public function __construct(public int $roleId) {}
}
