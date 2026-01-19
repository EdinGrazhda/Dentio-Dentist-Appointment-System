<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Services\AppointmentAvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicAppointmentController extends Controller
{
    public function __construct(private AppointmentAvailabilityService $availabilityService)
    {
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'dentist_id' => 'required|exists:dentists,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $this->availabilityService->ensureSlotIsAvailable(
                $request->dentist_id,
                $request->service_id,
                $request->appointment_date
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        }

        $appointment = Appointment::create([
            'dentist_id' => $request->dentist_id,
            'service_id' => $request->service_id,
            'patient_name' => $request->patient_name,
            'appointment_date' => $request->appointment_date,
            'status' => AppointmentStatus::PENDING,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment booked successfully! We will contact you soon.',
            'appointment' => $appointment
        ], 201);
    }
}
