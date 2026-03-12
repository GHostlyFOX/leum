<?php

namespace Modules\Club\Providers;

use Illuminate\Support\ServiceProvider;

class ClubServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Club';
    protected string $moduleNameLower = 'club';

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
        $sourcePath = base_path('Modules/' . $this->moduleName . '/Resources/views');
        $this->loadViewsFrom([$sourcePath], $this->moduleNameLower);
    }
}
