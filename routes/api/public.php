<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => ['ok' => true, 'time' => now()->toISOString()]);

Route::post('/contact', [ContactController::class, 'submit']);
