<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Bus;

use Shared\Application\Bus\QueryBusInterface;
use Illuminate\Contracts\Container\Container;
use LogicException;

final class SynchronousQueryBus implements QueryBusInterface
{
    private array $handlers = [];

    public function __construct(private readonly Container $container) {}

    public function register(string $queryClass, string $handlerClass): void
    {
        $this->handlers[$queryClass] = $handlerClass;
    }

    public function ask(object $query): mixed
    {
        $queryClass   = get_class($query);
        $handlerClass = $this->handlers[$queryClass]
            ?? throw new LogicException("No handler registered for query [{$queryClass}].");

        return $this->container->make($handlerClass)->handle($query);
    }
}
