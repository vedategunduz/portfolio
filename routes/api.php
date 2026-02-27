<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/health', fn () => ['ok' => true, 'time' => now()->toISOString()]);

// Contact Form Endpoint
Route::post('/contact', [ContactController::class, 'submit']);
