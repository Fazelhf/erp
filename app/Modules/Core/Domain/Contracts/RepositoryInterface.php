<?php

declare(strict_types=1);

namespace App\Modules\Core\Domain\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function findById(int $id): ?Model;

    public function findByIdOrFail(int $id): Model;

    public function all(): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function create(array $data): Model;

    public function update(Model $model, array $data): Model;

    public function delete(Model $model): bool;
}
