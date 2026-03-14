<?php

namespace Modules\Tournament\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Tournament\Livewire\TournamentList;
use Modules\Tournament\Livewire\TournamentCreate;
use Modules\Tournament\Livewire\TournamentDetail;

class TournamentServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Tournament';
    protected string $moduleNameLower = 'tournament';

    public function boot(): void
    {
        $this->registerViews();
        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('tournament.tournament-list', TournamentList::class);
        Livewire::component('tournament.tournament-create', TournamentCreate::class);
        Livewire::component('tournament.tournament-detail', TournamentDetail::class);
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
