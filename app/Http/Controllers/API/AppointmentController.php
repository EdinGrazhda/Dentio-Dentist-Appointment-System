<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\AppointmentAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
            ->get();
        
        return response()->json($appointments);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dentist_id' => 'required|exists:dentists,id',
            'service_id' => 'required|exists:services,id',
            'patient_name' => 'required|string|max:255',
            'appointment_date' => 'required|date|after:now',
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        try {
            $this->availabilityService->ensureSlotIsAvailable(
                $data['dentist_id'],
                $data['service_id'],
                $data['appointment_date'],
            );
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }

        $appointment = Appointment::create($data);

        return response()->json($appointment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['dentist', 'service']);
        return response()->json($appointment);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validator = Validator::make($request->all(), [
            'dentist_id' => 'required|exists:dentists,id',
            'service_id' => 'required|exists:services,id',
            'patient_name' => 'required|string|max:255',
            'appointment_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,canceled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $this->availabilityService->ensureSlotIsAvailable(
                $validator->validated()['dentist_id'],
                $validator->validated()['service_id'],
                $validator->validated()['appointment_date'],
                $appointment->id,
            );
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }

        $data = $validator->validated();

        try {
            $this->availabilityService->ensureSlotIsAvailable(
                $data['dentist_id'],
                $data['service_id'],
                $data['appointment_date'],
                $appointment->id,
            );
        } catch (ValidationException $exception) {
            return response()->json(['errors' => $exception->errors()], 422);
        }

        $appointment->update($data);

        return response()->json($appointment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted successfully']);
    }
}
