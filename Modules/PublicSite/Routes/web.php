<?php

use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Modules\PublicSite\Http\Controllers\ErrorPageController;
use Modules\PublicSite\Http\Controllers\HomeController;
use Modules\PublicSite\Http\Controllers\LocaleController;
use Modules\PublicSite\Http\Controllers\SitemapController;

Route::middleware('web')->group(function () {
    Route::get('/sitemap.xml', [SitemapController::class, 'index'])
        ->withoutMiddleware([
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            \App\Http\Middleware\SetLocaleFromSession::class,
            \Modules\Analytics\Http\Middleware\LogPageHistory::class,
        ])
        ->name('sitemap');

    Route::permanentRedirect('/favicon.ico', '/favicons/favicon-32x32.png');
    Route::permanentRedirect('/site.webmanifest', '/manifest.json');

    Route::get('/', [HomeController::class, 'redirectRoot'])->name('root');

    Route::get('/{locale}', [HomeController::class, 'home'])
        ->where('locale', 'tr|en')
        ->name('home');

    Route::get('/locale/{locale}', [LocaleController::class, 'update'])->name('locale.update');

    Route::prefix('errors')->name('errors.')->controller(ErrorPageController::class)->group(function () {
        Route::get('/401', 'show')->defaults('code', 401)->name('401');
        Route::get('/403', 'show')->defaults('code', 403)->name('403');
        Route::get('/404', 'show')->defaults('code', 404)->name('404');
        Route::get('/419', 'show')->defaults('code', 419)->name('419');
        Route::get('/429', 'show')->defaults('code', 429)->name('429');
        Route::get('/500', 'show')->defaults('code', 500)->name('500');
        Route::get('/503', 'show')->defaults('code', 503)->name('503');
    });
});
