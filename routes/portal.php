<?php

use App\Http\Middleware\EnsurePortalUser;
use Illuminate\Support\Facades\Route;

Route::prefix('portal')
    ->middleware(['auth', EnsurePortalUser::class])
    ->group(function () {
        Route::redirect('/', '/portal/dashboard');

        Route::livewire('dashboard', 'pages::portal.dashboard')->name('portal.dashboard');
        Route::livewire('appointments', 'pages::portal.appointments')->name('portal.appointments');
        Route::livewire('prescriptions', 'pages::portal.prescriptions')->name('portal.prescriptions');
        Route::livewire('lab-results', 'pages::portal.lab-results')->name('portal.lab-results');
        Route::livewire('invoices', 'pages::portal.invoices')->name('portal.invoices');
        Route::livewire('profile', 'pages::portal.profile')->name('portal.profile');
    });
