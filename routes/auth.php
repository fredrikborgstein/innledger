<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/property-manager')->middleware(['property-manager'])->group(function () {
    Route::get('/roles', function () {
        return view('property-manager.roles.index');
    })->name('property-manager.roles.index');

    Route::get('/users', function () {
        return view('property-manager.users.index');
    })->name('property-manager.users.index');
});
