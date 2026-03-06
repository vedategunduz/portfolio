<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/api/server-stats', 'serverStatsApi')->name('api.server-stats');
        Route::get('/page-history', 'pageHistory')->name('page-history');
        Route::get('/contact-messages', 'contactMessages')->name('contact-messages');
        Route::post('/contact-messages/{id}/mark-read', 'markMessageAsRead')->name('message.mark-read');
    });
});
