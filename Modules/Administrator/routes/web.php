<?php

use Illuminate\Support\Facades\Route;
use Modules\Administrator\Http\Controllers\AdministratorController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('administrators', AdministratorController::class)->names('administrator');
});
