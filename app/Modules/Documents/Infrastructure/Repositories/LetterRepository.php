<?php

declare(strict_types=1);

namespace App\Modules\Documents\Infrastructure\Repositories;

use App\Modules\Core\Infrastructure\Repositories\BaseRepository;
use App\Modules\Documents\Domain\Models\Letter;
use App\Modules\Documents\Domain\Models\LetterAction;
use Illuminate\Database\Eloquent\Collection;

final class LetterRepository extends BaseRepository
{
    public function __construct(Letter $model)
    {
        parent::__construct($model);
    }

    public function inboxForUser(int $userId): Collection
    {
        return LetterAction::where('to_user_id', $userId)
            ->with(['letter', 'sender'])
            ->latest()
            ->get();
    }
}
