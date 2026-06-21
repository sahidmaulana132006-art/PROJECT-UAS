<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/events', [EventController::class, 'index'])->name('events.index');

    Route::get('/registrations', [RegistrationController::class, 'index'])->name('registrations.index');

    Route::middleware('role:peserta')->group(function () {
        Route::post('/registrations', [RegistrationController::class, 'store'])->name('registrations.store');
        Route::get('/payments/upload/{registration}', [PaymentController::class, 'showUploadForm'])->name('payments.upload_form');
        Route::post('/payments/upload', [PaymentController::class, 'store'])->name('payments.store');
    });

    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/download/{certificate}', [CertificateController::class, 'download'])->name('certificates.download');

    Route::middleware('role:admin,panitia')->group(function () {

        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->whereNumber('event')->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->whereNumber('event')->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->whereNumber('event')->name('events.destroy');

        Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::post('/attendances', [AttendanceController::class, 'record'])->name('attendances.record');

        Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
        Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');
        Route::delete('/certificates/{certificate}', [CertificateController::class, 'destroy'])->name('certificates.destroy');
    });

    Route::middleware('role:admin')->group(function () {

        Route::resource('categories', EventCategoryController::class);

        Route::patch('/registrations/{registration}/status', [RegistrationController::class, 'updateStatus'])->name('registrations.update_status');
        Route::delete('/registrations/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');

        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
        Route::patch('/payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
        Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

        Route::resource('users', UserController::class);

        Route::get('/reports/registrations/pdf', [RegistrationController::class, 'exportPdf'])->name('reports.registrations.pdf');
        Route::get('/reports/registrations/excel', [RegistrationController::class, 'exportExcel'])->name('reports.registrations.excel');
    });
});

Route::get('/events/{event}', [EventController::class, 'show'])->whereNumber('event')->name('events.show');

require __DIR__.'/auth.php';