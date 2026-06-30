<?php

declare(strict_types=1);

namespace Modules\Search\Domain\Contracts;

interface SearchDriverInterface
{
    public function search(string $query, array $indices = [], array $filters = [], int $limit = 20): array;

    public function index(string $indexName, array $document): bool;

    public function delete(string $indexName, string $documentId): bool;

    public function flush(string $indexName): bool;
}
