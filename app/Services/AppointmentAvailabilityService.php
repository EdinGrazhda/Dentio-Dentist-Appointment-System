<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Services as DentalService;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class AppointmentAvailabilityService
{
    /**
     * Ensure a dentist is free for the requested slot, otherwise throw a validation error.
     */
    public function ensureSlotIsAvailable(
        int $dentistId,
        int $serviceId,
        string|DateTimeInterface $appointmentDate,
        ?int $ignoreAppointmentId = null,
        string $errorField = 'appointment_date'
    ): void {
        $conflict = $this->findConflict($dentistId, $serviceId, $appointmentDate, $ignoreAppointmentId);

        if ($conflict) {
            $dentistName = $conflict->dentist?->name ?? 'The selected dentist';
            $serviceName = $conflict->service?->service_name ?? 'another service';
            $startTime = $conflict->appointment_date->format('M d, Y H:i');
            $endTime = $this->calculateEnd($conflict->appointment_date, $conflict->service);

            throw ValidationException::withMessages([
                $errorField => sprintf(
                    '%s already has %s between %s and %s.',
                    $dentistName,
                    $serviceName,
                    $startTime,
                    $endTime->format('H:i')
                ),
            ]);
        }
    }

    /**
     * Return the first conflicting appointment if any exist.
     */
    public function findConflict(
        int $dentistId,
        int $serviceId,
        string|DateTimeInterface $appointmentDate,
        ?int $ignoreAppointmentId = null
    ): ?Appointment {
        $start = $this->normalizeDate($appointmentDate);
        $service = DentalService::findOrFail($serviceId);
        $end = $this->calculateEnd($start, $service);

        $appointments = $this->fetchPotentialConflicts($dentistId, $start, $end, $ignoreAppointmentId);

        foreach ($appointments as $existingAppointment) {
            $existingStart = $existingAppointment->appointment_date->copy();
            $existingEnd = $this->calculateEnd($existingStart, $existingAppointment->service);

            if ($this->slotsOverlap($start, $end, $existingStart, $existingEnd)) {
                return $existingAppointment;
            }
        }

        return null;
    }

    protected function fetchPotentialConflicts(
        int $dentistId,
        Carbon $start,
        Carbon $end,
        ?int $ignoreAppointmentId
    ): Collection {
        return Appointment::query()
            ->with(['service', 'dentist'])
            ->where('dentist_id', $dentistId)
            ->whereIn('status', [
                AppointmentStatus::PENDING->value,
                AppointmentStatus::CONFIRMED->value,
            ])
            ->when($ignoreAppointmentId, fn ($query) => $query->where('id', '!=', $ignoreAppointmentId))
            ->whereBetween('appointment_date', [$start->copy()->subDay(), $end->copy()])
            ->orderBy('appointment_date')
            ->get();
    }

    protected function slotsOverlap(Carbon $startA, Carbon $endA, Carbon $startB, Carbon $endB): bool
    {
        return $startA->lt($endB) && $endA->gt($startB);
    }

    protected function normalizeDate(string|DateTimeInterface $date): Carbon
    {
        if ($date instanceof Carbon) {
            return $date->copy();
        }

        if ($date instanceof DateTimeInterface) {
            return Carbon::parse($date->format('Y-m-d H:i:s'), $date->getTimezone());
        }

        return Carbon::parse($date);
    }

    protected function calculateEnd(Carbon $start, ?DentalService $service): Carbon
    {
        $duration = max(1, (int) ($service?->duration ?? 60));

        return $start->copy()->addMinutes($duration);
    }

    /**
     * Get available time slots for a specific dentist, service, and date
     */
    public function getAvailableSlots(int $dentistId, int $serviceId, string $date): array
    {
        $slots = [];
        $targetDate = Carbon::parse($date);
        $service = DentalService::findOrFail($serviceId);
        $dentist = \App\Models\Dentist::findOrFail($dentistId);
        
        // Get dentist's working hours or use defaults
        $startTime = $dentist->work_start_time ? Carbon::parse($dentist->work_start_time) : Carbon::parse('08:00');
        $endTime = $dentist->work_end_time ? Carbon::parse($dentist->work_end_time) : Carbon::parse('16:00');
        $slotDuration = $dentist->slot_duration ?? 30;
        
        // Generate slots based on dentist's settings
        $currentTime = $targetDate->copy()->setTimeFrom($startTime);
        $dayEndTime = $targetDate->copy()->setTimeFrom($endTime);
        
        while ($currentTime->lt($dayEndTime)) {
            // Check if this slot is available
            $slotEnd = $this->calculateEnd($currentTime->copy(), $service);
            
            // Skip slots that end after working hours
            if ($slotEnd->greaterThan($dayEndTime)) {
                break;
            }
            
            // Check if there's a conflict
            if (!$this->findConflict($dentistId, $serviceId, $currentTime)) {
                $slots[] = $currentTime->format('H:i:s');
            }
            
            // Move to next slot based on dentist's slot duration
            $currentTime->addMinutes($slotDuration);
        }
        
        return $slots;
    }
}
