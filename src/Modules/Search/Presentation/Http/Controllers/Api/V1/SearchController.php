<?php

declare(strict_types=1);

namespace Modules\Search\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Search\Application\Services\SearchService;
use Shared\Application\Bus\QueryBusInterface;
use Shared\Presentation\Http\Controllers\ApiController;

final class SearchController extends ApiController
{
    public function __construct(private readonly SearchService $search) {}

    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'q'       => ['required', 'string', 'min:2'],
            'index'   => ['nullable', 'string', 'in:customers,products,invoices,employees'],
            'per_page'=> ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $results = $this->search->search(
            query:     $data['q'],
            index:     $data['index'] ?? null,
            companyId: $request->user()->company_id,
            perPage:   $data['per_page'] ?? 20,
        );

        return $this->ok($results);
    }
}
