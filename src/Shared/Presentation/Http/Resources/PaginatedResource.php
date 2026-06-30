<?php

declare(strict_types=1);

namespace Shared\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class PaginatedResource extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data'  => $this->collection,
            'meta'  => [
                'current_page' => $this->currentPage(),
                'last_page'    => $this->lastPage(),
                'per_page'     => $this->perPage(),
                'total'        => $this->total(),
            ],
            'links' => [
                'first' => $this->url(1),
                'last'  => $this->url($this->lastPage()),
                'prev'  => $this->previousPageUrl(),
                'next'  => $this->nextPageUrl(),
            ],
        ];
    }
}
