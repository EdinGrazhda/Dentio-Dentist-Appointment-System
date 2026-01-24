<x-layouts.app :title="__('Add New Appointment')">
    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <flux:button :href="route('appointments.index')" icon="arrow-left" variant="ghost" size="sm" square wire:navigate />
                    <flux:heading size="xl">Add New Appointment</flux:heading>
                </div>
                <flux:subheading class="ml-11">Fill in the information below to schedule a new appointment</flux:subheading>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden p-6 sm:p-8">
                <form action="{{ route('appointments.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="rounded-xl bg-red-50 dark:bg-red-900/20 p-4 border-2 border-red-200 dark:border-red-800">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-sm font-semibold text-red-800 dark:text-red-200 mb-1">Validation Error</h3>
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li class="text-sm text-red-700 dark:text-red-300">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Patient Name Field -->
                    <flux:input
                        name="patient_name"
                        label="Patient Name"
                        :value="old('patient_name')"
                        type="text"
                        required
                        placeholder="Enter patient name"
                    />

                    <!-- Dentist Field -->
                    <div>
                        <label for="dentist_id" class="block text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
                            Dentist
                        </label>
                        <select 
                            id="dentist_id" 
                            name="dentist_id"
                            class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition-colors"
                            required
                        >
                            <option value="">Select a dentist</option>
                            @foreach($dentists as $dentist)
                                <option value="{{ $dentist->id }}" {{ old('dentist_id') == $dentist->id ? 'selected' : '' }}>
                                    {{ $dentist->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('dentist_id')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service Field -->
                    <div>
                        <label for="service_id" class="block text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
                            Service
                        </label>
                        <select 
                            id="service_id" 
                            name="service_id"
                            class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition-colors"
                            required
                        >
                            <option value="">Select a service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" 
                                        data-duration="{{ $service->duration }}" 
                                        {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->service_name }} - ${{ number_format($service->price, 2) }}
                                    @if($service->duration)
                                        ({{ $service->duration >= 60 ? floor($service->duration / 60) . 'h' . ($service->duration % 60 > 0 ? ' ' . ($service->duration % 60) . 'm' : '') : $service->duration . 'm' }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <div id="duration-info" class="mt-2 text-sm text-zinc-600 dark:text-zinc-400" style="display: none;">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Expected duration: <strong id="duration-text"></strong></span>
                            </div>
                        </div>
                        @error('service_id')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date & Time Field -->
                    <flux:input
                        name="appointment_date"
                        label="Date & Time"
                        :value="old('appointment_date')"
                        type="datetime-local"
                        required
                    />
                    @error('appointment_date')
                        <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror

                    <!-- Status Field -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
                            Status
                        </label>
                        <select 
                            id="status" 
                            name="status"
                            class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition-colors"
                            required
                        >
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <flux:separator />
                    <div class="flex items-center justify-end gap-3">
                        <flux:button :href="route('appointments.index')" variant="ghost" wire:navigate>
                            Cancel
                        </flux:button>
                        <flux:button type="submit" variant="primary" icon="check" style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                            Create Appointment
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Duration Display Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const serviceSelect = document.getElementById('service_id');
            const durationInfo = document.getElementById('duration-info');
            const durationText = document.getElementById('duration-text');

            function updateDuration() {
                const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                const duration = selectedOption.getAttribute('data-duration');

                if (duration && duration > 0) {
                    const hours = Math.floor(duration / 60);
                    const minutes = duration % 60;
                    let text = '';
                    
                    if (hours > 0) {
                        text += hours + 'h';
                        if (minutes > 0) {
                            text += ' ' + minutes + 'm';
                        }
                    } else {
                        text = minutes + ' minutes';
                    }
                    
                    durationText.textContent = text;
                    durationInfo.style.display = 'block';
                } else {
                    durationInfo.style.display = 'none';
                }
            }

            serviceSelect.addEventListener('change', updateDuration);
            
            // Check on page load if there's a selected service
            if (serviceSelect.value) {
                updateDuration();
            }
        });
    </script>

    <!-- CSRF Token Auto-Refresh Script -->
    <script>
        // Refresh CSRF token every 30 minutes to prevent 419 errors
        setInterval(function() {
            fetch('/dashboard', {
                method: 'HEAD',
                credentials: 'same-origin'
            }).then(response => {
                const token = document.querySelector('meta[name="csrf-token"]');
                if (token && response.headers.get('X-CSRF-TOKEN')) {
                    token.setAttribute('content', response.headers.get('X-CSRF-TOKEN'));
                }
            });
        }, 30 * 60 * 1000); // 30 minutes
    </script>
</x-layouts.app>
