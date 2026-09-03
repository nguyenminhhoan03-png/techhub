<?php

declare(strict_types=1);

namespace Shared\Infrastructure\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    public const SUPPORTED_LOCALES = ['vi', 'en'];

    /**
     * Handle an incoming request and set application locale.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $queryLocale = $request->query('lang');
        $headerLocale = $request->header('X-Locale');

        if (is_string($queryLocale) && in_array($queryLocale, self::SUPPORTED_LOCALES, true)) {
            $locale = $queryLocale;
            if ($request->hasSession()) {
                $request->session()->put('locale', $locale);
            }
            cookie()->queue(cookie()->forever('locale', $locale));
        } elseif (is_string($headerLocale) && in_array($headerLocale, self::SUPPORTED_LOCALES, true)) {
            $locale = $headerLocale;
        } else {
            /** @var string|null $sessionLocale */
            $sessionLocale = $request->hasSession() ? $request->session()->get('locale') : null;
            $locale = $sessionLocale ?? $request->cookie('locale', config('app.locale', 'vi'));
        }

        if (! is_string($locale) || ! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = config('app.locale', 'vi');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
