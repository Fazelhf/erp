<?php

declare(strict_types=1);

namespace App\Modules\CRM\Infrastructure\Repositories;

use App\Modules\Core\Infrastructure\Repositories\BaseRepository;
use App\Modules\CRM\Domain\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    public function paginateWithSearch(?string $search, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }
}
