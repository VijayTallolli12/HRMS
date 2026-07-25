<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['identify.tenant'])->group(function () {
    Route::view('/', 'welcome');

    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->middleware(['auth'])
        ->name('profile');
});
 

require __DIR__.'/auth.php';
 
Route::resource('organizations', App\Http\Controllers\OrganizationController::class)->middleware('auth');
