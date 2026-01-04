<div class="p-6 border-b border-zinc-200 dark:border-zinc-700">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Previous Month Button -->
        <button 
            wire:click="goToPreviousMonth" 
            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-[#4988C4] dark:hover:text-[#6BA3D8] transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Previous</span>
        </button>

        <!-- Current Month/Year Display -->
        <div class="flex items-center gap-3">
            <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-50">
                {{ $startsAt->format('F Y') }}
            </h3>
            
            <!-- Today Button -->
            <button 
                wire:click="goToCurrentMonth" 
                class="px-4 py-2 text-sm font-medium rounded-lg border-2 border-[#4988C4] text-[#4988C4] hover:bg-[#4988C4] hover:text-white transition-all">
                Today
            </button>
        </div>

        <!-- Next Month Button -->
        <button 
            wire:click="goToNextMonth" 
            class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-[#4988C4] dark:hover:text-[#6BA3D8] transition-colors">
            <span>Next</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
</div>
