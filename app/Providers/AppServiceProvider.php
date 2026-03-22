<?php

namespace App\Providers;

use App\Services\ImageOptimizationService;
use App\Services\SuspiciousPatternMatcher;
use App\Services\VisitorClassificationService;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ImageManager::class, function () {
            return new ImageManager(new GdDriver());
        });

        $this->app->singleton(ImageOptimizationService::class, function ($app) {
            return new ImageOptimizationService($app->make(ImageManager::class));
        });

        $this->app->singleton(SuspiciousPatternMatcher::class, function ($app) {
            $config = config('page_history', []);
            return new SuspiciousPatternMatcher(
                $config['suspicious_path_patterns'] ?? [],
                $config['suspicious_query_patterns'] ?? []
            );
        });

        $this->app->singleton(VisitorClassificationService::class, function ($app) {
            return new VisitorClassificationService(
                $app->make(SuspiciousPatternMatcher::class),
                config('page_history', [])
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
