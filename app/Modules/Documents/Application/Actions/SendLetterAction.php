<?php

declare(strict_types=1);

namespace App\Modules\Documents\Application\Actions;

use App\Modules\Documents\Application\DTOs\LetterData;
use App\Modules\Documents\Domain\Models\Letter;
use App\Modules\Documents\Domain\Models\LetterAction;
use App\Modules\Documents\Infrastructure\Repositories\LetterRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

final class SendLetterAction
{
    public function __construct(private readonly LetterRepository $repository) {}

    public function execute(LetterData $data): Letter
    {
        return DB::transaction(function () use ($data): Letter {
            $letter = $this->repository->create([
                'letter_no'  => 'LTR-'.now()->timestamp,
                'title'      => $data->title,
                'content'    => $data->content,
                'sender_id'  => Auth::id(),
                'type'       => 'internal',
                'attachment' => $data->attachmentPath,
            ]);

            LetterAction::create([
                'letter_id'    => $letter->id,
                'from_user_id' => Auth::id(),
                'to_user_id'   => $data->toUserId,
                'description'  => $data->description ?? 'جهت بررسی و اقدام',
                'status'       => 'pending',
            ]);

            return $letter;
        });
    }
}
