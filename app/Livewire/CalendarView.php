<?php

namespace App\Livewire;

use Livewire\Component;

class CalendarView extends Component
{
    public function render()
    {
        return view('livewire.calendar-view')
            ->layout('components.layouts.app', ['title' => __('Calendar')]);
    }
}
