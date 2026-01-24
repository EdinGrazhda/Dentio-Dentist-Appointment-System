<?php

namespace App\Livewire;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Services;
use App\Services\AppointmentAvailabilityService;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CalendarView extends Component
{
    protected AppointmentAvailabilityService $availabilityService;

    public function boot(AppointmentAvailabilityService $availabilityService): void
    {
        $this->availabilityService = $availabilityService;
    }

    // Calendar Properties
    public $currentDate;
    public $viewMode = 'day';
    public $selectedDentist = null;
    
    public $workStartTime = '08:00';
    public $workEndTime = '16:00';
    public $slotDuration = 30;
    
    public $timeSlots = [];
    public $appointments = [];

    // Edit Modal Properties
    public ?Appointment $selectedAppointment = null;
    public ?Appointment $detailAppointment = null;
    public bool $showAppointmentModal = false;
    
    public $form = [
        'patient_name' => '',
        'dentist_id' => '',
        'service_id' => '',
        'appointment_date' => '',
        'status' => '',
    ];

    public function mount()
    {
        $this->currentDate = now()->format('Y-m-d');
        $this->loadDentistSettings();
        $this->generateTimeSlots();
        $this->loadAppointments();
    }

    public function loadDentistSettings()
    {
        $dentist = Dentist::where('id', Auth::id())->first() ?? Dentist::first();
        
        if ($dentist) {
            $this->workStartTime = $dentist->work_start_time 
                ? Carbon::parse($dentist->work_start_time)->format('H:i') 
                : '08:00';
            $this->workEndTime = $dentist->work_end_time 
                ? Carbon::parse($dentist->work_end_time)->format('H:i') 
                : '16:00';
            $this->slotDuration = $dentist->slot_duration ?? 30;
            $this->selectedDentist = $dentist->id;
        }
    }

    public function generateTimeSlots()
    {
        $this->timeSlots = [];
        $startTime = Carbon::parse($this->workStartTime);
        $endTime = Carbon::parse($this->workEndTime);
        
        while ($startTime < $endTime) {
            $this->timeSlots[] = [
                'time' => $startTime->format('H:i'),
                'display' => $startTime->format('g:i A'),
            ];
            $startTime->addMinutes($this->slotDuration);
        }
    }

    public function loadAppointments()
    {
        $query = Appointment::with(['dentist', 'service']);
        
        if ($this->viewMode === 'day') {
            $query->whereDate('appointment_date', $this->currentDate);
        } else {
            $startOfWeek = Carbon::parse($this->currentDate)->startOfWeek();
            $endOfWeek = Carbon::parse($this->currentDate)->endOfWeek();
            $query->whereBetween('appointment_date', [$startOfWeek, $endOfWeek]);
        }
        
        if ($this->selectedDentist) {
            $query->where('dentist_id', $this->selectedDentist);
        }
        
        // Group appointments by start time
        $allAppointments = $query->get();
        $this->appointments = [];
        
        foreach ($allAppointments as $appointment) {
            $startKey = $appointment->appointment_date->format('Y-m-d H:i');
            if (!isset($this->appointments[$startKey])) {
                $this->appointments[$startKey] = [];
            }
            
            // Calculate appointment duration and end time
            $duration = $appointment->service->duration ?? 30;
            $endTime = $appointment->appointment_date->copy()->addMinutes($duration);
            
            $this->appointments[$startKey][] = [
                'id' => $appointment->id,
                'patient_name' => $appointment->patient_name,
                'email' => $appointment->email,
                'phone' => $appointment->phone,
                'service' => [
                    'service_name' => $appointment->service->service_name,
                    'duration' => $duration,
                ],
                'dentist' => [
                    'name' => $appointment->dentist->name,
                ],
                'start_time' => $appointment->appointment_date->format('H:i'),
                'end_time' => $endTime->format('H:i'),
                'duration_minutes' => $duration,
                'status' => $appointment->status->value,
            ];
        }
    }

    public function goToPreviousDay()
    {
        $this->currentDate = Carbon::parse($this->currentDate)->subDay()->format('Y-m-d');
        $this->loadAppointments();
    }

    public function goToNextDay()
    {
        $this->currentDate = Carbon::parse($this->currentDate)->addDay()->format('Y-m-d');
        $this->loadAppointments();
    }

    public function goToToday()
    {
        $this->currentDate = now()->format('Y-m-d');
        $this->loadAppointments();
    }

    public function switchView($mode)
    {
        $this->viewMode = $mode;
        $this->loadAppointments();
    }

    public function updatedWorkStartTime()
    {
        $this->generateTimeSlots();
    }

    public function updatedWorkEndTime()
    {
        $this->generateTimeSlots();
    }

    public function updatedSlotDuration()
    {
        $this->generateTimeSlots();
    }

    public function saveSettings()
    {
        if ($this->selectedDentist) {
            $dentist = Dentist::find($this->selectedDentist);
            if ($dentist) {
                $dentist->update([
                    'work_start_time' => $this->workStartTime,
                    'work_end_time' => $this->workEndTime,
                    'slot_duration' => $this->slotDuration,
                ]);
                
                session()->flash('message', 'Calendar settings saved successfully!');
                $this->dispatch('settings-saved');
                $this->loadAppointments();
            }
        }
    }

    public function openAppointmentModal($appointmentId)
    {
        Log::info('CalendarView openAppointmentModal', ['id' => $appointmentId]);
        $appointment = Appointment::with(['dentist', 'service'])->find($appointmentId);

        if (!$appointment) {
            return;
        }

        $this->detailAppointment = $appointment;
        $this->showAppointmentModal = true;
    }

    public function closeAppointmentModal()
    {
        $this->detailAppointment = null;
        $this->showAppointmentModal = false;
    }

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

        $this->availabilityService->ensureSlotIsAvailable(
            $this->form['dentist_id'],
            $this->form['service_id'],
            $this->form['appointment_date'],
            $this->selectedAppointment->id,
            'form.appointment_date'
        );

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
        $this->loadAppointments();
    }

    public function deleteAppointment()
    {
        if ($this->selectedAppointment) {
            $this->selectedAppointment->delete();
            $this->js('$flux.modal("edit-appointment").close()');
            session()->flash('success', 'Appointment deleted successfully!');
            $this->selectedAppointment = null;
            $this->loadAppointments();
        }
    }

    public function render()
    {
        return view('livewire.day-calendar', [
            'dentists' => Dentist::all(),
            'services' => Services::all(),
            'statuses' => collect(AppointmentStatus::cases())->mapWithKeys(fn($status) => [
                $status->value => ucfirst(str_replace('_', ' ', $status->value))
            ]),
            'currentDateDisplay' => Carbon::parse($this->currentDate)->format('F j, Y'),
        ])->layout('components.layouts.app', ['title' => __('Calendar')]);
    }
}
