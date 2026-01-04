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
