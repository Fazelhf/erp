<?php

declare(strict_types=1);

namespace Modules\IAM\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\IAM\Application\Commands\UpdateProfile\UpdateProfileCommand;
use Modules\IAM\Presentation\Http\Requests\UpdateProfileRequest;
use Shared\Application\Bus\CommandBusInterface;

class ProfileController extends Controller
{
    public function __construct(private readonly CommandBusInterface $commandBus) {}

    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $this->commandBus->dispatch(new UpdateProfileCommand(
            userId: $request->user()->id,
            name:   $request->validated('name'),
            email:  $request->validated('email'),
        ));

        return back()->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        auth()->logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
