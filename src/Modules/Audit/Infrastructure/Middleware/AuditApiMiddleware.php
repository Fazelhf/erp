<?php

declare(strict_types=1);

namespace Modules\Audit\Infrastructure\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Audit\Domain\AuditLog\Entities\AuditLog;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class AuditApiMiddleware
{
    private const WRITE_METHODS = ['POST', 'PUT', 'PATCH', 'DELETE'];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (
            $request->user() &&
            in_array($request->method(), self::WRITE_METHODS, true) &&
            $response->getStatusCode() < 400
        ) {
            $this->record($request, $response);
        }

        return $response;
    }

    private function record(Request $request, Response $response): void
    {
        try {
            $action = match ($request->method()) {
                'POST'   => 'created',
                'PUT',
                'PATCH'  => 'updated',
                'DELETE' => 'deleted',
                default  => 'updated',
            };

            $statusCode = $response->getStatusCode();
            $id         = 0;

            if (in_array($statusCode, [200, 201], true)) {
                $body = json_decode($response->getContent(), true);
                $id   = $body['data']['id'] ?? 0;
            }

            AuditLog::create([
                'company_id'     => $request->user()->company_id,
                'user_id'        => $request->user()->id,
                'action'         => $action,
                'auditable_type' => $this->resolveResource($request),
                'auditable_id'   => $id,
                'old_values'     => [],
                'new_values'     => [],
                'ip_address'     => $request->ip(),
                'user_agent'     => $request->userAgent(),
            ]);
        } catch (Throwable) {
            // Never let audit logging break the main request
        }
    }

    private function resolveResource(Request $request): string
    {
        $segments = $request->segments();
        // /api/v1/{resource}/{id?} → index 2 is the resource name
        return $segments[2] ?? 'unknown';
    }
}
