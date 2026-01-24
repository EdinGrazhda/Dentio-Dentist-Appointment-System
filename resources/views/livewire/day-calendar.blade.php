<div class="py-6 sm:py-8 lg:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <flux:heading size="xl">Appointments Calendar</flux:heading>
                <flux:subheading>View and manage your appointments schedule</flux:subheading>
            </div>
            <flux:button icon="plus" wire:click="$dispatch('open-add-appointment')" style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                Add Appointment
            </flux:button>
        </div>

        <!-- Calendar Controls -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden mb-6">
            <div class="p-4 sm:p-6">
                <div class="flex flex-col lg:flex-row gap-4 justify-between items-start lg:items-center">
                    <!-- Navigation -->
                    <div class="flex items-center gap-3">
                        <flux:button wire:click="goToPreviousDay" icon="chevron-left" variant="ghost" size="sm" style="color: #4988C4 !important;">
                            Previous
                        </flux:button>
                        
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-50">
                                {{ $currentDateDisplay }}
                            </h3>
                            <flux:button wire:click="goToToday" variant="outline" size="sm" style="border-color: #4988C4 !important; color: #4988C4 !important;">
                                Today
                            </flux:button>
                        </div>
                        
                        <flux:button wire:click="goToNextDay" icon="chevron-right" icon-trailing variant="ghost" size="sm" style="color: #4988C4 !important;">
                            Next
                        </flux:button>
                    </div>

                    <!-- Settings Controls -->
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Start Time -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Start:</label>
                            <input type="time" wire:model.live="workStartTime" 
                                   class="px-3 py-1.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:border-[#4988C4] focus:ring-2 focus:ring-[#4988C4]/20">
                        </div>

                        <!-- End Time -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">End:</label>
                            <input type="time" wire:model.live="workEndTime" 
                                   class="px-3 py-1.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:border-[#4988C4] focus:ring-2 focus:ring-[#4988C4]/20">
                        </div>

                        <!-- Slot Duration -->
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Interval:</label>
                            <select wire:model.live="slotDuration" 
                                    class="px-3 py-1.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 focus:border-[#4988C4] focus:ring-2 focus:ring-[#4988C4]/20">
                                <option value="15">15 min</option>
                                <option value="30">30 min</option>
                                <option value="45">45 min</option>
                                <option value="60">60 min</option>
                                <option value="90">90 min</option>
                                <option value="120">120 min</option>
                            </select>
                        </div>

                        <!-- Save Settings Button -->
                        <flux:button wire:click="saveSettings" size="sm" style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                            Save Settings
                        </flux:button>
                    </div>
                </div>

                @if (session()->has('message'))
                    <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <p class="text-sm text-green-900 dark:text-green-100">{{ session('message') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <div class="overflow-x-auto">
                <div class="min-w-[600px]">
                    <!-- Time Slots Grid -->
                    <div class="flex">
                        <!-- Time Labels Column -->
                        <div class="w-20 flex-shrink-0 border-r border-zinc-200 dark:border-zinc-700">
                            <div class="h-12 border-b border-zinc-200 dark:border-zinc-700"></div>
                            @foreach($timeSlots as $slot)
                                <div class="h-16 border-b border-zinc-200 dark:border-zinc-700 flex items-start justify-end pr-3 pt-1">
                                    <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ $slot['display'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Appointments Column -->
                        <div class="flex-1">
                            <!-- Day Header -->
                            <div class="h-12 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-center bg-zinc-50 dark:bg-zinc-800">
                                <span class="text-sm font-semibold text-zinc-900 dark:text-zinc-50">
                                    {{ \Carbon\Carbon::parse($currentDate)->format('l, M j') }}
                                </span>
                            </div>

                            <!-- Time Slots -->
                            @foreach($timeSlots as $index => $slot)
                                @php
                                    $slotDateTime = $currentDate . ' ' . $slot['time'];
                                    $slotAppointments = $appointments[$slotDateTime] ?? [];
                                    $slotTime = \Carbon\Carbon::parse($currentDate . ' ' . $slot['time']);
                                @endphp
                                <div class="h-16 border-b border-zinc-200 dark:border-zinc-700 p-1 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors relative group">
                                    @if(!empty($slotAppointments))
                                        @foreach($slotAppointments as $appointment)
                                            @php
                                                // Calculate how many slots this appointment spans
                                                $durationMinutes = $appointment['duration_minutes'];
                                                // Add 1 to include the end slot (e.g., 60min/30min = 2, but should span 3 slots: 8:00, 8:30, 9:00)
                                                $slotsSpanned = ceil($durationMinutes / $slotDuration) + 1;
                                                $heightInPixels = ($slotsSpanned * 64) - 4; // 64px per slot, minus padding
                                                
                                                // Determine status color with gradients and better styling
                                                $statusStyles = [
                                                    'pending' => [
                                                        'gradient' => 'linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.25))',
                                                        'border' => '#F59E0B',
                                                        'text' => '#92400E',
                                                        'badge' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                                        'icon' => '⏳'
                                                    ],
                                                    'confirmed' => [
                                                        'gradient' => 'linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(22, 163, 74, 0.25))',
                                                        'border' => '#22C55E',
                                                        'text' => '#14532D',
                                                        'badge' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                                        'icon' => '✓'
                                                    ],
                                                    'completed' => [
                                                        'gradient' => 'linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(37, 99, 235, 0.25))',
                                                        'border' => '#3B82F6',
                                                        'text' => '#1E3A8A',
                                                        'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                                        'icon' => '✔'
                                                    ],
                                                    'cancelled' => [
                                                        'gradient' => 'linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(220, 38, 38, 0.25))',
                                                        'border' => '#EF4444',
                                                        'text' => '#7F1D1D',
                                                        'badge' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                                        'icon' => '✕'
                                                    ],
                                                ];
                                                $style = $statusStyles[$appointment['status']] ?? [
                                                    'gradient' => 'linear-gradient(135deg, rgba(73, 136, 196, 0.2), rgba(107, 163, 216, 0.25))',
                                                    'border' => '#4988C4',
                                                    'text' => '#1E3A5F',
                                                    'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                                    'icon' => '📅'
                                                ];
                                            @endphp
                                                    <div class="absolute inset-1 rounded-xl p-3 text-xs overflow-hidden cursor-pointer transition-all duration-200 hover:shadow-xl hover:scale-[1.02] z-10 border border-zinc-200/50 dark:border-zinc-700/50"
                                                        style="background: {{ $style['gradient'] }}; border-left: 4px solid {{ $style['border'] }}; height: {{ $heightInPixels }}px; backdrop-filter: blur(10px);"
                                                        wire:click="openAppointmentModal({{ $appointment['id'] }})"
                                                        title="Click to view details">
                                                
                                                <!-- Header with status badge -->
                                                <div class="flex items-start justify-between gap-2 mb-1.5">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="font-bold text-sm text-zinc-900 dark:text-zinc-50 truncate flex items-center gap-1.5">
                                                            <span class="text-base">{{ $style['icon'] }}</span>
                                                            <span>{{ $appointment['patient_name'] }}</span>
                                                        </div>
                                                    </div>
                                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-semibold uppercase tracking-wide {{ $style['badge'] }} flex-shrink-0">
                                                        {{ ucfirst($appointment['status']) }}
                                                    </span>
                                                </div>
                                                
                                                <!-- Service name with icon -->
                                                <div class="flex items-center gap-1.5 mb-1.5">
                                                    <svg class="w-3 h-3 flex-shrink-0" style="color: {{ $style['border'] }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-zinc-700 dark:text-zinc-300 truncate font-medium">{{ $appointment['service']['service_name'] }}</span>
                                                </div>
                                                
                                                <!-- Time and duration -->
                                                <div class="flex items-center gap-1.5 mb-1">
                                                    <svg class="w-3 h-3 flex-shrink-0" style="color: {{ $style['border'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-zinc-600 dark:text-zinc-400 text-[11px] font-medium">
                                                        {{ $appointment['start_time'] }} - {{ $appointment['end_time'] }}
                                                    </span>
                                                    <span class="px-1.5 py-0.5 rounded text-[9px] font-semibold" style="background: {{ $style['border'] }}20; color: {{ $style['border'] }}">
                                                        {{ $durationMinutes }} min
                                                    </span>
                                                </div>
                                                
                                                <!-- Dentist info -->
                                                @if(isset($appointment['dentist']))
                                                    <div class="flex items-center gap-1.5">
                                                        <svg class="w-3 h-3 flex-shrink-0" style="color: {{ $style['border'] }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="text-zinc-600 dark:text-zinc-400 text-[10px]">Dr. {{ $appointment['dentist']['name'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- Empty slot - clickable -->
                                        <div class="absolute inset-1 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer"
                                            onclick="window.dispatchEvent(new CustomEvent('open-add-appointment', { detail: { time: '{{ $slot['time'] }}' } }))">
                                            <span class="text-xs text-zinc-400 dark:text-zinc-600">Click to add</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Message -->
        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex items-start gap-3">
                <flux:icon.information-circle class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" />
                <div class="text-sm text-blue-900 dark:text-blue-100">
                    <p class="font-semibold mb-1">Live Schedule Updates</p>
                    <p>Adjust the start time, end time, and interval above to customize your calendar view. Changes will automatically update the customer booking modal.</p>
                </div>
            </div>
        </div>
    </div>

        <!-- Appointment Details Modal -->
        <div x-data="{ open: @entangle('showAppointmentModal') }"
            x-show="open"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <!-- Background overlay -->
                  <div x-show="open" 
                      x-transition:enter="ease-out duration-300"
                      x-transition:enter-start="opacity-0"
                      x-transition:enter-end="opacity-100"
                      x-transition:leave="ease-in duration-200"
                      x-transition:leave-start="opacity-100"
                      x-transition:leave-end="opacity-0"
                      class="fixed inset-0 transition-opacity bg-white/5"
                      style="backdrop-filter: blur(18px); -webkit-backdrop-filter: blur(18px);" 
                      @click="open = false; $wire.closeAppointmentModal()"></div>

            <!-- Modal panel -->
            @if($detailAppointment)
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative inline-block w-full max-w-2xl px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl sm:my-8 sm:align-middle sm:p-6">
                    
                    <!-- Modal Header -->
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-zinc-50">
                                Appointment Details
                            </h3>
                            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                                Complete information about this appointment
                            </p>
                        </div>
                        <button @click="open = false; $wire.closeAppointmentModal()" 
                            class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Status Badge -->
                    <div class="mb-6">
                        @php
                            $statusValue = $detailAppointment->status?->value ?? $detailAppointment->status ?? '';
                            $statusColors = [
                                'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
                                'confirmed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                'completed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                            ];
                            $statusClass = $statusColors[$statusValue] ?? 'bg-zinc-100 text-zinc-800';
                        @endphp
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold uppercase tracking-wide {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $statusValue)) }}
                        </span>
                    </div>

                    <!-- Appointment Information Grid -->
                    <div class="space-y-6">
                        <!-- Patient Information -->
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-xl p-5 border border-zinc-200 dark:border-zinc-700">
                            <h4 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-4">Patient Information</h4>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-zinc-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Full Name</p>
                                        <p class="text-base font-semibold text-zinc-900 dark:text-zinc-50">{{ $detailAppointment->patient_name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Email</p>
                                        <p class="text-base font-semibold text-zinc-900 dark:text-zinc-50">{{ $detailAppointment->email }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Phone</p>
                                        <p class="text-base font-semibold text-zinc-900 dark:text-zinc-50">{{ $detailAppointment->phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Details -->
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-xl p-5 border border-zinc-200 dark:border-zinc-700">
                            <h4 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-4">Appointment Details</h4>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-zinc-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Service</p>
                                        <p class="text-base font-semibold text-zinc-900 dark:text-zinc-50">{{ $detailAppointment->service->service_name ?? 'Service' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Date & Time</p>
                                        <p class="text-base font-semibold text-zinc-900 dark:text-zinc-50">{{ \Carbon\Carbon::parse($detailAppointment->appointment_date)->format('F j, Y - g:i A') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Duration</p>
                                        <p class="text-base font-semibold text-zinc-900 dark:text-zinc-50">{{ $detailAppointment->service->duration ?? 0 }} minutes</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Price</p>
                                        <p class="text-base font-semibold text-zinc-900 dark:text-zinc-50">${{ number_format($detailAppointment->service->price ?? 0, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dentist Information -->
                        <div class="bg-zinc-50 dark:bg-zinc-900/50 rounded-xl p-5 border border-zinc-200 dark:border-zinc-700">
                            <h4 class="text-sm font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wide mb-4">Dentist Information</h4>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-zinc-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Doctor</p>
                                        <p class="text-base font-semibold text-zinc-900 dark:text-zinc-50">Dr. {{ $detailAppointment->dentist->name ?? 'Doctor' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <div>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">Email</p>
                                        <p class="text-base font-semibold text-zinc-900 dark:text-zinc-50">{{ $detailAppointment->dentist->email ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="mt-6 flex justify-end">
                        <flux:button wire:click="closeAppointmentModal" variant="ghost">
                            Close
                        </flux:button>
                    </div>
                </div>
                @endif
            </div>
        </div>
</div>
