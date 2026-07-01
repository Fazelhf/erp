<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Commands\DeleteRole;

final readonly class DeleteRoleCommand
{
    public function __construct(public int $roleId) {}
}
