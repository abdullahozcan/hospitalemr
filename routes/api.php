<?php

use App\Fhir\ApiClientMiddleware;
use App\Http\Controllers\Fhir\CapabilityController;
use App\Http\Controllers\Fhir\EncounterController;
use App\Http\Controllers\Fhir\ImmunizationController;
use App\Http\Controllers\Fhir\MedicationRequestController;
use App\Http\Controllers\Fhir\ObservationController;
use App\Http\Controllers\Fhir\PatientController;
use Illuminate\Support\Facades\Route;

Route::prefix('fhir')->middleware(['api', ApiClientMiddleware::class, 'throttle:fhir'])->group(function () {
    Route::get('metadata', CapabilityController::class)->withoutMiddleware(ApiClientMiddleware::class);

    Route::get('Patient/{patient}', [PatientController::class, 'show']);
    Route::get('Patient', [PatientController::class, 'index']);

    Route::get('Encounter/{encounter}', [EncounterController::class, 'show']);
    Route::get('Encounter', [EncounterController::class, 'index']);

    Route::get('Observation', [ObservationController::class, 'index']);

    Route::get('Immunization', [ImmunizationController::class, 'index']);

    Route::get('MedicationRequest', [MedicationRequestController::class, 'index']);
});
