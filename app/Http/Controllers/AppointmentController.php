<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Services;
use App\Enums\AppointmentStatus;
use App\Services\AppointmentAvailabilityService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function __construct(private AppointmentAvailabilityService $availabilityService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with(['dentist', 'service'])
            ->latest()
            ->paginate(10);
        
        return view('Admin.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dentists = Dentist::all();
        $services = Services::all();
        $statuses = AppointmentStatus::options();
        
        return view('Admin.appointments.create', compact('dentists', 'services', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dentist_id' => 'required|exists:dentists,id',
            'service_id' => 'required|exists:services,id',
            'patient_name' => 'required|string|max:255',
            'appointment_date' => 'required|date|after:now',
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        $this->availabilityService->ensureSlotIsAvailable(
            $validated['dentist_id'],
            $validated['service_id'],
            $validated['appointment_date'],
        );

        Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        $dentists = Dentist::all();
        $services = Services::all();
        $statuses = AppointmentStatus::options();
        
        return view('Admin.appointments.edit', compact('appointment', 'dentists', 'services', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'dentist_id' => 'required|exists:dentists,id',
            'service_id' => 'required|exists:services,id',
            'patient_name' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        $this->availabilityService->ensureSlotIsAvailable(
            $validated['dentist_id'],
            $validated['service_id'],
            $validated['appointment_date'],
            $appointment->id,
        );

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }
}
