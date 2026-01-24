<x-layouts.app :title="__('Calendar')">
    @livewire('day-calendar')
</x-layouts.app>
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
