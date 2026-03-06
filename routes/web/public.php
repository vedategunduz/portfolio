<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'));

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::prefix('errors')->name('errors.')->group(function () {
    Route::get('/401', fn () => response()->view('errors.401', ['code' => 401], 401))->name('401');
    Route::get('/403', fn () => response()->view('errors.403', ['code' => 403], 403))->name('403');
    Route::get('/404', fn () => response()->view('errors.404', ['code' => 404], 404))->name('404');
    Route::get('/419', fn () => response()->view('errors.419', ['code' => 419], 419))->name('419');
    Route::get('/429', fn () => response()->view('errors.429', ['code' => 429], 429))->name('429');
    Route::get('/500', fn () => response()->view('errors.500', ['code' => 500], 500))->name('500');
    Route::get('/503', fn () => response()->view('errors.503', ['code' => 503], 503))->name('503');
});
