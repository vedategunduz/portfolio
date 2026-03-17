<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    public function update(string $locale): RedirectResponse
    {
        $supportedLocales = config('app.supported_locales', ['tr', 'en']);

        abort_unless(in_array($locale, $supportedLocales, true), 404);

        session(['app_locale' => $locale]);
        app()->setLocale($locale);

        return redirect()->back();
    }
}
