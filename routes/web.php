<?php

use App\Http\Controllers\Frontend\RegistrationPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceRequestController;
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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/requests', [ServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::post('/requests', [ServiceRequestController::class, 'store'])->name('service-requests.store');
    Route::patch('/requests/{serviceRequest}/reschedule', [ServiceRequestController::class, 'reschedule'])->name('service-requests.reschedule');
    Route::patch('/requests/{serviceRequest}', [ServiceRequestController::class, 'update'])->name('service-requests.update');
    Route::patch('/requests/{serviceRequest}/confirm', [ServiceRequestController::class, 'confirmCompletion'])->name('service-requests.confirm');
    Route::patch('/requests/{serviceRequest}/search-alternative', [ServiceRequestController::class, 'searchAlternative'])->name('service-requests.search-alternative');
    Route::delete('/requests/{serviceRequest}/cancel', [ServiceRequestController::class, 'cancel'])->name('service-requests.cancel');
    Route::post('/requests/{serviceRequest}/reviews', [ServiceRequestController::class, 'storeReview'])->name('service-requests.reviews.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
