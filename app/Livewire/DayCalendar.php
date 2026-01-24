<?php

namespace App\Livewire;

use App\Models\Appointment;
use App\Models\Dentist;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DayCalendar extends Component
{
    public $currentDate;
    public $viewMode = 'day'; // 'day' or 'week'
    public $selectedDentist = null;
    
    public $workStartTime = '08:00';
    public $workEndTime = '16:00';
    public $slotDuration = 30;
    
    public $timeSlots = [];
    public $appointments = [];
    
    // Livewire event listeners
    protected $listeners = [
        'openAppointment' => 'openAppointmentModal',
    ];

    public $showAppointmentModal = false;
    public ?Appointment $detailAppointment = null;

    public function mount()
    {
        $this->currentDate = now()->format('Y-m-d');
        $this->loadDentistSettings();
        $this->generateTimeSlots();
        $this->loadAppointments();
    }

    public function loadDentistSettings()
    {
        // Get logged-in dentist's settings or use defaults
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
            // Week view
            $startOfWeek = Carbon::parse($this->currentDate)->startOfWeek();
            $endOfWeek = Carbon::parse($this->currentDate)->endOfWeek();
            $query->whereBetween('appointment_date', [$startOfWeek, $endOfWeek]);
        }
        
        if ($this->selectedDentist) {
            $query->where('dentist_id', $this->selectedDentist);
        }
        
        $this->appointments = $query->get()->groupBy(function($appointment) {
            return $appointment->appointment_date->format('Y-m-d H:i');
        })->toArray();
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
            }
        }
    }

    public function openAppointmentModal($appointmentId)
    {
        Log::info('DayCalendar openAppointmentModal called', ['id' => $appointmentId]);
        $appointment = Appointment::with(['dentist', 'service'])->find($appointmentId);

        if (! $appointment) {
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

    public function render()
    {
        return view('livewire.day-calendar', [
            'dentists' => Dentist::all(),
            'currentDateDisplay' => Carbon::parse($this->currentDate)->format('F j, Y'),
        ]);
    }
}
