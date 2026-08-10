<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('frontend')->name('frontend.')->group(function () {
    Route::view('/login', 'frontend.login')->name('login');

    Route::prefix('elderly')->name('elderly.')->group(function () {
        Route::view('/register', 'frontend.elderly-register')->name('register');
        Route::view('/housing', 'frontend.elderly-housing')->name('housing');
        Route::view('/review', 'frontend.elderly-review')->name('review');
    });

    Route::prefix('volunteer')->name('volunteer.')->group(function () {
        Route::view('/register', 'frontend.volunteer-register')->name('register');
        Route::view('/documents', 'frontend.volunteer-documents')->name('documents');
        Route::view('/review', 'frontend.volunteer-review')->name('review');
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
