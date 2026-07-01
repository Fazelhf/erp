<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Queries\GetUser;

final readonly class GetUserQuery
{
    public function __construct(public int $userId) {}
}
