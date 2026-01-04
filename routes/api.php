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
