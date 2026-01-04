<x-layouts.app :title="__('Appointments')">
    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <flux:heading size="xl">Appointments Management</flux:heading>
                    <flux:subheading>Manage your patient appointments</flux:subheading>
                </div>
                <flux:button :href="route('appointments.create')" icon="plus" wire:navigate style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                    Add New Appointment
                </flux:button>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Desktop Table View (hidden on mobile) -->
            <div class="hidden md:block bg-white dark:bg-zinc-900 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-700">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Patient Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Dentist</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Duration</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                            @forelse($appointments as $appointment)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-zinc-900 dark:text-zinc-50">
                                            {{ $appointment->patient_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-zinc-700 dark:text-zinc-300">
                                            {{ $appointment->dentist->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-zinc-700 dark:text-zinc-300">
                                            {{ $appointment->service->service_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-zinc-700 dark:text-zinc-300">
                                            {{ $appointment->appointment_date->format('M d, Y - h:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-zinc-700 dark:text-zinc-300">
                                            @if($appointment->service->duration)
                                                @if($appointment->service->duration >= 60)
                                                    {{ floor($appointment->service->duration / 60) }}h 
                                                    @if($appointment->service->duration % 60 > 0)
                                                        {{ $appointment->service->duration % 60 }}m
                                                    @endif
                                                @else
                                                    {{ $appointment->service->duration }}m
                                                @endif
                                            @else
                                                <span class="text-zinc-400 dark:text-zinc-600">-</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <flux:badge style="background-color: {{ $appointment->status->backgroundColor() }}; color: {{ $appointment->status->color() }}">
                                            {{ $appointment->status->label() }}
                                        </flux:badge>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <flux:button :href="route('appointments.edit', $appointment)" icon="pencil" variant="ghost" size="sm" square wire:navigate style="color: #4988C4 !important;" />
                                            
                                            <form action="{{ route('appointments.destroy', $appointment) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this appointment?');"
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <flux:button type="submit" icon="trash" variant="ghost" size="sm" square style="color: #4988C4 !important;" />
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-zinc-300 dark:text-zinc-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <flux:heading size="lg" class="mb-2">No appointments found</flux:heading>
                                            <flux:subheading class="mb-4">Get started by scheduling your first appointment</flux:subheading>
                                            <flux:button :href="route('appointments.create')" icon="plus" wire:navigate style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                                                Add Your First Appointment
                                            </flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination for Desktop -->
                @if($appointments->hasPages())
                    <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>

            <!-- Mobile Card View (visible on mobile only) -->
            <div class="md:hidden space-y-4">
                @forelse($appointments as $appointment)
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                        <div class="p-5">
                            <!-- Patient Name and Status -->
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
                                        {{ $appointment->patient_name }}
                                    </h3>
                                    <flux:badge style="background-color: {{ $appointment->status->backgroundColor() }}; color: {{ $appointment->status->color() }}">
                                        {{ $appointment->status->label() }}
                                    </flux:badge>
                                </div>
                            </div>

                            <!-- Appointment Details -->
                            <div class="mb-4 pb-4 border-b border-zinc-200 dark:border-zinc-800 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">Dentist:</span>
                                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-50">{{ $appointment->dentist->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">Service:</span>
                                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-50">{{ $appointment->service->service_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-zinc-500 dark:text-zinc-400">Date & Time:</span>
                                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-50">{{ $appointment->appointment_date->format('M d, Y - h:i A') }}</span>
                                </div>
                                @if($appointment->service->duration)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-zinc-500 dark:text-zinc-400">Duration:</span>
                                        <span class="text-sm font-medium text-zinc-900 dark:text-zinc-50">
                                            @if($appointment->service->duration >= 60)
                                                {{ floor($appointment->service->duration / 60) }}h 
                                                @if($appointment->service->duration % 60 > 0)
                                                    {{ $appointment->service->duration % 60 }}m
                                                @endif
                                            @else
                                                {{ $appointment->service->duration }} min
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <flux:button :href="route('appointments.edit', $appointment)" icon="pencil" variant="filled" class="flex-1" wire:navigate style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                                    Edit
                                </flux:button>
                                
                                <form action="{{ route('appointments.destroy', $appointment) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this appointment?');"
                                      class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <flux:button type="submit" icon="trash" variant="outline" class="w-full" style="border-color: #4988C4 !important; color: #4988C4 !important;">
                                        Delete
                                    </flux:button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-800 p-8">
                        <div class="flex flex-col items-center justify-center text-center">
                            <svg class="w-16 h-16 text-zinc-300 dark:text-zinc-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <flux:heading size="lg" class="mb-2">No appointments found</flux:heading>
                            <flux:subheading class="mb-4">Get started by scheduling your first appointment</flux:subheading>
                            <flux:button :href="route('appointments.create')" icon="plus" wire:navigate style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                                Add Your First Appointment
                            </flux:button>
                        </div>
                    </div>
                @endforelse

                <!-- Pagination for Mobile -->
                @if($appointments->hasPages())
                    <div class="mt-4">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
