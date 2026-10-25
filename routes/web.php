<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Marketplace\CourseCatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CourseCatalogController::class, 'index'])->name('home');
Route::get('/courses', [CourseCatalogController::class, 'index'])->name('courses.index');
Route::get('/courses/{slug}', [CourseCatalogController::class, 'show'])->name('courses.show');

Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        if (auth()->user()?->hasRole('admin')) {
            return redirect('/admin');
        }
        return \Inertia\Inertia::render('Dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
