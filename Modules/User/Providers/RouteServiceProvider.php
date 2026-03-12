<?php

namespace Modules\User\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();
    }

    public function map(): void
    {
        $this->mapApiV1Routes();
        $this->mapWebRoutes();
    }

    protected function mapApiV1Routes(): void
    {
        Route::prefix('api/v1')
            ->middleware('api')
            ->group(module_path('User', '/Routes/api_v1.php'));
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(module_path('User', '/Routes/web.php'));
    }
}
