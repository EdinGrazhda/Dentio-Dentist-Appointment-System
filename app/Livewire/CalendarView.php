<?php

namespace App\Livewire;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Services;
use Livewire\Attributes\On;
use Livewire\Component;

class CalendarView extends Component
{
    public ?Appointment $selectedAppointment = null;
    
    public $form = [
        'patient_name' => '',
        'dentist_id' => '',
        'service_id' => '',
        'appointment_date' => '',
        'status' => '',
    ];

    #[On('open-edit-modal')]
    public function openEditModal($appointmentId)
    {
        $this->selectedAppointment = Appointment::with(['dentist', 'service'])->findOrFail($appointmentId);
        
        $this->form = [
            'patient_name' => $this->selectedAppointment->patient_name,
            'dentist_id' => $this->selectedAppointment->dentist_id,
            'service_id' => $this->selectedAppointment->service_id,
            'appointment_date' => $this->selectedAppointment->appointment_date->format('Y-m-d\TH:i'),
            'status' => $this->selectedAppointment->status->value,
        ];
        
        $this->js('$flux.modal("edit-appointment").show()');
    }

    public function updateAppointment()
    {
        $this->validate([
            'form.patient_name' => 'required|string|max:255',
            'form.dentist_id' => 'required|exists:dentists,id',
            'form.service_id' => 'required|exists:services,id',
            'form.appointment_date' => 'required|date',
            'form.status' => 'required|in:' . implode(',', array_column(AppointmentStatus::cases(), 'value')),
        ]);

        $this->selectedAppointment->update([
            'patient_name' => $this->form['patient_name'],
            'dentist_id' => $this->form['dentist_id'],
            'service_id' => $this->form['service_id'],
            'appointment_date' => $this->form['appointment_date'],
            'status' => AppointmentStatus::from($this->form['status']),
        ]);

        $this->js('$flux.modal("edit-appointment").close()');
        session()->flash('success', 'Appointment updated successfully!');
        $this->selectedAppointment = null;
    }

    public function deleteAppointment()
    {
        if ($this->selectedAppointment) {
            $this->selectedAppointment->delete();
            $this->js('$flux.modal("edit-appointment").close()');
            session()->flash('success', 'Appointment deleted successfully!');
            $this->selectedAppointment = null;
        }
    }

    public function render()
    {
        return view('livewire.calendar-view', [
            'dentists' => Dentist::all(),
            'services' => Services::all(),
            'statuses' => collect(AppointmentStatus::cases())->mapWithKeys(fn($status) => [
                $status->value => ucfirst(str_replace('_', ' ', $status->value))
            ]),
        ])->layout('components.layouts.app', ['title' => __('Calendar')]);
    }
}
