<?php

use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'dashboard')->name('dashboard');
});

Route::withoutMiddleware('web')->post('/exercise-1-artwork-version', [IndexController::class, 'artworkVersion']); // // exercise = 1
Route::withoutMiddleware('web')->post('/exercise-2-tier-pricing', [IndexController::class, 'tierPricing']); // exercise = 2


require __DIR__.'/settings.php';
