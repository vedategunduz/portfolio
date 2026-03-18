<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    /**
     * Switch language and redirect to same page with new locale in URL (e.g. /tr → /en).
     */
    public function update(string $locale): RedirectResponse
    {
        $supportedLocales = config('app.supported_locales', ['tr', 'en']);

        abort_unless(in_array($locale, $supportedLocales, true), 404);

        session(['app_locale' => $locale]);
        app()->setLocale($locale);

        $previous = url()->previous();
        $path = (string) parse_url($previous, PHP_URL_PATH);
        $fragment = parse_url($previous, PHP_URL_FRAGMENT);
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));

        if (isset($segments[0]) && in_array($segments[0], $supportedLocales, true)) {
            $segments[0] = $locale;
            $newPath = '/' . implode('/', $segments);
        } else {
            $newPath = '/' . $locale;
        }

        $redirectUrl = url($newPath) . ($fragment ? '#' . $fragment : '');

        return redirect($redirectUrl);
    }
}
