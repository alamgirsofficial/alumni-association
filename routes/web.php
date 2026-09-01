<?php

use App\Http\Controllers\AlumniController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/alumni', [AlumniController::class, 'index'])
        ->name('alumni.index');

    Route::get('/alumni/create', [AlumniController::class, 'create'])
        ->name('alumni.create');
     Route::get('/alumni/pending', [AlumniController::class, 'pending'])
        ->name('alumni.pending');

});

require __DIR__.'/settings.php';
