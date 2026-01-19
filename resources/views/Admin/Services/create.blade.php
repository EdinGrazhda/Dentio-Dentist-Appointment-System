<x-layouts.app :title="__('Add New Service')">
    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <flux:button :href="route('services.index')" icon="arrow-left" variant="ghost" size="sm" square wire:navigate />
                    <flux:heading size="xl">Add New Service</flux:heading>
                </div>
                <flux:subheading class="ml-11">Fill in the information below to add a new dental service</flux:subheading>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden p-6 sm:p-8">
                <form action="{{ route('services.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Service Name Field -->
                    <flux:input
                        name="service_name"
                        label="Service Name"
                        :value="old('service_name')"
                        type="text"
                        required
                        placeholder="e.g., Teeth Cleaning, Root Canal"
                    />

                    <!-- Description Field -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
                            Description
                        </label>
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="4"
                            required
                            placeholder="Describe the service in detail..."
                            class="w-full px-4 py-3 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50 placeholder-zinc-500 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition-colors"
                        >{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price Field -->
                    <flux:input
                        name="price"
                        label="Price"
                        :value="old('price')"
                        type="number"
                        step="0.01"
                        min="0"
                        required
                        placeholder="99.99"
                    />

                    <!-- Duration Field -->
                    <flux:input
                        name="duration"
                        label="Duration (in minutes)"
                        :value="old('duration')"
                        type="number"
                        min="1"
                        required
                        placeholder="e.g., 30 for 30 minutes, 120 for 2 hours"
                    />

                    <!-- Action Buttons -->
                    <flux:separator />
                    <div class="flex items-center justify-end gap-3">
                        <flux:button :href="route('services.index')" variant="ghost" wire:navigate>
                            Cancel
                        </flux:button>
                        <flux:button type="submit" variant="primary" icon="check" style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white !important;">
                            Create Service
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
