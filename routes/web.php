<?php

use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
