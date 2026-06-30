<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Queries\GetUsers;

final readonly class GetUsersQuery
{
    public function __construct(
        public int     $companyId,
        public ?string $search  = null,
        public int     $perPage = 15,
    ) {}
}
