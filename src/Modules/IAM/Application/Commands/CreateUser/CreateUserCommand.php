<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Commands\CreateUser;

final readonly class CreateUserCommand
{
    public function __construct(
        public int    $companyId,
        public string $name,
        public string $email,
        public string $password,
        public string $status = 'active',
    ) {}
}
