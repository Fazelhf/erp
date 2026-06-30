<?php

declare(strict_types=1);

namespace App\Modules\Documents\Application\Actions;

use App\Modules\Documents\Domain\Enums\LetterStatusEnum;
use App\Modules\Documents\Domain\Models\LetterAction;

final class ReviewLetterAction
{
    public function execute(LetterAction $action, LetterStatusEnum $status, int $reviewerId): void
    {
        abort_if($action->to_user_id !== $reviewerId, 403, 'دسترسی مجاز نیست.');

        $action->update([
            'status'  => $status->value,
            'is_read' => true,
        ]);

        $action->letter->update(['status' => $status->value]);
    }
}
