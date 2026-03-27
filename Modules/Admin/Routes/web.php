<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\Auth\AdminAuthController;
use Modules\Blog\Http\Controllers\Admin\PostController as AdminPostController;

Route::middleware('web')->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });

    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/api/server-stats', 'serverStatsApi')->name('api.server-stats');
            Route::get('/login-history', 'loginHistory')->name('login-history.index');
            Route::get('/profile', 'profile')->name('profile.edit');
            Route::patch('/profile', 'updateProfile')->name('profile.update');
        });

        Route::controller(AdminPostController::class)->prefix('posts')->name('posts.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::post('/autosave', 'autosaveStore')->name('autosave.store');
            Route::put('/{post}/autosave', 'autosaveUpdate')->name('autosave.update');
            Route::get('/{post}/edit', 'edit')->name('edit');
            Route::put('/{post}', 'update')->name('update');
            Route::delete('/{post}', 'destroy')->name('destroy');
        });
    });
});
