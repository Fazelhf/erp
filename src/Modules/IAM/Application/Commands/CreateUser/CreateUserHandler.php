<?php

declare(strict_types=1);

namespace Modules\IAM\Application\Commands\CreateUser;

use Modules\IAM\Domain\User\Entities\User;
use Modules\IAM\Domain\User\Events\UserCreatedEvent;
use Shared\Application\Bus\EventBusInterface;
use Shared\Domain\Contracts\CommandHandlerInterface;

final class CreateUserHandler implements CommandHandlerInterface
{
    public function __construct(private readonly EventBusInterface $eventBus) {}

    public function handle(object $command): User
    {
        /** @var CreateUserCommand $command */
        $user = User::create([
            'company_id' => $command->companyId,
            'name'       => $command->name,
            'email'      => $command->email,
            'password'   => $command->password,
            'status'     => $command->status,
        ]);

        $this->eventBus->publish(new UserCreatedEvent($user));

        return $user;
    }
}
