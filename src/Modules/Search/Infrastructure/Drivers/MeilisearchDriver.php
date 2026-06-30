<?php

declare(strict_types=1);

namespace Modules\Search\Infrastructure\Drivers;

use Illuminate\Support\Facades\Http;
use Modules\Search\Domain\Contracts\SearchDriverInterface;

final class MeilisearchDriver implements SearchDriverInterface
{
    public function __construct(
        private readonly string $host,
        private readonly string $apiKey,
    ) {}

    public function search(string $query, array $indices = [], array $filters = [], int $limit = 20): array
    {
        $results = [];

        foreach ($indices as $index) {
            $response = Http::withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
                ->post("{$this->host}/multi-search", [
                    'queries' => [['indexUid' => $index, 'q' => $query, 'limit' => $limit]],
                ])
                ->json();

            $results[] = [
                'index'     => $index,
                'hits'      => $response['results'][0]['hits'] ?? [],
                'total'     => $response['results'][0]['estimatedTotalHits'] ?? 0,
            ];
        }

        return $results;
    }

    public function index(string $indexName, array $document): bool
    {
        $response = Http::withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
            ->post("{$this->host}/indexes/{$indexName}/documents", [$document]);

        return $response->successful();
    }

    public function delete(string $indexName, string $documentId): bool
    {
        $response = Http::withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
            ->delete("{$this->host}/indexes/{$indexName}/documents/{$documentId}");

        return $response->successful();
    }

    public function flush(string $indexName): bool
    {
        $response = Http::withHeaders(['Authorization' => "Bearer {$this->apiKey}"])
            ->delete("{$this->host}/indexes/{$indexName}/documents");

        return $response->successful();
    }
}
