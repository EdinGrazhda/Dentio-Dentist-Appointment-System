<div>
    <!-- Modal Backdrop and Container -->
    <div 
        x-data="{ open: @entangle('open') }"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        @keydown.escape.window="open = false"
    >
        <!-- Backdrop -->
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-zinc-900/80 backdrop-blur-sm"
            @click="$wire.closeModal()"
        ></div>

        <!-- Modal Content -->
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 flex items-center justify-center p-4"
        >
            <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="relative bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-2xl">
                    <!-- Close Button -->
                    <button 
                        type="button"
                        wire:click="closeModal"
                        class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-colors z-10"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Modal Content -->
                    <div class="p-6">
                        <!-- Header -->
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white mb-2" style="font-family: 'Space Grotesk', sans-serif;">
                                Book Appointment
                            </h2>
                            <div class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                                <svg class="w-4 h-4 text-[#4988C4]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ $selectedDentistName ? 'Dr. ' . $selectedDentistName : 'Select a dentist' }}</span>
                            </div>
                        </div>

                        <!-- Progress Steps -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between">
                                <!-- Step 1 -->
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 1 ? 'bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-500' }} font-semibold transition-all">
                                        @if($currentStep > 1)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @else
                                            1
                                        @endif
                                    </div>
                                    <span class="text-xs mt-2 {{ $currentStep >= 1 ? 'text-[#4988C4] font-medium' : 'text-zinc-500' }}">Info & Service</span>
                                </div>
                                
                                <!-- Connector -->
                                <div class="flex-1 h-1 mx-2 {{ $currentStep >= 2 ? 'bg-gradient-to-r from-[#4988C4] to-[#6BA3D8]' : 'bg-zinc-200 dark:bg-zinc-700' }} transition-all"></div>
                                
                                <!-- Step 2 -->
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 2 ? 'bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-500' }} font-semibold transition-all">
                                        @if($currentStep > 2)
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        @else
                                            2
                                        @endif
                                    </div>
                                    <span class="text-xs mt-2 {{ $currentStep >= 2 ? 'text-[#4988C4] font-medium' : 'text-zinc-500' }}">Date & Time</span>
                                </div>
                                
                                <!-- Connector -->
                                <div class="flex-1 h-1 mx-2 {{ $currentStep >= 3 ? 'bg-gradient-to-r from-[#4988C4] to-[#6BA3D8]' : 'bg-zinc-200 dark:bg-zinc-700' }} transition-all"></div>
                                
                                <!-- Step 3 -->
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $currentStep >= 3 ? 'bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-500' }} font-semibold transition-all">
                                        3
                                    </div>
                                    <span class="text-xs mt-2 {{ $currentStep >= 3 ? 'text-[#4988C4] font-medium' : 'text-zinc-500' }}">Verification</span>
                                </div>
                            </div>
                        </div>

                        <!-- Error Messages -->
                        @if (session()->has('message'))
                            <div class="mb-6 rounded-xl border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/20 p-4">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-blue-700 dark:text-blue-200">{{ session('message') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($debugMessage)
                            <div class="mb-4 p-3 rounded bg-yellow-50 text-yellow-800 text-sm">
                                Debug: {{ $debugMessage }}
                            </div>
                        @endif

                        <!-- Step 1: Customer Info & Service -->
                        @if($currentStep === 1)
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Full Name *</label>
                                    <input 
                                        type="text"
                                        wire:model="patient_name"
                                        placeholder="Enter your name"
                                        class="w-full px-3 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition"
                                    />
                                    @error('patient_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Email *</label>
                                        <input 
                                            type="email"
                                            wire:model="email"
                                            placeholder="email@example.com"
                                            class="w-full px-3 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition"
                                        />
                                        @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Phone *</label>
                                        <input 
                                            type="tel"
                                            wire:model="phone"
                                            placeholder="(555) 123-4567"
                                            class="w-full px-3 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition"
                                        />
                                        @error('phone') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Service *</label>
                                    <select 
                                        wire:model.live="service_id"
                                        class="w-full px-3 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition cursor-pointer"
                                    >
                                        <option value="">Select a service</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">
                                                {{ $service->service_name }} - 
                                                @if($service->duration >= 60)
                                                    {{ floor($service->duration / 60) }}h {{ $service->duration % 60 > 0 ? ($service->duration % 60) . 'min' : '' }}
                                                @else
                                                    {{ $service->duration }} min
                                                @endif
                                                - ${{ number_format($service->price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                    
                                    @if($service_id && $selectedServiceDuration > 0)
                                        <div class="mt-2 text-xs text-zinc-600 dark:text-zinc-400 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>This service takes {{ $selectedServiceDuration }} minutes</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <div>
                                            <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-1">Email Verification</h4>
                                            <p class="text-xs text-blue-700 dark:text-blue-200">A 4-character verification code will be sent to your email when you proceed to the next step.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Step 2: Date & Time Selection -->
                        @if($currentStep === 2)
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Select Date *</label>
                                    <input 
                                        type="date"
                                        wire:model.live="selectedDate"
                                        wire:change="loadAvailableSlots"
                                        min="{{ date('Y-m-d') }}"
                                        class="w-full px-3 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition"
                                    />
                                    @error('appointment_date') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                @if($selectedDate)
                                    <div>
                                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-3">Available Time Slots</label>
                                        
                                        @if(count($availableSlots) > 0)
                                            <div class="grid grid-cols-3 gap-2 max-h-64 overflow-y-auto">
                                                @foreach($availableSlots as $slot)
                                                    <button 
                                                        type="button"
                                                        wire:click="selectTimeSlot('{{ $slot }}')"
                                                        class="px-4 py-3 text-sm rounded-lg border transition {{ $selectedTime === $slot ? 'bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white border-transparent' : 'border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800' }}"
                                                    >
                                                        {{ \Carbon\Carbon::parse($slot)->format('h:i A') }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-center py-8 border border-zinc-200 dark:border-zinc-700 rounded-lg">
                                                <svg class="w-12 h-12 mx-auto text-zinc-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <p class="text-sm text-zinc-600 dark:text-zinc-400">No available slots for this date</p>
                                                <p class="text-xs text-zinc-500 dark:text-zinc-500 mt-1">Please select another date</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Step 3: Email Verification -->
                        @if($currentStep === 3)
                            <div class="space-y-6">
                                <div class="text-center">
                                    <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-2">Check Your Email</h3>
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400">We've sent a 4-character verification code to</p>
                                    <p class="text-sm font-semibold text-[#4988C4] mt-1">{{ $email }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2 text-center">Enter Verification Code</label>
                                    <input 
                                        type="text"
                                        wire:model="verificationCode"
                                        maxlength="4"
                                        placeholder="XXXX"
                                        class="w-full px-4 py-3 text-2xl font-bold text-center tracking-widest rounded-lg border-2 border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition uppercase"
                                        style="letter-spacing: 0.5em;"
                                    />
                                    @error('verificationCode') <span class="text-xs text-red-500 mt-1 block text-center">{{ $message }}</span> @enderror
                                </div>

                                <div class="bg-zinc-50 dark:bg-zinc-800 rounded-lg p-4 space-y-2">
                                    <div class="flex items-center gap-2 text-sm">
                                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span class="text-zinc-700 dark:text-zinc-300">{{ $patient_name }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm">
                                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-zinc-700 dark:text-zinc-300">{{ \Carbon\Carbon::parse($appointment_date)->format('M d, Y - h:i A') }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-sm">
                                        <svg class="w-4 h-4 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <span class="text-zinc-700 dark:text-zinc-300">{{ $services->find($service_id)?->service_name }}</span>
                                    </div>
                                </div>

                                <p class="text-xs text-center text-zinc-500 dark:text-zinc-400">Code expires in 15 minutes. Didn't receive it? Check your spam folder.</p>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between gap-3 pt-6 border-t border-zinc-200 dark:border-zinc-800 mt-6">
                            <div>
                                @if($currentStep > 1)
                                    <button 
                                        type="button"
                                        wire:click="previousStep"
                                        class="px-4 py-2 text-sm font-medium rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition flex items-center gap-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        Back
                                    </button>
                                @endif
                            </div>

                            <div class="flex items-center gap-3">
                                <button 
                                    type="button"
                                    wire:click="closeModal"
                                    class="px-4 py-2 text-sm font-medium rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
                                >
                                    Cancel
                                </button>
                                
                                @if($currentStep < 3)
                                    <button 
                                        type="button"
                                        wire:click="nextStep"
                                        wire:loading.attr="disabled"
                                        class="px-5 py-2 text-sm font-semibold rounded-lg bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white hover:shadow-lg transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <span wire:loading.remove wire:target="nextStep">Continue</span>
                                        <span wire:loading wire:target="nextStep">Processing...</span>
                                        <svg wire:loading.remove wire:target="nextStep" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        <svg wire:loading wire:target="nextStep" class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                @else
                                    <button 
                                        type="button"
                                        wire:click="verifyAndBook"
                                        wire:loading.attr="disabled"
                                        class="px-5 py-2 text-sm font-semibold rounded-lg bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white hover:shadow-lg transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <span wire:loading.remove wire:target="verifyAndBook">Confirm Appointment</span>
                                        <span wire:loading wire:target="verifyAndBook">Verifying...</span>
                                        <svg wire:loading wire:target="verifyAndBook" class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
