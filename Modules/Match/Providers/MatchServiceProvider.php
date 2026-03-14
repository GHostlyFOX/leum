<?php

namespace Modules\Match\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Match\Livewire\MatchList;

class MatchServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Match';
    protected string $moduleNameLower = 'match';

    public function boot(): void
    {
        $this->registerViews();
        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('match.match-list', MatchList::class);
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
