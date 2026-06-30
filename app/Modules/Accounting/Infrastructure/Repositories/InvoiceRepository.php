<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Infrastructure\Repositories;

use App\Modules\Accounting\Domain\Models\Invoice;
use App\Modules\Core\Infrastructure\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class InvoiceRepository extends BaseRepository
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    public function paginateWithFilters(?string $search, ?string $status, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('customer');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest()->paginate($perPage);
    }
}
