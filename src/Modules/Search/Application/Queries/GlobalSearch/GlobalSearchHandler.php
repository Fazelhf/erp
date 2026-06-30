<?php

declare(strict_types=1);

namespace Modules\Search\Application\Queries\GlobalSearch;

use Modules\Search\Domain\Contracts\SearchDriverInterface;
use Shared\Domain\Contracts\QueryHandlerInterface;

final class GlobalSearchHandler implements QueryHandlerInterface
{
    public function __construct(private readonly SearchDriverInterface $driver) {}

    public function handle(object $query): array
    {
        /** @var GlobalSearchQuery $query */
        return $this->driver->search(
            query:   $query->term,
            indices: $query->indices,
            filters: ['company_id' => $query->companyId],
            limit:   $query->limit,
        );
    }
}
