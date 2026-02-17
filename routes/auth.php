<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/property-manager')->middleware(['property-manager'])->group(function () {
    Route::get('/roles', function () {
        return view('property-manager.roles.index');
    })->name('property-manager.roles.index');

    Route::get('/users', function () {
        return view('property-manager.users.index');
    })->name('property-manager.users.index');

    Route::get('/room-categories', function () {
        return view('property-manager.room-categories.index');
    })->name('property-manager.room-categories.index');

    Route::get('/attributes', function () {
        return view('property-manager.attributes.index');
    })->name('property-manager.attributes.index');

    Route::get('/statuses', function () {
        return view('property-manager.statuses.index');
    })->name('property-manager.statuses.index');

    Route::get('/rooms', function () {
        return view('property-manager.rooms.index');
    })->name('property-manager.rooms.index');
});
