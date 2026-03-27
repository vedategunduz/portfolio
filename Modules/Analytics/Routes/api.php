<?php

use Illuminate\Support\Facades\Route;
use Modules\Analytics\Http\Controllers\Api\BlogAnalyticsIngestController;

Route::prefix('analytics/pageview')->group(function () {
    Route::post('/start', [BlogAnalyticsIngestController::class, 'start']);
    Route::post('/heartbeat', [BlogAnalyticsIngestController::class, 'heartbeat']);
    Route::post('/interaction', [BlogAnalyticsIngestController::class, 'interaction']);
    Route::post('/end', [BlogAnalyticsIngestController::class, 'end']);
});
