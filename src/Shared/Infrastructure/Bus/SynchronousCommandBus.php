<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Bus;

use Shared\Application\Bus\CommandBusInterface;
use Illuminate\Contracts\Container\Container;
use LogicException;

final class SynchronousCommandBus implements CommandBusInterface
{
    private array $handlers = [];

    public function __construct(private readonly Container $container) {}

    public function register(string $commandClass, string $handlerClass): void
    {
        $this->handlers[$commandClass] = $handlerClass;
    }

    public function dispatch(object $command): mixed
    {
        $commandClass = get_class($command);
        $handlerClass = $this->handlers[$commandClass]
            ?? throw new LogicException("No handler registered for command [{$commandClass}].");

        return $this->container->make($handlerClass)->handle($command);
    }
}
