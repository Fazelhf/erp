<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Queries\GetUser;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\IAM\Domain\User\Entities\User;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GetUserHandler implements QueryHandlerInterface
{
    public function handle(object $query): User
    {
        /** @var GetUserQuery $query */
        $user = User::withoutGlobalScopes()
            ->with('roles.permissions')
            ->find($query->userId);

        if (! $user) {
            throw (new ModelNotFoundException())->setModel(User::class, $query->userId);
        }

        return $user;
    }
}
