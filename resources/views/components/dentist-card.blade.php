@props(['dentist'])

@php
    $initial = strtoupper(substr($dentist->name, 0, 1));
    $experience = $dentist->years_of_experience ? $dentist->years_of_experience . '+' : '5+';
    $firstName = explode(' ', trim($dentist->name))[0];
@endphp

<div 
    role="button"
    tabindex="0"
    @click="$dispatch('open-appointment-modal', { dentistId: {{ $dentist->id }}, dentistName: '{{ $dentist->name }}' })"
    @keydown.enter.prevent="$dispatch('open-appointment-modal', { dentistId: {{ $dentist->id }}, dentistName: '{{ $dentist->name }}' })"
    @keydown.space.prevent="$dispatch('open-appointment-modal', { dentistId: {{ $dentist->id }}, dentistName: '{{ $dentist->name }}' })"
    class="group relative rounded-3xl border border-zinc-200/70 dark:border-zinc-800 bg-white/90 dark:bg-zinc-900/80 backdrop-blur supports-[backdrop-filter]:backdrop-blur-md p-5 sm:p-6 shadow-sm shadow-[#4988C4]/5 hover:shadow-2xl hover:shadow-[#4988C4]/20 hover:-translate-y-1 transition-all duration-300 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-[#4988C4]/50 overflow-hidden"
>
    <!-- Gradient overlay -->
    <div class="absolute inset-0 bg-gradient-to-br from-[#4988C4]/0 via-transparent to-[#6BA3D8]/0 group-hover:from-[#4988C4]/5 group-hover:to-[#6BA3D8]/5 transition-all duration-500 pointer-events-none"></div>
    
    <div class="relative">
        <!-- Header with avatar and status -->
        <div class="flex items-start gap-3 sm:gap-4 mb-4">
            <div class="relative flex-shrink-0">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl border border-white/60 dark:border-zinc-800 overflow-hidden bg-gradient-to-br from-[#4988C4] to-[#6BA3D8] text-white flex items-center justify-center text-lg sm:text-xl font-semibold shadow-md group-hover:shadow-lg transition-shadow">
                    @if($dentist->image_path)
                        <img src="{{ asset('storage/' . $dentist->image_path) }}" alt="{{ $dentist->name }}" class="w-full h-full object-cover">
                    @else
                        {{ $initial }}
                    @endif
                </div>
                <span class="absolute -bottom-1 -right-1 inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-500 text-white text-[10px] sm:text-[11px] font-semibold shadow-lg">
                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                    Live
                </span>
            </div>
            
            <div class="flex-1 min-w-0">
                <p class="text-[10px] sm:text-xs uppercase tracking-[0.2em] text-zinc-500 dark:text-zinc-400 mb-1">Dentist</p>
                <h3 class="text-base sm:text-lg font-semibold text-zinc-900 dark:text-white truncate" style="font-family: 'Space Grotesk', sans-serif;">Dr. {{ $dentist->name }}</h3>
                <p class="text-xs sm:text-sm text-[#4988C4] dark:text-[#6BA3D8] font-medium truncate">{{ $dentist->specialization ?: 'Comprehensive Care' }}</p>
            </div>
            
            <div class="text-right flex-shrink-0">
                <p class="text-[10px] sm:text-xs text-zinc-500 dark:text-zinc-400">Experience</p>
                <p class="text-base sm:text-lg font-semibold text-zinc-900 dark:text-white whitespace-nowrap">{{ $experience }} yrs</p>
            </div>
        </div>

        <!-- Stats badges -->
        <div class="flex flex-wrap gap-1.5 sm:gap-2 mb-4">
            <span class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-full bg-[#4988C4]/10 dark:bg-[#4988C4]/20 text-[#4988C4] dark:text-[#6BA3D8] font-semibold text-[10px] sm:text-xs">Precision care</span>
            <span class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300 text-[10px] sm:text-xs">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Sedation safe
            </span>
            <span class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-300 text-[10px] sm:text-xs font-semibold">
                98% satisfaction
            </span>
        </div>

        <!-- Availability info -->
        <div class="flex items-center gap-2 mb-4 text-xs sm:text-sm text-zinc-600 dark:text-zinc-400">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="truncate">Next: <strong class="text-zinc-900 dark:text-white">Today · 4:30 PM</strong></span>
        </div>

        <!-- Features -->
        <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-4 text-[10px] sm:text-xs font-medium text-zinc-500 dark:text-zinc-400">
            <div class="flex items-center gap-1.5">
                <span class="inline-flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-zinc-100 dark:bg-zinc-800">
                    <svg class="w-2.5 h-2.5 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
                <span class="hidden sm:inline">Instant booking</span>
                <span class="sm:hidden">Instant</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="inline-flex items-center justify-center w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-zinc-100 dark:bg-zinc-800">
                    <svg class="w-2.5 h-2.5 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/></svg>
                </span>
                <span>24h concierge</span>
            </div>
        </div>

        <!-- CTA Button -->
        <button class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 sm:py-3 rounded-2xl bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white text-xs sm:text-sm font-semibold shadow-lg shadow-[#4988C4]/30 group-hover:shadow-xl group-hover:shadow-[#4988C4]/40 transition-all duration-200">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="truncate">Book with Dr. {{ $firstName }}</span>
        </button>
    </div>

    <!-- Hover border effect -->
    <div class="absolute inset-0 rounded-3xl border border-transparent group-hover:border-[#4988C4]/40 dark:group-hover:border-[#6BA3D8]/40 transition-colors pointer-events-none"></div>
</div>
