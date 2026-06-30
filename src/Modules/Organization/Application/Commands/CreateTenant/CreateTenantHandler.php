<?php

declare(strict_types=1);

namespace Modules\Organization\Application\Commands\CreateTenant;

use Modules\Organization\Domain\Tenant\Entities\Tenant;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class CreateTenantHandler implements CommandHandlerInterface
{
    public function handle(object $command): Tenant
    {
        /** @var CreateTenantCommand $command */
        return Tenant::create([
            'name'      => $command->name,
            'slug'      => $command->slug,
            'domain'    => $command->domain,
            'plan'      => $command->plan,
            'is_active' => true,
        ]);
    }
}
