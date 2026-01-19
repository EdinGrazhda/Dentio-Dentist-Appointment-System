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
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
        class="fixed inset-0 flex items-center justify-center p-4 z-50"
        @click.self="closeModal()"
    >
        <div class="w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="relative bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl shadow-2xl">
                <!-- Close Button -->
                <button 
                    type="button"
                    @click="closeModal()"
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
                            <span x-text="selectedDentistName ? 'Dr. ' + selectedDentistName : 'No dentist selected'"></span>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <div x-show="success" x-transition class="mb-6 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-emerald-900 dark:text-emerald-100">Appointment Booked!</h3>
                                <p class="text-xs text-emerald-700 dark:text-emerald-200">We'll confirm the details shortly.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    <div x-show="Object.keys(errors).length > 0" x-cloak class="mb-6 rounded-xl border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20 p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-xs font-semibold text-red-800 dark:text-red-100 mb-1">Please fix the errors below</h3>
                                <ul class="list-disc list-inside text-xs text-red-700 dark:text-red-200 space-y-0.5">
                                    <template x-for="(fieldErrors, field) in errors" :key="field">
                                        <template x-for="error in fieldErrors" :key="error">
                                            <li x-text="error"></li>
                                        </template>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submitAppointment" class="space-y-4" x-show="!success">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Full Name</label>
                            <input 
                                type="text"
                                x-model="form.patient_name"
                                required
                                placeholder="Enter your name"
                                class="w-full px-3 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition"
                            />
                        </div>

                        <!-- Email & Phone -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Email</label>
                                <input 
                                    type="email"
                                    x-model="form.email"
                                    required
                                    placeholder="email@example.com"
                                    class="w-full px-3 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Phone</label>
                                <input 
                                    type="tel"
                                    x-model="form.phone"
                                    required
                                    placeholder="(555) 123-4567"
                                    class="w-full px-3 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition"
                                />
                            </div>
                        </div>

                        <!-- Service & Date -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Service</label>
                                <select 
                                    x-model="form.service_id"
                                    required
                                    class="w-full px-3 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition cursor-pointer"
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
                                <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Date & Time</label>
                                <input 
                                    type="datetime-local"
                                    x-model="form.appointment_date"
                                    required
                                    class="w-full px-3 py-2.5 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition"
                                />
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                            <button 
                                type="button"
                                @click="closeModal()"
                                class="px-4 py-2 text-sm font-medium rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                :disabled="loading"
                                class="px-5 py-2 text-sm font-semibold rounded-lg bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white hover:shadow-lg transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg x-show="loading" class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="loading ? 'Booking...' : 'Book Appointment'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
