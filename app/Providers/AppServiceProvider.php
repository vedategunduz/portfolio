<?php

namespace App\Providers;

use App\Services\SuspiciousPatternMatcher;
use App\Services\VisitorClassificationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
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
