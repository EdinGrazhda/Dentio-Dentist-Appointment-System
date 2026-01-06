<div>
    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-green-50 dark:bg-green-900/20 p-4 border border-green-200 dark:border-green-800">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <flux:heading size="xl">Appointments Calendar</flux:heading>
                    <flux:subheading>View and manage your appointments schedule</flux:subheading>
                </div>
                <flux:button :href="route('appointments.create')" icon="plus" wire:navigate style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                    Add Appointment
                </flux:button>
            </div>

            <!-- Calendar -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                <livewire:appointments-calendar
                    week-starts-at="1"
                    :day-of-week-format="['S', 'M', 'T', 'W', 'T', 'F', 'S']"
                    :drag-and-drop-enabled="false"
                    before-calendar-view="livewire.calendar-navigation"
                    :key="$refresh ?? 0"
                />
            </div>
        </div>
    </div>

    <style>
    /* Calendar Styling */
    .bg-blue-500 {
        background-color: #4988C4 !important;
    }
    
    .text-blue-500 {
        color: #4988C4 !important;
    }
    
    .hover\:bg-blue-500:hover {
        background-color: #4988C4 !important;
    }

    /* Calendar container */
    [x-data*="LivewireCalendar"] {
        width: 100%;
    }

    /* Calendar header */
    .livewire-calendar .header {
        margin-bottom: 1rem;
    }

    /* Calendar grid */
    .livewire-calendar .grid {
        display: grid;
        gap: 0;
    }

    /* Calendar days */
    .livewire-calendar .day {
        min-height: 100px;
        padding: 0.5rem;
        border: 1px solid #e4e4e7;
        transition: background-color 0.2s;
    }

    .dark .livewire-calendar .day {
        border-color: #3f3f46;
    }

    .livewire-calendar .day:hover {
        background-color: #f4f4f5;
    }

    .dark .livewire-calendar .day:hover {
        background-color: #27272a;
    }

    /* Calendar events */
    .livewire-calendar .event {
        background: linear-gradient(to right, #4988C4, #6BA3D8);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        margin-bottom: 0.25rem;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .livewire-calendar .event:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(73, 136, 196, 0.3);
    }

    /* Today highlight */
    .livewire-calendar .today {
        background-color: #E3F2FD;
    }

    .dark .livewire-calendar .today {
        background-color: #1e3a5f;
    }

    /* Day number */
    .livewire-calendar .day-number {
        font-weight: 600;
        color: #18181b;
        margin-bottom: 0.5rem;
    }

    .dark .livewire-calendar .day-number {
        color: #fafafa;
    }

    /* Navigation buttons */
    .livewire-calendar button {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }

    .livewire-calendar button:hover {
        background: linear-gradient(to right, #4988C4, #6BA3D8);
        color: white;
    }
    </style>

    <!-- Edit Appointment Modal -->
    <flux:modal name="edit-appointment" class="max-w-2xl">
        <div class="relative">
            <!-- Modal Header with gradient background -->
            <div class="relative -mx-6 -mt-6 px-6 py-6 bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] rounded-t-xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-white">Edit Appointment</h2>
                            <p class="text-sm text-white/80">Update appointment details</p>
                        </div>
                    </div>
                    <button type="button" x-on:click="$flux.modal('edit-appointment').close()" class="w-8 h-8 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            @if($selectedAppointment)
                <form wire:submit.prevent="updateAppointment" class="space-y-5 mt-6">
                    <!-- Patient Name Field with Icon -->
                    <div class="relative">
                        <label class="block text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Patient Name</span>
                            </div>
                        </label>
                        <input 
                            type="text"
                            wire:model="form.patient_name"
                            required
                            placeholder="Enter patient name"
                            class="w-full px-4 py-3 pl-4 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-[#4988C4]/30 focus:border-[#4988C4] transition-all duration-200"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Dentist Field -->
                        <div>
                            <label class="block text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Dentist</span>
                                </div>
                            </label>
                            <select 
                                wire:model="form.dentist_id"
                                required
                                class="w-full px-4 py-3 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-[#4988C4]/30 focus:border-[#4988C4] transition-all duration-200 cursor-pointer"
                            >
                                <option value="">Select dentist</option>
                                @foreach($dentists as $dentist)
                                    <option value="{{ $dentist->id }}">{{ $dentist->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Service Field -->
                        <div>
                            <label class="block text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span>Service</span>
                                </div>
                            </label>
                            <select 
                                wire:model="form.service_id"
                                required
                                class="w-full px-4 py-3 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-[#4988C4]/30 focus:border-[#4988C4] transition-all duration-200 cursor-pointer"
                            >
                                <option value="">Select service</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">
                                        {{ $service->service_name }} - ${{ number_format($service->price, 2) }}
                                        @if($service->duration)
                                            @php
                                                $hours = floor($service->duration / 60);
                                                $minutes = $service->duration % 60;
                                                $durationText = $hours > 0 ? $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'm' : '') : $service->duration . 'm';
                                            @endphp
                                            ({{ $durationText }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Date & Time Field -->
                        <div>
                            <label class="block text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Date & Time</span>
                                </div>
                            </label>
                            <input 
                                type="datetime-local"
                                wire:model="form.appointment_date"
                                required
                                class="w-full px-4 py-3 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-[#4988C4]/30 focus:border-[#4988C4] transition-all duration-200"
                            />
                        </div>

                        <!-- Status Field -->
                        <div>
                            <label class="block text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Status</span>
                                </div>
                            </label>
                            <select 
                                wire:model="form.status"
                                required
                                class="w-full px-4 py-3 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-[#4988C4]/30 focus:border-[#4988C4] transition-all duration-200 cursor-pointer"
                            >
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-zinc-200 dark:border-zinc-700 -mx-6"></div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-2">
                        <button 
                            type="button" 
                            wire:click="deleteAppointment"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white font-semibold shadow-lg shadow-red-500/30 hover:shadow-xl hover:shadow-red-500/40 transition-all duration-200 hover:scale-105"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Delete</span>
                        </button>
                        
                        <div class="flex gap-3">
                            <button 
                                type="button" 
                                x-on:click="$flux.modal('edit-appointment').close()" 
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border-2 border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-semibold transition-all duration-200 hover:scale-105"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span>Cancel</span>
                            </button>
                            <button 
                                type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white font-semibold shadow-lg shadow-[#4988C4]/30 hover:shadow-xl hover:shadow-[#4988C4]/40 transition-all duration-200 hover:scale-105"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Update</span>
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </flux:modal>
</div>
