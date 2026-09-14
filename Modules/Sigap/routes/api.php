<?php

use Illuminate\Support\Facades\Route;
use Modules\Sigap\Http\Controllers\SigapController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('sigaps', SigapController::class)->names('sigap');
});
