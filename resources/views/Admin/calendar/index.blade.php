<x-layouts.app :title="__('Calendar')">
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
                <!-- Calendar Navigation Header -->
                <div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
                    <div class="flex items-center justify-between">
                        <!-- Previous Month Button -->
                        <flux:button 
                            wire:click="goToPreviousMonth" 
                            icon="chevron-left" 
                            variant="ghost" 
                            size="sm"
                            style="color: #4988C4 !important;">
                            Previous
                        </flux:button>

                        <!-- Current Month/Year Display -->
                        <div class="flex items-center gap-3">
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-50">
                                {{ now()->setDate($startsAt->year, $startsAt->month, 1)->format('F Y') }}
                            </h3>
                            
                            <!-- Today Button -->
                            <flux:button 
                                wire:click="goToCurrentMonth" 
                                variant="outline" 
                                size="sm"
                                style="border-color: #4988C4 !important; color: #4988C4 !important;">
                                Today
                            </flux:button>
                        </div>

                        <!-- Next Month Button -->
                        <flux:button 
                            wire:click="goToNextMonth" 
                            icon="chevron-right" 
                            icon-trailing 
                            variant="ghost" 
                            size="sm"
                            style="color: #4988C4 !important;">
                            Next
                        </flux:button>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="p-6">
                    <livewire:appointments-calendar
                        week-starts-at="1"
                        :day-of-week-format="['S', 'M', 'T', 'W', 'T', 'F', 'S']"
                        :drag-and-drop-enabled="false"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Appointment Modal -->
    <flux:modal name="edit-appointment" class="max-w-3xl">
        <div>
            <flux:heading size="lg">Edit Appointment</flux:heading>
            <flux:subheading>Update the appointment information below</flux:subheading>
        </div>

        @if($selectedAppointment)
            <form wire:submit="updateAppointment" class="space-y-6 mt-6">
                <!-- Patient Name Field -->
                <flux:input
                    wire:model="form.patient_name"
                    label="Patient Name"
                    type="text"
                    required
                    placeholder="Enter patient name"
                />

                <!-- Dentist Field -->
                <flux:select
                    wire:model="form.dentist_id"
                    label="Dentist"
                    placeholder="Select a dentist"
                    required
                >
                    @foreach($dentists as $dentist)
                        <flux:option value="{{ $dentist->id }}">{{ $dentist->name }}</flux:option>
                    @endforeach
                </flux:select>

                <!-- Service Field -->
                <flux:select
                    wire:model="form.service_id"
                    label="Service"
                    placeholder="Select a service"
                    required
                >
                    @foreach($services as $service)
                        <flux:option value="{{ $service->id }}">
                            {{ $service->service_name }} - ${{ number_format($service->price, 2) }}
                            @if($service->duration)
                                @php
                                    $hours = floor($service->duration / 60);
                                    $minutes = $service->duration % 60;
                                    $durationText = $hours > 0 ? $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'm' : '') : $service->duration . 'm';
                                @endphp
                                ({{ $durationText }})
                            @endif
                        </flux:option>
                    @endforeach
                </flux:select>

                <!-- Date & Time Field -->
                <flux:input
                    wire:model="form.appointment_date"
                    label="Date & Time"
                    type="datetime-local"
                    required
                />

                <!-- Status Field -->
                <flux:select
                    wire:model="form.status"
                    label="Status"
                    required
                >
                    @foreach($statuses as $value => $label)
                        <flux:option value="{{ $value }}">{{ $label }}</flux:option>
                    @endforeach
                </flux:select>

                <flux:separator />

                <div class="flex items-center justify-between gap-3">
                    <flux:button type="button" wire:click="deleteAppointment" variant="danger" icon="trash">
                        Delete
                    </flux:button>
                    <div class="flex gap-3">
                        <flux:button type="button" x-on:click="$flux.modal('edit-appointment').close()" variant="ghost">
                            Cancel
                        </flux:button>
                        <flux:button type="submit" variant="primary" icon="check" style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                            Update Appointment
                        </flux:button>
                    </div>
                </div>
            </form>
        @endif
    </flux:modal>

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
</x-layouts.app>
