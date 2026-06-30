<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Bus;

use Illuminate\Contracts\Container\Container;
use Shared\Application\Bus\EventBusInterface;

final class SynchronousEventBus implements EventBusInterface
{
    /** @var array<string, list<string>> */
    private array $listeners = [];

    public function __construct(private readonly Container $container) {}

    public function subscribe(string $eventClass, string $listenerClass): void
    {
        $this->listeners[$eventClass][] = $listenerClass;
    }

    public function publish(object ...$events): void
    {
        foreach ($events as $event) {
            $eventClass = get_class($event);

            foreach ($this->listeners[$eventClass] ?? [] as $listenerClass) {
                $this->container->make($listenerClass)->handle($event);
            }
        }
    }
}
