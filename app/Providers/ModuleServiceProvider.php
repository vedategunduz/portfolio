<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $modulesPath = base_path('Modules');

        if (! File::isDirectory($modulesPath)) {
            return;
        }

        foreach (File::directories($modulesPath) as $modulePath) {
            $moduleName = basename($modulePath);

            $this->loadModuleRoutes($modulePath);
            $this->loadModuleMigrations($modulePath);
            $this->loadModuleViews($modulePath, $moduleName);
        }
    }

    private function loadModuleRoutes(string $modulePath): void
    {
        $webPath = $modulePath.'/Routes/web.php';
        if (File::exists($webPath)) {
            $this->loadRoutesFrom($webPath);
        }

        $apiPath = $modulePath.'/Routes/api.php';
        if (File::exists($apiPath)) {
            Route::middleware('api')
                ->prefix('api')
                ->group($apiPath);
        }
    }

    private function loadModuleMigrations(string $modulePath): void
    {
        $migrationPath = $modulePath.'/Database/Migrations';

        if (File::isDirectory($migrationPath)) {
            $this->loadMigrationsFrom($migrationPath);
        }
    }

    private function loadModuleViews(string $modulePath, string $moduleName): void
    {
        $viewsPath = $modulePath.'/Resources/views';

        if (File::isDirectory($viewsPath)) {
            $this->loadViewsFrom($viewsPath, strtolower($moduleName));
        }
    }
}
