<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleFromSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('app.supported_locales', ['tr', 'en']);

        // Prefer locale from URL when present (e.g. /tr, /en)
        $locale = $request->route()?->parameter('locale');
        if (is_string($locale) && in_array($locale, $supportedLocales, true)) {
            $request->session()->put('app_locale', $locale);
        } else {
            $locale = $request->session()->get('app_locale', config('app.locale'));
            if (! in_array($locale, $supportedLocales, true)) {
                $locale = config('app.locale');
            }
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
