<?php

use Illuminate\Support\Facades\Route;
use Modules\Sigap\Http\Controllers\SigapController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('sigaps', SigapController::class)->names('sigap');
});
