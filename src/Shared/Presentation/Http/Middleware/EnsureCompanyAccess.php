<?php

declare(strict_types=1);

namespace Shared\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureCompanyAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->company_id) {
            return response()->json([
                'success' => false,
                'message' => 'No company context for this user.',
                'data'    => null,
            ], Response::HTTP_FORBIDDEN);
        }

        // Bind company_id into the request so all handlers can read it
        $request->merge(['_company_id' => $user->company_id]);

        return $next($request);
    }
}
