<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Queries\GetRoles;

final readonly class GetRolesQuery
{
    public function __construct(public int $companyId) {}
}
