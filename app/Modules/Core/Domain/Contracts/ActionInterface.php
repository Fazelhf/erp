<?php

declare(strict_types=1);

namespace App\Modules\Core\Domain\Contracts;

interface ActionInterface
{
    public function execute(mixed ...$args): mixed;
}
