<?php

use Illuminate\Support\Facades\Route;
use Modules\Club\Http\Controllers\V1\ClubController;

/*
|--------------------------------------------------------------------------
| Club API Routes — v1
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('clubs', ClubController::class);
});
