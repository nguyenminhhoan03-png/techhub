<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AssignRequestIdMiddleware
{
    public const HEADER_NAME = 'X-Request-Id';

    /**
     * Assign a unique UUID request ID to incoming request for distributed tracing & logging.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestId = $request->header(self::HEADER_NAME) ?: (string) Str::uuid();

        // Attach request_id to request attributes
        $request->attributes->set('request_id', $requestId);

        // Share with Monolog logging context
        Log::withContext([
            'request_id' => $requestId,
        ]);

        /** @var Response $response */
        $response = $next($request);

        $response->headers->set(self::HEADER_NAME, $requestId);

        return $response;
    }
}
