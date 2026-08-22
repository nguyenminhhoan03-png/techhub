<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class RemoveIndexPhpMiddleware
{
    /**
     * Handle an incoming request.
     * Enforce Clean URLs for Senior SEO standard:
     * If incoming request URI starts with or contains 'index.php', perform a 301 Permanent Redirect to the clean path.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rawUri = (string) $request->server('REQUEST_URI', '');

        if ($rawUri !== '' && str_contains($rawUri, 'index.php')) {
            $cleaned = preg_replace('#^/index\.php(/.*)?$#', '$1', $rawUri);
            if ($cleaned === '' || $cleaned === false) {
                $cleaned = '/';
            }
            if (! str_starts_with($cleaned, '/')) {
                $cleaned = '/' . $cleaned;
            }

            $targetUrl = $request->getSchemeAndHttpHost() . $cleaned;

            return redirect()->away($targetUrl, 301);
        }

        return $next($request);
    }
}
