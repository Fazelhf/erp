<?php

declare(strict_types=1);

namespace Shared\Domain\Contracts;

interface QueryHandlerInterface
{
    public function handle(object $query): mixed;
}
