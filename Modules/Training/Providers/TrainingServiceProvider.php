<?php

namespace Modules\Training\Providers;

use Illuminate\Support\ServiceProvider;

class TrainingServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Training';
    protected string $moduleNameLower = 'training';

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
