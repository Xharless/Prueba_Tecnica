<?php

use App\Http\Controllers\LinkController; 
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SupportController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $supports = $user->supports()->latest()->get();
    return Inertia::render('Dashboard', [
        'supports' => $supports
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rutas de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/links', [LinkController::class, 'index'])->name('links.index');
    Route::post('/links', [LinkController::class, 'store'])->name('links.store');
});

Route::get('/@{username}', [PublicProfileController::class, 'show'])->name('public.profile');

Route::post('/supports/{user}', [SupportController::class, 'store'])->name('supports.store');
require __DIR__.'/auth.php';