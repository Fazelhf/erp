<?php

declare(strict_types=1);

namespace Modules\Auth\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Shared\Presentation\Http\Controllers\ApiController;

final class AuthController extends ApiController
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $user = Auth::user();

        if (! $user->isActive()) {
            Auth::logout();
            return response()->json([
                'success' => false,
                'message' => 'Account is inactive or suspended.',
            ], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->ok([
            'token' => $token,
            'user'  => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'company_id' => $user->company_id,
            ],
        ], 'Authenticated');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->ok([
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'company_id' => $user->company_id,
            'locale'     => $user->locale ?? 'fa',
            'timezone'   => $user->timezone ?? 'Asia/Tehran',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok(null, 'Logged out');
    }

    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        return $this->ok(['token' => $token]);
    }
}
