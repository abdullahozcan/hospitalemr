<?php

use App\Http\Controllers\ApiClientTokenController;
use App\Http\Controllers\AppointmentBookingController;
use App\Http\Controllers\DoctorSearchController;
use App\Http\Controllers\PrescriptionPrintController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

Route::get('/', [DoctorSearchController::class, 'home'])->name('home');

Route::get('/doktorlar', [DoctorSearchController::class, 'index'])->name('doctors.index');

Route::get('/doktorlar/{doctor}/randevu', [AppointmentBookingController::class, 'show'])
    ->name('doctors.book.show');

Route::middleware(['auth'])
    ->post('/doktorlar/{doctor}/randevu', [AppointmentBookingController::class, 'store'])
    ->name('doctors.book.store');

require __DIR__.'/portal.php';

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::view('dashboard', 'dashboard')->name('dashboard');
    });

Route::middleware(['auth'])
    ->get('/prescriptions/{prescription}/print', PrescriptionPrintController::class)
    ->name('prescriptions.print');

Route::middleware(['auth'])
    ->get('/admin/api-clients/token', [ApiClientTokenController::class, 'show'])
    ->name('api-clients.token');

require __DIR__.'/settings.php';
