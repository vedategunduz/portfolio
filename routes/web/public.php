<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('home'));

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
