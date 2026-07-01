<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Commands\UpdateRole;

final readonly class UpdateRoleCommand
{
    public function __construct(
        public int     $roleId,
        public ?string $name          = null,
        public ?string $description   = null,
        public ?array  $permissionIds = null,
    ) {}
}
