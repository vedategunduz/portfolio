<?php

use Illuminate\Support\Facades\Route;
use Modules\Contact\Http\Controllers\Admin\ContactMessageController as AdminContactMessageController;
use Modules\Contact\Http\Controllers\ContactController;

Route::middleware('web')->group(function () {
    Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::get('/contact-messages', [AdminContactMessageController::class, 'index'])->name('contact-messages');
        Route::post('/contact-messages/{id}/mark-read', [AdminContactMessageController::class, 'markRead'])->name('message.mark-read');
    });
});
