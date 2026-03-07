<?php

namespace Modules\Match\Providers;

use Illuminate\Support\ServiceProvider;

class MatchServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Match';
    protected string $moduleNameLower = 'match';

    public function boot(): void
    {
        $this->registerViews();
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerViews(): void
    {
        $sourcePath = module_path($this->moduleName, 'Resources/views');
        $this->loadViewsFrom([$sourcePath], $this->moduleNameLower);
    }
}
