<?php

namespace Modules\Tournament\Providers;

use Illuminate\Support\ServiceProvider;

class TournamentServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Tournament';
    protected string $moduleNameLower = 'tournament';

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
