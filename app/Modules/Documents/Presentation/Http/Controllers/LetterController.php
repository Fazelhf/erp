<?php

declare(strict_types=1);

namespace App\Modules\Documents\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Documents\Application\Actions\ReviewLetterAction;
use App\Modules\Documents\Application\Actions\SendLetterAction;
use App\Modules\Documents\Application\DTOs\LetterData;
use App\Modules\Documents\Domain\Enums\LetterStatusEnum;
use App\Modules\Documents\Domain\Models\LetterAction;
use App\Modules\Documents\Infrastructure\Repositories\LetterRepository;
use App\Modules\Documents\Presentation\Http\Requests\SendLetterRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class LetterController extends Controller
{
    public function __construct(
        private readonly LetterRepository   $repository,
        private readonly SendLetterAction   $sendAction,
        private readonly ReviewLetterAction $reviewAction,
    ) {}

    public function create(): View
    {
        return view('letters.create');
    }

    public function store(SendLetterRequest $request): RedirectResponse
    {
        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('letters', 'public');
        }

        $this->sendAction->execute(new LetterData(
            title:          $request->validated('title'),
            content:        $request->validated('content'),
            toUserId:       (int) $request->validated('to_user_id'),
            description:    $request->validated('description'),
            attachmentPath: $attachmentPath,
        ));

        return redirect()->route('letters.inbox')->with('success', 'نامه با موفقیت ارسال شد.');
    }

    public function inbox(Request $request): View
    {
        $letters = $this->repository->inboxForUser($request->user()->id);

        return view('letters.inbox', compact('letters'));
    }

    public function updateStatus(Request $request, LetterAction $letterAction): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        $this->reviewAction->execute(
            $letterAction,
            LetterStatusEnum::from($request->validated('status')),
            $request->user()->id,
        );

        return back()->with('success', 'وضعیت نامه با موفقیت تغییر کرد.');
    }
}
