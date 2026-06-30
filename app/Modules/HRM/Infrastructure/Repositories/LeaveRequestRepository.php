<?php

declare(strict_types=1);

namespace App\Modules\HRM\Infrastructure\Repositories;

use App\Modules\Core\Infrastructure\Repositories\BaseRepository;
use App\Modules\HRM\Domain\Models\LeaveRequest;
use Illuminate\Database\Eloquent\Collection;

final class LeaveRequestRepository extends BaseRepository
{
    public function __construct(LeaveRequest $model)
    {
        parent::__construct($model);
    }

    public function forUser(int $userId): Collection
    {
        return $this->model->newQuery()
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function allWithUsers(): Collection
    {
        return $this->model->newQuery()
            ->with('user')
            ->latest()
            ->get();
    }
}
