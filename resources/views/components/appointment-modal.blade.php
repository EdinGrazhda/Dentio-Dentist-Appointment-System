@props(['services'])

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('appointmentModal', () => ({
                storeUrl: null,
                open: false,
                selectedDentist: null,
                selectedDentistName: '',
                form: {
                    patient_name: '',
                    dentist_id: '',
                    service_id: '',
                    appointment_date: '',
                    phone: '',
                    email: ''
                },
                errors: {},
                loading: false,
                success: false,

                init() {
                    this.storeUrl = this.$el.dataset.storeUrl;
                },

                resetForm(dentistId = this.selectedDentist) {
                    this.form = {
                        patient_name: '',
                        dentist_id: dentistId,
                        service_id: '',
                        appointment_date: '',
                        phone: '',
                        email: ''
                    };
                    this.errors = {};
                    this.success = false;
                },

                openModal(event) {
                    this.selectedDentist = event.detail.dentistId;
                    this.selectedDentistName = event.detail.dentistName;
                    this.resetForm(event.detail.dentistId);
                    this.open = true;
                },

                closeModal() {
                    this.open = false;
                    this.selectedDentist = null;
                    this.selectedDentistName = '';
                    this.resetForm(null);
                },

                async submitAppointment() {
                    this.loading = true;
                    this.errors = {};

                    try {
                        const response = await fetch(this.storeUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                ...this.form,
                                dentist_id: this.selectedDentist
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.success = true;
                            setTimeout(() => {
                                this.closeModal();
                            }, 3000);
                        } else {
                            this.errors = data.errors || {};
                        }
                    } catch (error) {
                        this.errors = { general: ['An error occurred. Please try again.'] };
                    } finally {
                        this.loading = false;
                    }
                }
            }));
        });
    </script>
@endonce

<div 
    x-data="appointmentModal()"
    @open-appointment-modal.window="openModal($event)"
    @keydown.escape.window="if (open) { closeModal(); }"
    x-cloak
    class="relative z-50"
    data-store-url="{{ route('public.appointments.store') }}"
>
    <!-- Modal Backdrop -->
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-zinc-900/80 backdrop-blur-sm"
        @click="closeModal()"
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
        class="fixed inset-0 flex items-center justify-center p-3 sm:p-4 z-50"
        @click.self="closeModal()"
    >
        <div class="w-full max-w-5xl max-h-[95vh] overflow-y-auto">
            <div class="relative bg-white/95 dark:bg-zinc-950/90 backdrop-blur-2xl border border-white/50 dark:border-zinc-800 rounded-3xl sm:rounded-[34px] shadow-[0_25px_80px_rgba(73,136,196,0.35)] overflow-hidden">
                <button 
                    type="button"
                    @click="closeModal()"
                    class="absolute top-3 right-3 sm:top-4 sm:right-4 w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-full bg-white/70 dark:bg-zinc-900/70 border border-white/60 dark:border-zinc-800 text-zinc-600 dark:text-zinc-200 hover:bg-white/100 transition-colors z-10"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <div class="grid lg:grid-cols-[380px,1fr]">
                    <div class="bg-gradient-to-br from-[#4988C4] via-[#4D9DE0] to-[#6BA3D8] text-white p-6 sm:p-8 flex flex-col">
                        <!-- Header -->
                        <div class="space-y-6">
                            <div class="flex items-center justify-between text-[10px] sm:text-xs uppercase tracking-[0.35em] text-white/70">
                                <span>Dentio Pulse</span>
                                <span class="flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Live
                                </span>
                            </div>
                            
                            <div class="space-y-3">
                                <h2 class="text-3xl sm:text-4xl font-bold leading-tight" style="font-family: 'Space Grotesk', sans-serif;">
                                    Book Your<br/>Perfect Smile
                                </h2>
                                <p class="text-sm sm:text-base text-white/90 leading-relaxed">
                                    Experience seamless dental care with AI-powered scheduling and expert professionals ready to serve you.
                                </p>
                            </div>
                        </div>

                        <!-- Selected Dentist - Prominent -->
                        <div class="mt-8 rounded-3xl border-2 border-white/30 bg-white/15 backdrop-blur-sm p-5 shadow-lg">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-white/30 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] uppercase tracking-wider text-white/60 mb-1">Your Dentist</p>
                                    <p class="text-lg font-bold truncate" x-text="selectedDentistName ? 'Dr. ' + selectedDentistName : 'Select from cards →'"></p>
                                    <p class="text-xs text-white/80" x-show="selectedDentistName">Ready to assist you</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Grid - Modern Cards -->
                        <div class="mt-6 grid grid-cols-3 gap-3">
                            <div class="rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 p-4 text-center">
                                <div class="w-10 h-10 mx-auto mb-2 rounded-xl bg-white/20 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold">12K+</p>
                                <p class="text-[9px] text-white/70 uppercase tracking-wider mt-1">Patients</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 p-4 text-center">
                                <div class="w-10 h-10 mx-auto mb-2 rounded-xl bg-white/20 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold">4.97</p>
                                <p class="text-[9px] text-white/70 uppercase tracking-wider mt-1">Rating</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 p-4 text-center">
                                <div class="w-10 h-10 mx-auto mb-2 rounded-xl bg-white/20 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold">8m</p>
                                <p class="text-[9px] text-white/70 uppercase tracking-wider mt-1">Avg Time</p>
                            </div>
                        </div>

                        <!-- Process Timeline -->
                        <div class="mt-6 space-y-3">
                            <p class="text-xs uppercase tracking-wider text-white/70 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Booking Process
                            </p>
                            <div class="space-y-3">
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full bg-white text-[#4988C4] flex items-center justify-center font-bold text-sm">1</div>
                                        <div class="w-0.5 h-full bg-white/30 mt-1"></div>
                                    </div>
                                    <div class="pb-3 flex-1">
                                        <p class="font-semibold text-sm">Choose Dentist</p>
                                        <p class="text-xs text-white/80">Select from our expert team</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full bg-white text-[#4988C4] flex items-center justify-center font-bold text-sm">2</div>
                                        <div class="w-0.5 h-full bg-white/30 mt-1"></div>
                                    </div>
                                    <div class="pb-3 flex-1">
                                        <p class="font-semibold text-sm">Pick Service & Time</p>
                                        <p class="text-xs text-white/80">AI prevents scheduling conflicts</p>
                                    </div>
                                </div>
                                <div class="flex gap-3">
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full bg-white text-[#4988C4] flex items-center justify-center font-bold text-sm">3</div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-sm">Instant Confirmation</p>
                                        <p class="text-xs text-white/80">Receive email & SMS updates</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trust Indicators -->
                        <div class="mt-auto pt-6 space-y-2">
                            <div class="flex items-center gap-2 text-xs">
                                <svg class="w-4 h-4 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-white/90">AI-Powered Conflict Prevention</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <svg class="w-4 h-4 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-white/90">HIPAA Compliant & Secure</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <svg class="w-4 h-4 text-emerald-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                <span class="text-white/90">24/7 Support Available</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 sm:p-6 lg:p-8 space-y-5 sm:space-y-6">
                        <div x-show="success" x-transition class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 p-5">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-white text-emerald-500 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-semibold text-emerald-900 dark:text-emerald-100">Appointment booked!</h3>
                                    <p class="text-sm text-emerald-700 dark:text-emerald-200">We will reach out shortly to confirm the details.</p>
                                </div>
                            </div>
                        </div>

                        <div x-show="Object.keys(errors).length > 0" x-cloak class="rounded-2xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 p-5">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-semibold text-red-800 dark:text-red-100">Please review the form</h3>
                                    <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-200 space-y-1">
                                        <template x-for="(fieldErrors, field) in errors" :key="field">
                                            <template x-for="error in fieldErrors" :key="error">
                                                <li x-text="error"></li>
                                            </template>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="submitAppointment" class="space-y-4 sm:space-y-5" x-show="!success">
                            <div class="grid gap-3 sm:gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="text-xs sm:text-sm font-semibold text-zinc-900 dark:text-zinc-50">Full Name</label>
                                    <input 
                                        type="text"
                                        x-model="form.patient_name"
                                        required
                                        placeholder="Enter your full name"
                                        class="mt-1.5 sm:mt-2 w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-2xl border border-zinc-200/80 dark:border-zinc-700/60 bg-white/80 dark:bg-zinc-900/70 text-zinc-900 dark:text-zinc-50 focus:ring-4 focus:ring-[#4988C4]/20 focus:border-[#4988C4] transition-all"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs sm:text-sm font-semibold text-zinc-900 dark:text-zinc-50">Email</label>
                                    <input 
                                        type="email"
                                        x-model="form.email"
                                        required
                                        placeholder="your.email@example.com"
                                        class="mt-1.5 sm:mt-2 w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-2xl border border-zinc-200/80 dark:border-zinc-700/60 bg-white/80 dark:bg-zinc-900/70 text-zinc-900 dark:text-zinc-50 focus:ring-4 focus:ring-[#4988C4]/20 focus:border-[#4988C4] transition-all"
                                    />
                                </div>
                                <div>
                                    <label class="text-xs sm:text-sm font-semibold text-zinc-900 dark:text-zinc-50">Phone</label>
                                    <input 
                                        type="tel"
                                        x-model="form.phone"
                                        required
                                        placeholder="(555) 123-4567"
                                        class="mt-1.5 sm:mt-2 w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-2xl border border-zinc-200/80 dark:border-zinc-700/60 bg-white/80 dark:bg-zinc-900/70 text-zinc-900 dark:text-zinc-50 focus:ring-4 focus:ring-[#4988C4]/20 focus:border-[#4988C4] transition-all"
                                    />
                                </div>
                            </div>

                            <div class="grid gap-3 sm:gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="text-xs sm:text-sm font-semibold text-zinc-900 dark:text-zinc-50">Service</label>
                                    <select 
                                        x-model="form.service_id"
                                        required
                                        class="mt-1.5 sm:mt-2 w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-2xl border border-zinc-200/80 dark:border-zinc-700/60 bg-white/80 dark:bg-zinc-900/70 text-zinc-900 dark:text-zinc-50 focus:ring-4 focus:ring-[#4988C4]/20 focus:border-[#4988C4] transition-all cursor-pointer"
                                    >
                                        <option value="">Select service</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">
                                                {{ $service->service_name }}
                                                @if($service->duration)
                                                    ({{ $service->duration >= 60 ? floor($service->duration / 60) . 'h' : $service->duration . 'm' }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs sm:text-sm font-semibold text-zinc-900 dark:text-zinc-50">Date &amp; Time</label>
                                    <input 
                                        type="datetime-local"
                                        x-model="form.appointment_date"
                                        required
                                        class="mt-1.5 sm:mt-2 w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base rounded-2xl border border-zinc-200/80 dark:border-zinc-700/60 bg-white/80 dark:bg-zinc-900/70 text-zinc-900 dark:text-zinc-50 focus:ring-4 focus:ring-[#4988C4]/20 focus:border-[#4988C4] transition-all"
                                    />
                                </div>
                            </div>

                            <div class="flex flex-col-reverse sm:flex-row sm:flex-wrap items-stretch sm:items-center justify-between gap-3 pt-2">
                                <div class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">
                                    Protected by Dentio availability AI.
                                </div>
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <button 
                                        type="button"
                                        @click="closeModal()"
                                        class="flex-1 sm:flex-none px-4 sm:px-5 py-2.5 sm:py-3 text-sm sm:text-base rounded-2xl border border-zinc-200 dark:border-zinc-700 text-zinc-700 dark:text-zinc-200 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors"
                                    >
                                        Cancel
                                    </button>
                                    <button 
                                        type="submit"
                                        :disabled="loading"
                                        class="flex-1 sm:flex-none px-5 sm:px-6 py-2.5 sm:py-3 text-sm sm:text-base rounded-2xl bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white font-semibold shadow-lg shadow-[#4988C4]/30 flex items-center justify-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed"
                                    >
                                        <svg x-show="loading" class="animate-spin w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span x-text="loading ? 'Booking...' : 'Book Appointment'"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
