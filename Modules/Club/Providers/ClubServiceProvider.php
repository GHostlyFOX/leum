<?php

namespace Modules\Club\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Club\Http\Livewire\Seasons;

class ClubServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Club';
    protected string $moduleNameLower = 'club';

    public function boot(): void
    {
        $this->registerViews();
        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('club::seasons', Seasons::class);
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
