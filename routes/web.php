<?php

use App\Http\Controllers\Frontend\RegistrationPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\VolunteerTaskController;
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
    if (auth()->user()->account_type === 'volunteer') {
        return redirect()->route('provider.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// مسارات كبير السن (المستفيد)
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

// مسارات مقدم الخدمة الكاملة (المتطوع)
Route::prefix('provider')->name('provider.')->middleware(['auth', 'verified'])->group(function () {
    // الشاشات الرئيسية
    Route::get('/dashboard', [VolunteerTaskController::class, 'dashboard'])->name('dashboard');
    Route::get('/available', [VolunteerTaskController::class, 'available'])->name('available');
    Route::get('/tasks', [VolunteerTaskController::class, 'myTasks'])->name('tasks');
    Route::get('/performance', [VolunteerTaskController::class, 'performance'])->name('performance');
    Route::get('/availability', [VolunteerTaskController::class, 'availability'])->name('availability');
    Route::post('/availability', [VolunteerTaskController::class, 'updateAvailability'])->name('availability.update');

    // إجراءات مسار العمليات (Workflow Actions)
    Route::post('/tasks/{serviceRequest}/accept', [VolunteerTaskController::class, 'accept'])->name('tasks.accept');
    Route::post('/tasks/{serviceRequest}/dismiss', [VolunteerTaskController::class, 'dismiss'])->name('tasks.dismiss');
    Route::post('/tasks/{serviceRequest}/start-heading', [VolunteerTaskController::class, 'startHeading'])->name('tasks.start-heading');
    Route::post('/tasks/{serviceRequest}/confirm-arrival', [VolunteerTaskController::class, 'confirmArrival'])->name('tasks.confirm-arrival');
    Route::post('/tasks/{serviceRequest}/start-service', [VolunteerTaskController::class, 'startService'])->name('tasks.start-service');
    Route::post('/tasks/{serviceRequest}/finish-service', [VolunteerTaskController::class, 'finishService'])->name('tasks.finish-service');
    Route::post('/tasks/{serviceRequest}/report-delay', [VolunteerTaskController::class, 'reportDelay'])->name('tasks.report-delay');
    Route::post('/tasks/{serviceRequest}/apologize', [VolunteerTaskController::class, 'apologize'])->name('tasks.apologize');
});

// توافقية مسارات volunteer.tasks القديمة
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/volunteer/tasks', [VolunteerTaskController::class, 'myTasks'])->name('volunteer.tasks.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
