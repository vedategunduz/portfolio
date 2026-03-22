<?php

use App\Http\Controllers\Admin\PageHistoryController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/api/server-stats', 'serverStatsApi')->name('api.server-stats');
        Route::get('/login-history', 'loginHistory')->name('login-history.index');
        Route::get('/profile', 'profile')->name('profile.edit');
        Route::patch('/profile', 'updateProfile')->name('profile.update');
        Route::get('/contact-messages', 'contactMessages')->name('contact-messages');
        Route::post('/contact-messages/{id}/mark-read', 'markMessageAsRead')->name('message.mark-read');
    });

    Route::controller(PageHistoryController::class)->prefix('page-history')->name('page-history.')->group(function () {
        Route::get('/raw', 'raw')->name('raw');
        Route::get('/raw/export', 'rawExport')->name('raw.export');
        Route::get('/classified', 'classified')->name('classified');
        Route::get('/suspicious', 'suspicious')->name('suspicious');
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
