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
        $locale = $request->get('lang');

        if (is_string($locale) && in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $request->session()->put('locale', $locale);
        } else {
            /** @var string $locale */
            $locale = $request->session()->get('locale', config('app.locale', 'vi'));
        }

        if ( ! in_array($locale, self::SUPPORTED_LOCALES, true)) {
            $locale = 'vi';
        }

        App::setLocale($locale);

        return $next($request);
    }
}
