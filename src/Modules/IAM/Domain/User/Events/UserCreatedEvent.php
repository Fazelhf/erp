<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\User\Events;

use Modules\IAM\Domain\User\Entities\User;

final class UserCreatedEvent
{
    public function __construct(public readonly User $user) {}
}
