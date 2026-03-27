<?php

use Illuminate\Support\Facades\Route;
use Modules\Contact\Http\Controllers\ContactController;

Route::post('/contact', [ContactController::class, 'submit']);
