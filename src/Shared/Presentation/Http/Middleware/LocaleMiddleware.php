<?php

declare(strict_types=1);

namespace Shared\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class LocaleMiddleware
{
    private const SUPPORTED = ['fa', 'en'];
    private const DEFAULT   = 'fa';

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->user()?->locale
            ?? $request->header('Accept-Language')
            ?? self::DEFAULT;

        // Normalize: take first two chars, e.g. "en-US" → "en"
        $locale = strtolower(substr((string) $locale, 0, 2));

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = self::DEFAULT;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
