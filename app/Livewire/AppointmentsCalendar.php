<?php

namespace App\Livewire;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Services;
use Asantibanez\LivewireCalendar\LivewireCalendar;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AppointmentsCalendar extends LivewireCalendar
{
    public function events(): Collection
    {
        return Appointment::query()
            ->with(['dentist', 'service'])
            ->whereBetween('appointment_date', [
                $this->gridStartsAt,
                $this->gridEndsAt,
            ])
            ->get()
            ->map(function (Appointment $appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->patient_name,
                    'description' => $appointment->service->service_name.' - Dr. '.$appointment->dentist->name,
                    'date' => $appointment->appointment_date,
                ];
            });
    }

    public function onEventClick($eventId)
    {
        $this->dispatch('open-edit-modal', appointmentId: $eventId)->to(CalendarView::class);
    }

    public function onDayClick($year, $month, $day)
    {
        $date = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
        $this->redirect(route('appointments.create', ['date' => $date]));
    }
}
