<?php

declare(strict_types=1);

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Shared\Infrastructure\Http\Middleware\AssignRequestIdMiddleware;
use Shared\Infrastructure\Http\Middleware\ForceJsonResponseMiddleware;
use Shared\Infrastructure\Http\Middleware\SecurityHeadersMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../src/Presentation/Admin/routes/web.php',
            __DIR__ . '/../src/Presentation/Tool/routes/web.php',
            __DIR__ . '/../src/Presentation/UserManagement/routes/web.php',
        ],
        api: [
            __DIR__ . '/../src/Presentation/UserManagement/routes/api.php',
            __DIR__ . '/../src/Presentation/Tool/routes/api.php',
        ],
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append([
            AssignRequestIdMiddleware::class,
            SecurityHeadersMiddleware::class,
        ]);

        $middleware->web(append: [
            Shared\Infrastructure\Http\Middleware\SetLocaleMiddleware::class,
        ]);

        $middleware->api(prepend: [
            ForceJsonResponseMiddleware::class,
        ]);

        $middleware->api(append: [
            'throttle:api',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e): bool {
            if ($request->is('api/*')) {
                return true;
            }

            return $request->expectsJson();
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'The given data was invalid.',
                    'code' => 'VALIDATION_ERROR',
                    'errors' => $e->errors(),
                    'request_id' => $request->attributes->get('request_id'),
                ], 422);
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'code' => 'UNAUTHENTICATED',
                    'request_id' => $request->attributes->get('request_id'),
                ], 401);
            }
        });

        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many requests. Please slow down.',
                    'code' => 'RATE_LIMIT_EXCEEDED',
                    'request_id' => $request->attributes->get('request_id'),
                ], 429);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource or endpoint not found.',
                    'code' => 'NOT_FOUND',
                    'request_id' => $request->attributes->get('request_id'),
                ], 404);
            }
        });
    })->create();
