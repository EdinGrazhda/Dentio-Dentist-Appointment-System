<x-layouts.app :title="__('Dentists')">
    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <flux:heading size="xl">Dentists Management</flux:heading>
                    <flux:subheading>Manage your dental professionals</flux:subheading>
                </div>
                <flux:button :href="route('dentists.create')" icon="plus" wire:navigate style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                    Add New Dentist
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
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Specialization</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Experience</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-zinc-700 dark:text-zinc-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                            @forelse($dentists as $dentist)
                                <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($dentist->image_path)
                                            <img src="{{ asset('storage/' . $dentist->image_path) }}" 
                                                 alt="{{ $dentist->name }}" 
                                                 class="w-12 h-12 rounded-full object-cover border-2 border-[#4988C4]/20">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#4988C4] to-[#6BA3D8] flex items-center justify-center">
                                                <span class="text-white font-semibold text-lg">{{ substr($dentist->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-zinc-900 dark:text-zinc-50">
                                            {{ $dentist->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <flux:badge color="zinc" style="background-color: #E3F2FD !important; color: #4988C4 !important; border: 1.5px solid #4988C4 !important;">
                                            {{ $dentist->specialization }}
                                        </flux:badge>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-zinc-700 dark:text-zinc-300">
                                            {{ $dentist->years_of_experience }} years
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <flux:button :href="route('dentists.edit', $dentist->id)" icon="pencil" variant="ghost" size="sm" square wire:navigate style="color: #4988C4 !important;" />
                                            
                                            <form action="{{ route('dentists.destroy', $dentist->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this dentist?');"
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
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-16 h-16 text-zinc-300 dark:text-zinc-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                            <flux:heading size="lg" class="mb-2">No dentists found</flux:heading>
                                            <flux:subheading class="mb-4">Get started by adding your first dentist</flux:subheading>
                                            <flux:button :href="route('dentists.create')" icon="plus" wire:navigate class="bg-gradient-to-r from-[#4988C4] to-[#6BA3D8]">
                                                Add Your First Dentist
                                            </flux:button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination for Desktop -->
                @if($dentists->hasPages())
                    <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800">
                        {{ $dentists->links() }}
                    </div>
                @endif
            </div>

            <!-- Mobile Card View (visible on mobile only) -->
            <div class="md:hidden space-y-4">
                @forelse($dentists as $dentist)
                    <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                        <div class="p-5">
                            <!-- Header with Image and Name -->
                            <div class="flex items-start gap-4 mb-4">
                                @if($dentist->image_path)
                                    <img src="{{ asset('storage/' . $dentist->image_path) }}" 
                                         alt="{{ $dentist->name }}" 
                                         class="w-16 h-16 rounded-full object-cover border-2 border-[#4988C4]/20 flex-shrink-0">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-[#4988C4] to-[#6BA3D8] flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-semibold text-xl">{{ substr($dentist->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-zinc-50 mb-1">
                                        {{ $dentist->name }}
                                    </h3>
                                    <flux:badge color="zinc" style="background-color: #E3F2FD !important; color: #4988C4 !important; border: 1.5px solid #4988C4 !important;">
                                        {{ $dentist->specialization }}
                                    </flux:badge>
                                </div>
                            </div>

                            <!-- Experience -->
                            <div class="mb-4 pb-4 border-b border-zinc-200 dark:border-zinc-800">
                                <div class="flex items-center text-sm text-zinc-600 dark:text-zinc-400">
                                    <svg class="w-4 h-4 mr-2 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-medium text-zinc-900 dark:text-zinc-50">{{ $dentist->years_of_experience }} years</span>
                                    <span class="ml-1">of experience</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2">
                                <flux:button :href="route('dentists.edit', $dentist->id)" icon="pencil" variant="filled" class="flex-1" wire:navigate style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                                    Edit
                                </flux:button>
                                
                                <form action="{{ route('dentists.destroy', $dentist->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this dentist?');"
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <flux:heading size="lg" class="mb-2">No dentists found</flux:heading>
                            <flux:subheading class="mb-4">Get started by adding your first dentist</flux:subheading>
                            <flux:button :href="route('dentists.create')" icon="plus" wire:navigate style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                                Add Your First Dentist
                            </flux:button>
                        </div>
                    </div>
                @endforelse

                <!-- Pagination for Mobile -->
                @if($dentists->hasPages())
                    <div class="mt-4">
                        {{ $dentists->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>