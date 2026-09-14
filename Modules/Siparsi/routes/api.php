<?php

use Illuminate\Support\Facades\Route;
use Modules\Siparsi\Http\Controllers\SiparsiController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('siparsis', SiparsiController::class)->names('siparsi');
});
