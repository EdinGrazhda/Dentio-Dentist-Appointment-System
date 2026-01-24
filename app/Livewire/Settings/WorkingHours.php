<?php

namespace App\Livewire\Settings;

use App\Models\Dentist;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WorkingHours extends Component
{
    public $work_start_time;
    public $work_end_time;
    public $slot_duration;
    public $dentist;

    public function mount()
    {
        // Get the dentist associated with the logged-in user
        $this->dentist = Dentist::where('id', Auth::id())->first() 
                      ?? Dentist::first(); // Fallback for testing
        
        if ($this->dentist) {
            $this->work_start_time = $this->dentist->work_start_time 
                ? \Carbon\Carbon::parse($this->dentist->work_start_time)->format('H:i') 
                : '08:00';
            $this->work_end_time = $this->dentist->work_end_time 
                ? \Carbon\Carbon::parse($this->dentist->work_end_time)->format('H:i') 
                : '16:00';
            $this->slot_duration = $this->dentist->slot_duration ?? 30;
        }
    }

    public function save()
    {
        $this->validate([
            'work_start_time' => 'required|date_format:H:i',
            'work_end_time' => 'required|date_format:H:i|after:work_start_time',
            'slot_duration' => 'required|integer|min:15|max:120',
        ]);

        $this->dentist->update([
            'work_start_time' => $this->work_start_time,
            'work_end_time' => $this->work_end_time,
            'slot_duration' => $this->slot_duration,
        ]);

        session()->flash('message', 'Working hours updated successfully!');
    }

    public function render()
    {
        return view('livewire.settings.working-hours');
    }
}
