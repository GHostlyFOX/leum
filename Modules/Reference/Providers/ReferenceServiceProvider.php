<?php

namespace Modules\Reference\Providers;

use Illuminate\Support\ServiceProvider;

class ReferenceServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Reference';

    public function boot(): void
    {
        //
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
