<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Modules\Audit\Infrastructure\Middleware\AuditApiMiddleware;
use Modules\IAM\Infrastructure\Middleware\PermissionMiddleware;
use Shared\Presentation\Http\Middleware\EnsureCompanyAccess;
use Shared\Presentation\Http\Middleware\LocaleMiddleware;
use Shared\Presentation\Http\Middleware\SetCompanyContext;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api/v1',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();

        $middleware->alias([
            'company'    => EnsureCompanyAccess::class,
            'locale'     => LocaleMiddleware::class,
            'audit'      => AuditApiMiddleware::class,
            'permission' => PermissionMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Unified JSON error envelope for all API requests
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request): ?JsonResponse {
            if (! $request->expectsJson() && ! str_starts_with($request->path(), 'api/')) {
                return null;
            }

            if ($e instanceof ValidationException) {
                // Include both 'errors' (Laravel standard for assertJsonValidationErrors)
                // and 'data' (our API envelope convention)
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors'  => $e->errors(),
                    'data'    => $e->errors(),
                ], 422);
            }

            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'data'    => null,
                ], 401);
            }

            if ($e instanceof ModelNotFoundException) {
                $model = class_basename($e->getModel());
                return response()->json([
                    'success' => false,
                    'message' => "{$model} not found.",
                    'data'    => null,
                ], 404);
            }

            if ($e instanceof HttpException) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'HTTP error.',
                    'data'    => null,
                ], $e->getStatusCode());
            }

            $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

            return response()->json([
                'success' => false,
                'message' => app()->environment('production') ? 'Server error.' : $e->getMessage(),
                'data'    => null,
            ], $statusCode);
        });
    })->create();
