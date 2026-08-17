<?php

use App\Http\Controllers\Frontend\RegistrationPageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegistrationPageController::class, 'index'])->name('register.choose');

    Route::prefix('frontend')->name('frontend.')->group(function () {
        Route::redirect('/login', '/login')->name('login');

        Route::prefix('elderly')->name('elderly.')->group(function () {
            Route::get('/register', [RegistrationPageController::class, 'elderly'])->name('register');
            Route::get('/housing', [RegistrationPageController::class, 'redirectToElderly'])->name('housing');
            Route::get('/review', [RegistrationPageController::class, 'redirectToElderly'])->name('review');
        });

        Route::prefix('volunteer')->name('volunteer.')->group(function () {
            Route::get('/register', [RegistrationPageController::class, 'volunteer'])->name('register');
            Route::get('/documents', [RegistrationPageController::class, 'redirectToVolunteer'])->name('documents');
            Route::get('/review', [RegistrationPageController::class, 'redirectToVolunteer'])->name('review');
        });
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
