<?php

declare(strict_types=1);

namespace Shared\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetCompanyContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->company_id) {
            $request->merge(['_company_id' => $request->user()->company_id]);
        }

        return $next($request);
    }
}
