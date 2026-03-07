<?php

namespace Modules\File\Providers;

use Illuminate\Support\ServiceProvider;

class FileServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'File';
    protected string $moduleNameLower = 'file';

    public function boot(): void
    {
        //
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
