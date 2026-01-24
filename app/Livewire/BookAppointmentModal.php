<?php

namespace App\Livewire;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Services;
use App\Models\VerificationCode;
use App\Notifications\AppointmentVerificationCode;
use App\Services\AppointmentAvailabilityService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class BookAppointmentModal extends Component
{
    public $open = false;
    public $currentStep = 1;
    public $selectedDentist;
    public $selectedDentistName = '';
    
    // Step 1 - Customer Info
    public $patient_name = '';
    public $email = '';
    public $phone = '';
    public $service_id = '';
    
    // Step 2 - Date and Time
    public $appointment_date = '';
    public $availableSlots = [];
    public $selectedDate = '';
    public $selectedTime = '';
    public $selectedServiceDuration = 0;
    
    // Step 3 - Verification
    public $verificationCode = '';
    public $sentCode = '';
    
    public $services = [];
    public $formErrors = [];
    public $loading = false;
    public $debugMessage = '';

    protected $listeners = [
        'open-appointment-modal' => 'handleOpenModal',
    ];

    public function mount()
    {
        $this->services = Services::all();
    }

    public function handleOpenModal($dentistId, $dentistName)
    {
        $this->openModal($dentistId, $dentistName);
    }

    public function openModal($dentistId, $dentistName)
    {
        $this->reset(['currentStep', 'patient_name', 'email', 'phone', 'service_id', 'appointment_date', 'verificationCode', 'sentCode', 'selectedDate', 'selectedTime', 'formErrors']);
        $this->selectedDentist = $dentistId;
        $this->selectedDentistName = $dentistName;
        $this->currentStep = 1;
        $this->open = true;
    }

    public function closeModal()
    {
        $this->open = false;
        $this->reset();
    }

    public function nextStep()
    {
        $this->debugMessage = 'nextStep called (currentStep=' . $this->currentStep . ') at ' . now();
        if ($this->currentStep == 1) {
            $this->validateStep1();
        } elseif ($this->currentStep == 2) {
            $this->validateStep2();
        }
        $this->debugMessage = 'after validate (currentStep=' . $this->currentStep . ') at ' . now();
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    protected function validateStep1()
    {
        $this->validate([
            'patient_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'service_id' => 'required|exists:services,id',
        ]);

        // advance UI first so user can continue even if email sending has issues
        $this->currentStep = 2;

        // Send verification code (fire and forget)
        try {
            $this->sendVerificationCode();
        } catch (\Exception $e) {
            Log::error('Non-blocking error sending verification code: ' . $e->getMessage());
            session()->flash('message', 'Verification code could not be sent immediately. You can still continue.');
        }
    }

    protected function validateStep2()
    {
        $this->validate([
            'appointment_date' => 'required|date|after:now',
        ]);

        $this->currentStep = 3;
    }

    protected function sendVerificationCode()
    {
        try {
            // Generate 4-character code
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4));
            
            Log::info('Sending verification code', [
                'email' => $this->email,
                'code' => $code,
                'patient_name' => $this->patient_name
            ]);
            
            // Store in database
            VerificationCode::create([
                'email' => $this->email,
                'code' => $code,
                'expires_at' => now()->addMinutes(15),
            ]);

            $this->sentCode = $code;

            // Send email
            Notification::route('mail', $this->email)
                ->notify(new AppointmentVerificationCode($code, $this->patient_name));

            Log::info('Verification code sent successfully to: ' . $this->email);
            session()->flash('message', 'Verification code sent to your email!');
        } catch (\Exception $e) {
            Log::error('Error sending verification code: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            $this->addError('email', 'Failed to send verification code. Please try again.');
        }
    }

    public function verifyAndBook()
    {
        $this->validate([
            'verificationCode' => 'required|string|size:4',
        ]);

        // Verify code
        $verification = VerificationCode::where('email', $this->email)
            ->where('code', strtoupper($this->verificationCode))
            ->where('expires_at', '>', now())
            ->where('used', false)
            ->first();

        if (!$verification) {
            $this->addError('verificationCode', 'Invalid or expired verification code.');
            return;
        }

        try {
            $availabilityService = app(AppointmentAvailabilityService::class);
            $availabilityService->ensureSlotIsAvailable(
                $this->selectedDentist,
                $this->service_id,
                $this->appointment_date
            );

            $appointment = Appointment::create([
                'dentist_id' => $this->selectedDentist,
                'service_id' => $this->service_id,
                'patient_name' => $this->patient_name,
                'appointment_date' => $this->appointment_date,
                'status' => AppointmentStatus::CONFIRMED,
                'email' => $this->email,
                'phone' => $this->phone,
            ]);

            // Mark verification code as used
            $verification->update(['used' => true]);

            session()->flash('success', 'Appointment booked successfully!');
            $this->dispatch('appointmentBooked', $appointment->id);
            
            $this->closeModal();
        } catch (\Exception $e) {
            Log::error('Error booking appointment: ' . $e->getMessage());
            $this->addError('general', 'Failed to book appointment. Please try again.');
        }
    }

    public function loadAvailableSlots()
    {
        if (!$this->selectedDate || !$this->service_id) {
            return;
        }

        try {
            // Get service duration
            $service = Services::find($this->service_id);
            $this->selectedServiceDuration = $service ? $service->duration : 30;
            
            $availabilityService = app(AppointmentAvailabilityService::class);
            $this->availableSlots = $availabilityService->getAvailableSlots(
                $this->selectedDentist,
                $this->service_id,
                $this->selectedDate
            );
        } catch (\Exception $e) {
            Log::error('Error loading slots: ' . $e->getMessage());
            $this->availableSlots = [];
        }
    }

    public function updatedServiceId()
    {
        // Reload slots when service changes (different durations)
        if ($this->selectedDate) {
            $this->loadAvailableSlots();
        }
        
        // Get updated service duration
        $service = Services::find($this->service_id);
        $this->selectedServiceDuration = $service ? $service->duration : 30;
    }

    public function selectTimeSlot($time)
    {
        $this->selectedTime = $time;
        $this->appointment_date = $this->selectedDate . ' ' . $time;
    }

    public function render()
    {
        return view('livewire.book-appointment-modal');
    }
}
