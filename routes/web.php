<?php

use App\Http\Controllers\DentistController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    $dentists = \App\Models\Dentist::all();
    $services = \App\Models\Services::all();
    return view('welcome', compact('dentists', 'services'));
})->name('home');

// Public appointment booking
Route::post('/book-appointment', [App\Http\Controllers\PublicAppointmentController::class, 'store'])
    ->name('public.appointments.store');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// Dentist CRUD Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('dentists', DentistController::class);
});

// Service CRUD Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('services', App\Http\Controllers\ServiceController::class);
});

// Appointment CRUD Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('appointments', App\Http\Controllers\AppointmentController::class);
});

// Calendar Route
Route::middleware(['auth'])->group(function () {
    Route::get('calendar', App\Livewire\CalendarView::class)->name('calendar.index');
});
