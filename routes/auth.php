<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/property-manager')->group(function () {
    Route::get('/roles', function () {
        return view('property-manager.roles.index');
    })->name('property-manager.roles.index');
});
