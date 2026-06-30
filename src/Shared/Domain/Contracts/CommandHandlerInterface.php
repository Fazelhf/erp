<?php

declare(strict_types=1);

namespace Shared\Domain\Contracts;

interface CommandHandlerInterface
{
    public function handle(object $command): mixed;
}
