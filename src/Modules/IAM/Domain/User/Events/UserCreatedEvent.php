<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\User\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Modules\IAM\Domain\User\Entities\User;

final class UserCreatedEvent
{
    use Dispatchable;

    public function __construct(public readonly User $user) {}
}
