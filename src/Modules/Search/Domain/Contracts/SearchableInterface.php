<?php

declare(strict_types=1);

namespace Modules\Search\Domain\Contracts;

interface SearchableInterface
{
    public function toSearchDocument(): array;

    public function searchIndex(): string;

    public function searchId(): string;
}
