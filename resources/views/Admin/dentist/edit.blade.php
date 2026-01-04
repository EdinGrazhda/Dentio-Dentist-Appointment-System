<x-layouts.app :title="__('Edit Dentist')">
    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <flux:button :href="route('dentists.index')" icon="arrow-left" variant="ghost" size="sm" square wire:navigate />
                    <flux:heading size="xl">Edit Dentist</flux:heading>
                </div>
                <flux:subheading class="ml-11">Update the information for {{ $dentist->name }}</flux:subheading>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-800 overflow-hidden p-6 sm:p-8">
                <form action="{{ route('dentists.update', $dentist->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Name Field -->
                    <flux:input
                        name="name"
                        label="Full Name"
                        :value="old('name', $dentist->name)"
                        type="text"
                        required
                        placeholder="Dr. John Smith"
                    />

                    <!-- Specialization Field -->
                    <flux:input
                        name="specialization"
                        label="Specialization"
                        :value="old('specialization', $dentist->specialization)"
                        type="text"
                        required
                        placeholder="e.g., Orthodontist, General Dentistry"
                    />

                    <!-- Years of Experience Field -->
                    <flux:input
                        name="years_of_experience"
                        label="Years of Experience"
                        :value="old('years_of_experience', $dentist->years_of_experience)"
                        type="number"
                        required
                        min="0"
                        placeholder="5"
                    />

                    <!-- Current Image Display -->
                    @if($dentist->image_path)
                        <div>
                            <label class="block text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
                                Current Image
                            </label>
                            <img src="{{ asset('storage/' . $dentist->image_path) }}" 
                                 alt="{{ $dentist->name }}" 
                                 class="w-32 h-32 object-cover rounded-lg border-2 border-[#4988C4]/20">
                        </div>
                    @endif

                    <!-- Image Upload Field -->
                    <div>
                        <label for="image" class="block text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-2">
                            {{ $dentist->image_path ? 'Change Profile Image' : 'Upload Profile Image' }}
                        </label>
                        <div class="mt-2">
                            <input type="file" 
                                   name="image" 
                                   id="image" 
                                   accept="image/*"
                                   onchange="previewImage(event)"
                                   class="block w-full text-sm text-zinc-600 dark:text-zinc-400
                                          file:mr-4 file:py-2.5 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-gradient-to-r file:from-[#4988C4] file:to-[#6BA3D8] file:text-white
                                          file:cursor-pointer file:transition-all file:duration-200
                                          hover:file:shadow-lg hover:file:shadow-[#4988C4]/30
                                          cursor-pointer">
                        </div>
                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-4 hidden">
                            <p class="text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-2">New Image Preview</p>
                            <img id="preview" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-[#4988C4]/20">
                        </div>
                        @error('image')
                            <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1.5 text-xs text-zinc-500 dark:text-zinc-500">JPG, PNG, GIF up to 2MB</p>
                    </div>

                    <!-- Action Buttons -->
                    <flux:separator />
                    <div class="flex items-center justify-end gap-3">
                        <flux:button :href="route('dentists.index')" variant="ghost" wire:navigate>
                            Cancel
                        </flux:button>
                        <flux:button type="submit" variant="primary" icon="check" class="bg-gradient-to-r from-[#4988C4] to-[#6BA3D8]">
                            Update Dentist
                        </flux:button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            const imagePreview = document.getElementById('imagePreview');
            const preview = document.getElementById('preview');
            
            reader.onload = function() {
                preview.src = reader.result;
                imagePreview.classList.remove('hidden');
            }
            
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        // Auto-refresh CSRF token every 30 minutes to prevent 419 errors
        setInterval(function() {
            fetch(window.location.href, {
                method: 'GET',
                credentials: 'same-origin'
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newToken = doc.querySelector('input[name="_token"]')?.value;
                
                if (newToken) {
                    document.querySelector('input[name="_token"]')?.setAttribute('value', newToken);
                }
            })
            .catch(error => console.error('CSRF refresh error:', error));
        }, 30 * 60 * 1000); // 30 minutes
    </script>
</x-layouts.app>