<?php

use Illuminate\Support\Facades\Route;
use Modules\Analytics\Http\Controllers\Admin\BlogAnalyticsDashboardController;
use Modules\Analytics\Http\Controllers\Admin\PageHistoryController;

Route::middleware('web')->group(function () {
    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::controller(BlogAnalyticsDashboardController::class)->prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/overview', 'overview')->name('overview');
            Route::get('/overview/data', 'overviewData')->name('overview.data');
        });

        Route::controller(PageHistoryController::class)->prefix('page-history')->name('page-history.')->group(function () {
            Route::get('/raw', 'raw')->name('raw');
            Route::get('/raw/export', 'rawExport')->name('raw.export');
            Route::get('/classified', 'classified')->name('classified');
            Route::get('/suspicious', 'suspicious')->name('suspicious');
        });
    });
});
