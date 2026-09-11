<?php

use App\Http\Controllers\Frontend\RegistrationPageController;
use App\Http\Controllers\NotificationController;
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
        });

        Route::prefix('volunteer')->name('volunteer.')->group(function () {
            Route::get('/register', [RegistrationPageController::class, 'volunteer'])->name('register');
        });
    });
});

// مسارات كبير السن (المستفيد)
Route::middleware(['auth', 'verified', 'role:elder'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/requests', [ServiceRequestController::class, 'index'])->name('service-requests.index');
    Route::post('/requests', [ServiceRequestController::class, 'store'])->name('service-requests.store');
    Route::patch('/requests/{serviceRequest}/reschedule', [ServiceRequestController::class, 'reschedule'])->name('service-requests.reschedule');
    Route::patch('/requests/{serviceRequest}', [ServiceRequestController::class, 'update'])->name('service-requests.update');
    Route::patch('/requests/{serviceRequest}/confirm', [ServiceRequestController::class, 'confirmCompletion'])->name('service-requests.confirm');
    Route::patch('/requests/{serviceRequest}/search-alternative', [ServiceRequestController::class, 'searchAlternative'])->name('service-requests.search-alternative');
    Route::delete('/requests/{serviceRequest}/cancel', [ServiceRequestController::class, 'cancel'])->name('service-requests.cancel');
    Route::post('/requests/{serviceRequest}/report-problem', [ServiceRequestController::class, 'reportProblem'])->name('service-requests.report-problem');
});

// مسارات مقدم الخدمة الكاملة (المتطوع)
Route::prefix('provider')->name('provider.')->middleware(['auth', 'verified', 'role:provider'])->group(function () {
    // الشاشات الرئيسية
    Route::get('/dashboard', [VolunteerTaskController::class, 'dashboard'])->name('dashboard');
    Route::get('/available', [VolunteerTaskController::class, 'available'])->name('available');
    Route::get('/tasks', [VolunteerTaskController::class, 'myTasks'])->name('tasks');
    Route::get('/performance', [VolunteerTaskController::class, 'performance'])->name('performance');
    Route::get('/certificates', [VolunteerTaskController::class, 'certificates'])->name('certificates');
    Route::post('/certificates/request', [VolunteerTaskController::class, 'requestCertificate'])->name('certificates.request');
    Route::get('/availability', [VolunteerTaskController::class, 'availability'])->name('availability');
    Route::post('/availability', [VolunteerTaskController::class, 'updateAvailability'])->name('availability.update');

    // إجراءات مسار العمليات (Workflow Actions)
    Route::post('/tasks/{serviceRequest}/accept', [VolunteerTaskController::class, 'accept'])->name('tasks.accept');
    Route::post('/tasks/{serviceRequest}/dismiss', [VolunteerTaskController::class, 'dismiss'])->name('tasks.dismiss');
    Route::post('/tasks/{serviceRequest}/start-service', [VolunteerTaskController::class, 'startService'])->name('tasks.start-service');
    Route::post('/tasks/{serviceRequest}/finish-service', [VolunteerTaskController::class, 'finishService'])->name('tasks.finish-service');
    Route::post('/tasks/{serviceRequest}/report-delay', [VolunteerTaskController::class, 'reportDelay'])->name('tasks.report-delay');
    Route::post('/tasks/{serviceRequest}/apologize', [VolunteerTaskController::class, 'apologize'])->name('tasks.apologize');
    Route::post('/tasks/{serviceRequest}/rate-elder', [VolunteerTaskController::class, 'rateElder'])->name('tasks.rate-elder');
});

// توافقية مسارات volunteer.tasks القديمة
Route::middleware(['auth', 'verified', 'role:provider'])->group(function () {
    Route::get('/volunteer/tasks', [VolunteerTaskController::class, 'myTasks'])->name('volunteer.tasks.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // مسارات الإشعارات المشتركة لكافة المستخدمين
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});

require __DIR__ . '/auth.php';

// ============================================================
// مسارات لوحة الإدارة — الدفعة 0 والدفعة 1
// ============================================================
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'ensure.admin'])
    ->group(function () {
        // 1.1 لوحة التحكم الرئيسية
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');

        // استعراض المستندات المحمية
        Route::get('/documents/{path}', [\App\Http\Controllers\Admin\AdminApprovalsController::class, 'viewDocument'])
            ->where('path', '.*')
            ->name('documents.view');

        // 1.2 مراجعة اعتماد الحسابات
        Route::prefix('approvals')->name('approvals.')->group(function () {
            Route::get('/',                            [\App\Http\Controllers\Admin\AdminApprovalsController::class, 'index'])->name('index');
            Route::get('/{user}',                      [\App\Http\Controllers\Admin\AdminApprovalsController::class, 'show'])->name('show');
            Route::post('/{user}/approve',             [\App\Http\Controllers\Admin\AdminApprovalsController::class, 'approve'])->name('approve');
            Route::post('/{user}/reject',              [\App\Http\Controllers\Admin\AdminApprovalsController::class, 'reject'])->name('reject');
            Route::post('/{user}/request-resubmission', [\App\Http\Controllers\Admin\AdminApprovalsController::class, 'requestResubmission'])->name('request-resubmission');
        });

        // 2.1 إدارة كل الطلبات
        Route::prefix('requests')->name('requests.')->group(function () {
            Route::get('/',                               [\App\Http\Controllers\Admin\AdminRequestsController::class, 'index'])->name('index');
            Route::get('/{serviceRequest}',               [\App\Http\Controllers\Admin\AdminRequestsController::class, 'show'])->name('show');
            Route::post('/{serviceRequest}/force-status', [\App\Http\Controllers\Admin\AdminRequestsController::class, 'forceStatus'])->name('force-status');
        });

        // 2.2 و 2.3 إدارة الشكاوى وتنبيهات الموثوقية
        Route::prefix('complaints')->name('complaints.')->group(function () {
            Route::get('/',                     [\App\Http\Controllers\Admin\AdminComplaintsController::class, 'index'])->name('index');
            Route::get('/{complaint}',          [\App\Http\Controllers\Admin\AdminComplaintsController::class, 'show'])->name('show');
            Route::post('/{complaint}/resolve', [\App\Http\Controllers\Admin\AdminComplaintsController::class, 'resolve'])->name('resolve');
        });

        // 3.1 & 3.2 إدارة حسابات المستخدمين
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/',                   [\App\Http\Controllers\Admin\AdminUsersController::class, 'index'])->name('index');
            Route::get('/{user}',             [\App\Http\Controllers\Admin\AdminUsersController::class, 'show'])->name('show');
            Route::post('/{user}/suspend',    [\App\Http\Controllers\Admin\AdminUsersController::class, 'suspend'])->name('suspend');
            Route::post('/{user}/reactivate', [\App\Http\Controllers\Admin\AdminUsersController::class, 'reactivate'])->name('reactivate');
        });

        // 4.1 إدارة المديرين ومسؤولي النظام (Super Admin فقط)
        Route::prefix('admins')->name('admins.')->middleware('ensure.super')->group(function () {
            Route::get('/',            [\App\Http\Controllers\Admin\AdminAdminsController::class, 'index'])->name('index');
            Route::get('/create',      [\App\Http\Controllers\Admin\AdminAdminsController::class, 'create'])->name('create');
            Route::post('/',           [\App\Http\Controllers\Admin\AdminAdminsController::class, 'store'])->name('store');
            Route::delete('/{admin}',  [\App\Http\Controllers\Admin\AdminAdminsController::class, 'destroy'])->name('destroy');
        });

        // 4.2 إعدادات النظام العامة وعتبات الترقية (Super Admin فقط)
        Route::prefix('settings')->name('settings.')->middleware('ensure.super')->group(function () {
            Route::get('/',  [\App\Http\Controllers\Admin\AdminSettingsController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\AdminSettingsController::class, 'update'])->name('update');
        });

        // 5.1 سجل النظام الإداري (Audit Log)
        Route::get('/audit-log', [\App\Http\Controllers\Admin\AdminAuditLogsController::class, 'index'])->name('audit-log.index');
    });


