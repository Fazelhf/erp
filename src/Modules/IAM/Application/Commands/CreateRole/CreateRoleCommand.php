<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Commands\CreateRole;

final readonly class CreateRoleCommand
{
    public function __construct(
        public int     $companyId,
        public string  $name,
        public string  $slug,
        public ?string $description   = null,
        public array   $permissionIds = [],
    ) {}
}
