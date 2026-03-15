<?php

namespace Modules\Training\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Training\Livewire\TrainingList;
use Modules\Training\Livewire\TrainingCalendar;
use Modules\Training\Livewire\RecurringTrainings;
use Modules\Training\Livewire\VenueList;
use Modules\Training\Livewire\AnnouncementCreate;

class TrainingServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Training';
    protected string $moduleNameLower = 'training';

    public function boot(): void
    {
        $this->registerViews();
        $this->registerLivewireComponents();
    }

    protected function registerLivewireComponents(): void
    {
        Livewire::component('training.training-list', TrainingList::class);
        Livewire::component('training.training-calendar', TrainingCalendar::class);
        Livewire::component('training.recurring-trainings', RecurringTrainings::class);
        Livewire::component('training.venue-list', VenueList::class);
        Livewire::component('training.announcement-create', AnnouncementCreate::class);
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
