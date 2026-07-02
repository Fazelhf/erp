<?php

declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\IAM\Infrastructure\Persistence\RbacGuard;
use Symfony\Component\HttpFoundation\Response;

final class PermissionMiddleware
{
    public function __construct(private readonly RbacGuard $rbac) {}

    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        if (! $user->isActive()) {
            return response()->json(['success' => false, 'message' => 'Account is inactive or suspended.'], 403);
        }

        if (! $this->rbac->check($user, $permission, $user->company_id)) {
            return response()->json(['success' => false, 'message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
