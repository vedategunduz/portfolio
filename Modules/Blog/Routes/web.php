<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\PostController as ModulePostController;

Route::middleware('web')->group(function () {
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [ModulePostController::class, 'index'])->name('index');
        Route::get('/{slug}', [ModulePostController::class, 'show'])->name('show');
    });
});
