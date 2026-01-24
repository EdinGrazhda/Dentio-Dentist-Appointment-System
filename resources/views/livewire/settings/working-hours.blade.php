<div class="space-y-6">
    <div>
        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white mb-1">Working Hours</h3>
        <p class="text-sm text-zinc-600 dark:text-zinc-400">Configure your availability and appointment slot duration</p>
    </div>

    @if (session()->has('message'))
        <div class="rounded-lg border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 p-4">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <p class="text-sm text-emerald-700 dark:text-emerald-200">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Start Time -->
            <div>
                <label for="work_start_time" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                    Start Time
                </label>
                <input 
                    type="time" 
                    id="work_start_time"
                    wire:model="work_start_time"
                    class="w-full px-4 py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition"
                />
                @error('work_start_time') 
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span> 
                @enderror
            </div>

            <!-- End Time -->
            <div>
                <label for="work_end_time" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                    End Time
                </label>
                <input 
                    type="time" 
                    id="work_end_time"
                    wire:model="work_end_time"
                    class="w-full px-4 py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition"
                />
                @error('work_end_time') 
                    <span class="text-xs text-red-500 mt-1">{{ $message }}</span> 
                @enderror
            </div>
        </div>

        <!-- Slot Duration -->
        <div>
            <label for="slot_duration" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
                Appointment Slot Duration (minutes)
            </label>
            <select 
                id="slot_duration"
                wire:model="slot_duration"
                class="w-full px-4 py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-[#4988C4] focus:border-transparent transition cursor-pointer"
            >
                <option value="15">15 minutes</option>
                <option value="30">30 minutes</option>
                <option value="45">45 minutes</option>
                <option value="60">1 hour</option>
                <option value="90">1.5 hours</option>
                <option value="120">2 hours</option>
            </select>
            @error('slot_duration') 
                <span class="text-xs text-red-500 mt-1">{{ $message }}</span> 
            @enderror
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                This determines how often new appointment slots are available
            </p>
        </div>

        <!-- Preview -->
        <div class="rounded-lg border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 p-4">
            <h4 class="text-sm font-semibold text-zinc-900 dark:text-white mb-3">Preview</h4>
            <div class="space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Working hours: <strong class="text-zinc-900 dark:text-white">{{ $work_start_time }} - {{ $work_end_time }}</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Slot duration: <strong class="text-zinc-900 dark:text-white">{{ $slot_duration }} minutes</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span>Available slots per hour: <strong class="text-zinc-900 dark:text-white">{{ 60 / $slot_duration }}</strong></span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <button 
                type="submit"
                wire:loading.attr="disabled"
                class="px-6 py-2.5 rounded-lg bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white font-semibold hover:shadow-lg transition flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="save">Save Changes</span>
                <span wire:loading wire:target="save">Saving...</span>
                <svg wire:loading wire:target="save" class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </form>
</div>
