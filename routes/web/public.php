<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SitemapController;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Locale-free routes (no language in URL)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Redirect root to locale: session preference, else browser Accept-Language, else config default
Route::get('/', function (Request $request) {
    $supported = config('app.supported_locales', ['tr', 'en']);
    $locale = $request->session()->get('app_locale');

    if (! in_array($locale, $supported, true)) {
        $locale = $request->getPreferredLanguage($supported) ?? config('app.locale', 'tr');
    }

    return redirect('/' . $locale, 302);
})->name('root');

// Locale-prefixed public pages (SEO-friendly: /tr, /en)
Route::get('/{locale}', function ($locale) {
    $posts = Post::query()
        ->published()
        ->latestPublished()
        ->with(['user', 'translations'])
        ->take(3)
        ->get();

    return view('home', compact('posts'));
})
    ->where('locale', 'tr|en')
    ->name('home');

// Switch language: redirect to same page in new locale (e.g. /tr → /en)
Route::get('/locale/{locale}', [LocaleController::class, 'update'])->name('locale.update');

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::get('/{slug}', [PostController::class, 'show'])->name('show');
});

Route::prefix('errors')->name('errors.')->group(function () {
    Route::get('/401', fn () => response()->view('errors.401', ['code' => 401], 401))->name('401');
    Route::get('/403', fn () => response()->view('errors.403', ['code' => 403], 403))->name('403');
    Route::get('/404', fn () => response()->view('errors.404', ['code' => 404], 404))->name('404');
    Route::get('/419', fn () => response()->view('errors.419', ['code' => 419], 419))->name('419');
    Route::get('/429', fn () => response()->view('errors.429', ['code' => 429], 429))->name('429');
    Route::get('/500', fn () => response()->view('errors.500', ['code' => 500], 500))->name('500');
    Route::get('/503', fn () => response()->view('errors.503', ['code' => 503], 503))->name('503');
});
