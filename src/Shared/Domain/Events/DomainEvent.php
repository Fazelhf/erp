<?php

declare(strict_types=1);

namespace Shared\Domain\Events;

abstract class DomainEvent
{
    public readonly string $eventId;
    public readonly \DateTimeImmutable $occurredAt;

    public function __construct()
    {
        $this->eventId   = \Str::uuid()->toString();
        $this->occurredAt = new \DateTimeImmutable();
    }

    abstract public function eventName(): string;
}
