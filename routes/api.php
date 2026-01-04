<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DentistController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Dentist API Routes
Route::apiResource('dentists', DentistController::class)->names([
    'index' => 'api.dentists.index',
    'store' => 'api.dentists.store',
    'show' => 'api.dentists.show',
    'update' => 'api.dentists.update',
    'destroy' => 'api.dentists.destroy',
]);

// Service API Routes
Route::apiResource('services', App\Http\Controllers\API\ServiceController::class)->names([
    'index' => 'api.services.index',
    'store' => 'api.services.store',
    'show' => 'api.services.show',
    'update' => 'api.services.update',
    'destroy' => 'api.services.destroy',
]);

// Appointment API Routes
Route::apiResource('appointments', App\Http\Controllers\API\AppointmentController::class)->names([
    'index' => 'api.appointments.index',
    'store' => 'api.appointments.store',
    'show' => 'api.appointments.show',
    'update' => 'api.appointments.update',
    'destroy' => 'api.appointments.destroy',
]);
