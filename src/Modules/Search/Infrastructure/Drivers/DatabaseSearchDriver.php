<?php

declare(strict_types=1);

namespace Modules\Search\Infrastructure\Drivers;

use Illuminate\Support\Facades\DB;
use Modules\Search\Domain\Contracts\SearchDriverInterface;

/**
 * Fallback driver using full-text search on the database.
 * For production, replace with MeilisearchDriver or ElasticsearchDriver.
 */
final class DatabaseSearchDriver implements SearchDriverInterface
{
    public function search(string $query, array $indices = [], array $filters = [], int $limit = 20): array
    {
        $results = [];

        foreach ($indices as $index) {
            $rows = DB::table($index)
                ->whereRaw('MATCH(name) AGAINST(? IN BOOLEAN MODE)', ["{$query}*"])
                ->limit($limit)
                ->get();

            foreach ($rows as $row) {
                $results[] = ['index' => $index, 'document' => (array) $row];
            }
        }

        return $results;
    }

    public function index(string $indexName, array $document): bool
    {
        return true;
    }

    public function delete(string $indexName, string $documentId): bool
    {
        return true;
    }

    public function flush(string $indexName): bool
    {
        return true;
    }
}
