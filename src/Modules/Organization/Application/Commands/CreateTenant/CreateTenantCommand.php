<?php

declare(strict_types=1);

namespace Modules\Organization\Application\Commands\CreateTenant;

final readonly class CreateTenantCommand
{
    public function __construct(
        public string  $name,
        public string  $slug,
        public ?string $domain,
        public string  $plan = 'basic',
    ) {}
}
