<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Commands\UpdateProfile;

use Modules\IAM\Domain\User\Entities\User;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class UpdateProfileHandler implements CommandHandlerInterface
{
    public function handle(object $command): User
    {
        /** @var UpdateProfileCommand $command */
        $user = User::findOrFail($command->userId);

        if ($user->email !== $command->email) {
            $user->email_verified_at = null;
        }

        $user->update([
            'name'  => $command->name,
            'email' => $command->email,
        ]);

        return $user->fresh();
    }
}
