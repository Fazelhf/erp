<?php

declare(strict_types=1);

namespace Shared\Application\Bus;

interface EventBusInterface
{
    public function publish(object ...$events): void;

    public function subscribe(string $eventClass, string $listenerClass): void;
}
