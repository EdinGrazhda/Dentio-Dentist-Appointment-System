<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Dentio - Your Trusted Dental Care Partner</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|inter:400,500,600&display=swap" rel="stylesheet" />
        <style>
            [x-cloak] { display: none !important; }
        </style>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
        <body class="antialiased bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 transition-colors duration-300" 
            x-data="{
                mobileMenuOpen: false,
                darkMode: false,
                initializeDarkMode() {
                    const storedMode = localStorage.getItem('darkMode');
                    this.darkMode = storedMode === null
                        ? window.matchMedia('(prefers-color-scheme: dark)').matches
                        : storedMode === 'true';
                    this.applyDarkMode();
                },
                applyDarkMode() {
                    document.documentElement.classList.toggle('dark', this.darkMode);
                },
                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    localStorage.setItem('darkMode', this.darkMode);
                    this.applyDarkMode();
                }
            }"
            x-init="initializeDarkMode()"
            style="font-family: 'Inter', sans-serif;">
        
        <!-- Navigation -->
        <nav class="fixed top-0 w-full bg-white/80 dark:bg-zinc-950/80 backdrop-blur-xl border-b border-zinc-200 dark:border-zinc-800 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-20">
                    <!-- Logo -->
                    <a href="/" class="flex items-center gap-2 lg:gap-3">
                        <img src="{{ asset('images/Dentio.png') }}" alt="Dentio Logo" class="h-10 lg:h-20 w-auto">
                    </a>
                    
                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center gap-1 lg:gap-2">
                        <a href="#services" class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-[#4988C4] dark:hover:text-[#6BA3D8] hover:bg-zinc-100 dark:hover:bg-zinc-900 rounded-lg transition-all duration-200">
                            Services
                        </a>
                        <a href="#features" class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-[#4988C4] dark:hover:text-[#6BA3D8] hover:bg-zinc-100 dark:hover:bg-zinc-900 rounded-lg transition-all duration-200">
                            Features
                        </a>
                        <a href="#contact" class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-[#4988C4] dark:hover:text-[#6BA3D8] hover:bg-zinc-100 dark:hover:bg-zinc-900 rounded-lg transition-all duration-200">
                            Contact
                        </a>
                    </div>

                    <div class="flex items-center gap-2 lg:gap-3">
                        <!-- Dark Mode Toggle -->
                        <button @click="toggleDarkMode()" 
                                class="inline-flex items-center justify-center rounded-lg w-9 h-9 hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-colors duration-200">
                            <svg x-show="!darkMode" class="w-5 h-5 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg x-show="darkMode" x-cloak class="w-5 h-5 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </button>

                        <!-- CTA Button -->
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-lg px-4 lg:px-6 h-9 lg:h-10 bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white text-sm font-semibold shadow-md shadow-[#4988C4]/20 hover:shadow-lg hover:shadow-[#4988C4]/30 transition-all duration-200" wire:navigate>
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg px-4 lg:px-6 h-9 lg:h-10 bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white text-sm font-semibold shadow-md shadow-[#4988C4]/20 hover:shadow-lg hover:shadow-[#4988C4]/30 transition-all duration-200" wire:navigate>
                                    Get Started
                                </a>
                            @endauth
                        @endif

                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden inline-flex items-center justify-center rounded-lg w-9 h-9 hover:bg-zinc-100 dark:hover:bg-zinc-900 transition-colors duration-200">
                            <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg x-show="mobileMenuOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div x-show="mobileMenuOpen" x-cloak x-transition class="md:hidden py-4 space-y-1 border-t border-zinc-200 dark:border-zinc-800">
                    <a href="#services" class="block px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-900 rounded-lg transition-colors">Services</a>
                    <a href="#features" class="block px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-900 rounded-lg transition-colors">Features</a>
                    <a href="#contact" class="block px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-900 rounded-lg transition-colors">Contact</a>
                </div>
            </div>
        </nav>

        </nav>

        <!-- Hero Section -->
        <section class="relative pt-24 sm:pt-32 lg:pt-40 pb-16 sm:pb-20 lg:pb-32 overflow-hidden">
            <!-- Gradient Backgrounds -->
            <div class="absolute inset-0 -z-10">
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#4988C4]/10 dark:bg-[#4988C4]/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-[#6BA3D8]/10 dark:bg-[#6BA3D8]/5 rounded-full blur-3xl"></div>
            </div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center">
                    <!-- Hero Content -->
                    <div class="text-center lg:text-left space-y-6 lg:space-y-8">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#4988C4]/10 dark:bg-[#4988C4]/20 border border-[#4988C4]/20 dark:border-[#4988C4]/30">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#4988C4] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#4988C4]"></span>
                            </span>
                            <span class="text-sm font-medium text-[#4988C4] dark:text-[#6BA3D8]">Modern Dental Care</span>
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-bold tracking-tight" style="font-family: 'Space Grotesk', sans-serif;">
                            Your Smile,
                            <span class="block bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] bg-clip-text text-transparent">Our Priority</span>
                        </h1>

                        <p class="text-base sm:text-lg lg:text-xl text-zinc-600 dark:text-zinc-400 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                            Experience comprehensive dental care with state-of-the-art technology and compassionate professionals dedicated to your oral health.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                            <a href="#contact" class="inline-flex items-center justify-center rounded-lg px-8 h-12 bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white font-semibold shadow-lg shadow-[#4988C4]/30 hover:shadow-xl hover:shadow-[#4988C4]/40 hover:-translate-y-0.5 transition-all duration-200">
                                Book Appointment
                            </a>
                            <a href="#services" class="inline-flex items-center justify-center rounded-lg px-8 h-12 border-2 border-zinc-200 dark:border-zinc-700 hover:border-[#4988C4] dark:hover:border-[#6BA3D8] hover:bg-zinc-50 dark:hover:bg-zinc-900 font-semibold transition-all duration-200">
                                Our Services
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-4 pt-8 max-w-md mx-auto lg:mx-0">
                            <div class="text-center">
                                <div class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] bg-clip-text text-transparent" style="font-family: 'Space Grotesk', sans-serif;">500+</div>
                                <div class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-400">Patients</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] bg-clip-text text-transparent" style="font-family: 'Space Grotesk', sans-serif;">15+</div>
                                <div class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-400">Years</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] bg-clip-text text-transparent" style="font-family: 'Space Grotesk', sans-serif;">98%</div>
                                <div class="text-xs sm:text-sm text-zinc-600 dark:text-zinc-400">Satisfied</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Animated Smiling Tooth -->
                    <div class="flex justify-center lg:justify-end">
                        <div class="relative w-full max-w-md lg:max-w-lg">
                            <style>
                                @keyframes toothBounce {
                                    0%, 100% { transform: translateY(0px) rotate(0deg); }
                                    25% { transform: translateY(-20px) rotate(-3deg); }
                                    50% { transform: translateY(-30px) rotate(0deg); }
                                    75% { transform: translateY(-20px) rotate(3deg); }
                                }
                                @keyframes sparkleShine {
                                    0%, 100% { opacity: 0; transform: scale(0.5); }
                                    50% { opacity: 1; transform: scale(1.2); }
                                }
                                @keyframes eyesBlink {
                                    0%, 90%, 100% { transform: scaleY(1); }
                                    95% { transform: scaleY(0.1); }
                                }
                                .tooth-animated { animation: toothBounce 3s ease-in-out infinite; }
                                .sparkle-animated { animation: sparkleShine 2s ease-in-out infinite; }
                                .eyes-animated { animation: eyesBlink 4s ease-in-out infinite; }
                            </style>
                            
                            <svg class="w-full h-auto" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g class="tooth-animated">
                                    <!-- Tooth Body - Logo style tooth shape with wavy top and roots -->
                                    <path d="M 145 120 
                                             Q 155 105 170 100
                                             Q 185 105 200 110
                                             Q 215 105 230 100
                                             Q 245 105 255 120
                                             L 260 180
                                             Q 258 220 250 250
                                             Q 245 270 235 285
                                             Q 225 295 215 297
                                             L 215 297
                                             Q 210 300 205 297
                                             L 205 297
                                             Q 202 295 200 285
                                             Q 198 295 195 297
                                             L 195 297
                                             Q 190 300 185 297
                                             L 185 297
                                             Q 175 295 165 285
                                             Q 155 270 150 250
                                             Q 142 220 140 180
                                             Z" 
                                          fill="white" stroke="url(#gradient1)" stroke-width="4"/>
                                    
                                    <!-- Wavy Crown Top -->
                                    <path d="M 155 115 Q 170 105 185 115 Q 200 108 215 115 Q 230 105 245 115" 
                                          stroke="url(#gradient1)" stroke-width="3" fill="none" stroke-linecap="round"/>
                                    
                                    <!-- Tooth Highlight -->
                                    <ellipse cx="175" cy="140" rx="25" ry="35" fill="white" opacity="0.3"/>
                                    <path d="M 170 108 Q 180 112 190 108" stroke="white" stroke-width="2" opacity="0.4" stroke-linecap="round"/>
                                    
                                    <!-- Eyes -->
                                    <g class="eyes-animated">
                                        <ellipse cx="170" cy="170" rx="12" ry="15" fill="url(#gradient1)"/>
                                        <ellipse cx="230" cy="170" rx="12" ry="15" fill="url(#gradient1)"/>
                                    </g>
                                    
                                    <!-- Big Smile -->
                                    <path d="M 160 210 Q 200 240 240 210" stroke="url(#gradient1)" stroke-width="6" fill="none" stroke-linecap="round"/>
                                    
                                    <!-- Rosy Cheeks -->
                                    <ellipse cx="150" cy="195" rx="18" ry="12" fill="#FF69B4" opacity="0.3"/>
                                    <ellipse cx="250" cy="195" rx="18" ry="12" fill="#FF69B4" opacity="0.3"/>
                                </g>
                                
                                <!-- Sparkles around tooth -->
                                <g class="sparkle-animated">
                                    <path d="M 120 150 L 125 155 L 130 150 L 125 145 Z" fill="url(#gradient1)"/>
                                    <path d="M 125 142 L 125 158" stroke="url(#gradient1)" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M 117 150 L 133 150" stroke="url(#gradient1)" stroke-width="2" stroke-linecap="round"/>
                                </g>
                                <g class="sparkle-animated" style="animation-delay: 0.7s;">
                                    <path d="M 280 200 L 285 205 L 290 200 L 285 195 Z" fill="url(#gradient1)"/>
                                    <path d="M 285 192 L 285 208" stroke="url(#gradient1)" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M 277 200 L 293 200" stroke="url(#gradient1)" stroke-width="2" stroke-linecap="round"/>
                                </g>
                                <g class="sparkle-animated" style="animation-delay: 1.4s;">
                                    <path d="M 200 80 L 205 85 L 210 80 L 205 75 Z" fill="url(#gradient1)"/>
                                    <path d="M 205 72 L 205 88" stroke="url(#gradient1)" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M 197 80 L 213 80" stroke="url(#gradient1)" stroke-width="2" stroke-linecap="round"/>
                                </g>
                                
                                <!-- Gradient Definition -->
                                <defs>
                                    <linearGradient id="gradient1" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" style="stop-color:#4988C4;stop-opacity:1" />
                                        <stop offset="100%" style="stop-color:#6BA3D8;stop-opacity:1" />
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="relative py-20 sm:py-24 lg:py-32 bg-gradient-to-b from-white via-[#EFF6FF] to-white dark:from-zinc-950 dark:via-zinc-900/80 dark:to-zinc-950 overflow-hidden">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-36 -right-24 w-96 h-96 bg-[#4988C4]/10 dark:bg-[#4988C4]/20 blur-[120px]"></div>
                <div class="absolute bottom-0 left-0 w-[480px] h-[480px] bg-[#6BA3D8]/10 dark:bg-[#6BA3D8]/20 blur-[140px]"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 lg:mb-16 space-y-4">
                    <span class="inline-flex items-center gap-2 px-4 py-1 rounded-full bg-white/60 dark:bg-white/5 text-sm font-semibold text-[#4988C4] border border-[#4988C4]/20 uppercase tracking-widest">
                        Intelligent Care Platform
                    </span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight" style="font-family: 'Space Grotesk', sans-serif;">
                        Why Choose Dentio?
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
                        A SaaS-inspired command center for your smile—real-time availability, curated dentists, and automated scheduling in one beautiful dashboard.
                    </p>
                </div>

                <!-- Stats Row -->
                <div class="grid gap-3 sm:gap-4 grid-cols-2 lg:grid-cols-4">
                    @php
                        $stats = [
                            ['label' => 'Patients protected', 'value' => '12K+', 'trend' => '+18% QoQ'],
                            ['label' => 'Avg. satisfaction', 'value' => '4.97/5', 'trend' => 'Top-rated care'],
                            ['label' => 'Response time', 'value' => '8 min', 'trend' => 'Live concierge'],
                            ['label' => 'Clinics connected', 'value' => '42', 'trend' => 'Growing network'],
                        ];
                    @endphp
                    @foreach($stats as $stat)
                        <div class="rounded-2xl border border-white/60 dark:border-zinc-800 bg-white/90 dark:bg-zinc-900/80 backdrop-blur supports-[backdrop-filter]:backdrop-blur-md p-4 sm:p-6 shadow-sm shadow-[#4988C4]/5 hover:shadow-lg hover:shadow-[#4988C4]/10 transition-all duration-300">
                            <p class="text-xs sm:text-sm uppercase tracking-widest text-zinc-500 dark:text-zinc-400">{{ $stat['label'] }}</p>
                            <p class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white mt-2" style="font-family: 'Space Grotesk', sans-serif;">{{ $stat['value'] }}</p>
                            <p class="text-xs sm:text-sm text-[#4988C4] dark:text-[#6BA3D8] mt-1">{{ $stat['trend'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 sm:mt-12 grid gap-6 lg:gap-10 lg:grid-cols-12 items-start">
                    <!-- Feature narrative -->
                    <div class="lg:col-span-5 space-y-6 sm:space-y-8">
                        <div class="rounded-3xl sm:rounded-[32px] border border-white/50 dark:border-zinc-800 bg-white/90 dark:bg-zinc-900/80 p-6 sm:p-8 shadow-lg shadow-[#4988C4]/5">
                            <h3 class="text-xl sm:text-2xl font-semibold text-zinc-900 dark:text-white mb-3 sm:mb-4" style="font-family: 'Space Grotesk', sans-serif;">Operate your dental experience like a product team.</h3>
                            <p class="text-sm sm:text-base text-zinc-600 dark:text-zinc-400">
                                Dentio layers automation, human expertise, and collaborative tooling so every appointment feels intentional. Think patient success dashboards, not waiting rooms.
                            </p>
                            <div class="mt-6 space-y-4">
                                @foreach([
                                    ['title' => 'Unified scheduling cloud', 'copy' => 'Sync dentist calendars, service durations, and availability windows in real time.'],
                                    ['title' => 'Experience-grade workflows', 'copy' => 'Trigger reminders, approvals, and follow-ups the moment a slot is reserved.'],
                                    ['title' => 'Insightful telemetry', 'copy' => 'Monitor utilization, satisfaction, and wait times from a single control pane.'],
                                ] as $feature)
                                    <div class="flex gap-4">
                                        <div class="w-10 h-10 rounded-2xl bg-[#4988C4]/10 dark:bg-[#4988C4]/20 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-[#4988C4]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-base font-semibold text-zinc-900 dark:text-white">{{ $feature['title'] }}</p>
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ $feature['copy'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Pro Tip Card -->
                        <div class="rounded-2xl border border-dashed border-[#4988C4]/30 bg-white/70 dark:bg-zinc-900/60 p-6">
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#4988C4] to-[#6BA3D8] flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-[#4988C4] dark:text-[#6BA3D8]">Pro tip</p>
                                    <p class="text-base font-semibold text-zinc-900 dark:text-white mt-1">Choose a dentist card to launch the booking OS instantly.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Trust Metrics Grid -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="rounded-2xl border border-white/60 dark:border-zinc-800 bg-gradient-to-br from-white to-[#EFF6FF] dark:from-zinc-900 dark:to-zinc-900/50 p-5 shadow-sm">
                                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold text-zinc-900 dark:text-white">99.8%</p>
                                <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-1">Uptime reliability</p>
                            </div>
                            <div class="rounded-2xl border border-white/60 dark:border-zinc-800 bg-gradient-to-br from-white to-[#EFF6FF] dark:from-zinc-900 dark:to-zinc-900/50 p-5 shadow-sm">
                                <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                </div>
                                <p class="text-2xl font-bold text-zinc-900 dark:text-white">24/7</p>
                                <p class="text-xs text-zinc-600 dark:text-zinc-400 mt-1">Support available</p>
                            </div>
                        </div>

                        <!-- Key Features List -->
                        <div class="rounded-2xl border border-white/60 dark:border-zinc-800 bg-white/70 dark:bg-zinc-900/60 p-6 space-y-3">
                            <h4 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wider">Platform Benefits</h4>
                            <div class="space-y-2">
                                @foreach([
                                    'No-code conflict prevention engine',
                                    'HIPAA-compliant data encryption',
                                    'SMS & email automation flows',
                                    'Real-time calendar synchronization',
                                    'Advanced analytics dashboard'
                                ] as $benefit)
                                    <div class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300">
                                        <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $benefit }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- CTA Banner -->
                        <div class="rounded-2xl border border-white/60 dark:border-zinc-800 bg-gradient-to-br from-[#4988C4] to-[#6BA3D8] p-6 text-white shadow-xl shadow-[#4988C4]/20">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                        <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold opacity-90">Ready to experience modern dental care?</p>
                                    <p class="text-base font-bold mt-1">Book in less than 60 seconds</p>
                                    <p class="text-xs opacity-80 mt-2">Join 12,000+ patients who trust Dentio for smarter scheduling</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dentist cards embedded -->
                    <div class="lg:col-span-7">
                        <div class="rounded-3xl sm:rounded-[36px] border border-white/60 dark:border-zinc-800 bg-white/95 dark:bg-zinc-900/90 backdrop-blur-xl shadow-2xl shadow-[#4988C4]/10 p-5 sm:p-6 lg:p-8">
                            <div class="flex flex-wrap items-center justify-between gap-3 sm:gap-4">
                                <div>
                                    <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">Live roster</p>
                                    <h3 class="text-xl sm:text-2xl font-semibold text-zinc-900 dark:text-white" style="font-family: 'Space Grotesk', sans-serif;">Available Dentists</h3>
                                </div>
                                <div class="flex items-center gap-2 text-xs sm:text-sm font-medium text-[#4988C4] dark:text-[#6BA3D8]">
                                    <span class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-full bg-[#4988C4]/10 dark:bg-[#4988C4]/20 text-xs sm:text-sm">Real-time sync</span>
                                    <span class="hidden sm:inline text-zinc-500 dark:text-zinc-400">Tap a card to book</span>
                                </div>
                            </div>
                            <div class="mt-4 sm:mt-6 grid gap-4">
                                @forelse($dentists as $dentist)
                                    <x-dentist-card :dentist="$dentist" />
                                @empty
                                    <div class="rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 p-6 text-center text-zinc-500 dark:text-zinc-400">
                                        No dentists available right now. Check back soon!
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="py-16 sm:py-20 lg:py-32 bg-zinc-50 dark:bg-zinc-900/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 lg:mb-16">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight mb-4" style="font-family: 'Space Grotesk', sans-serif;">
                        Our Services
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
                        Comprehensive dental solutions for all your oral health needs.
                    </p>
                </div>
                
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                    <!-- Service Cards -->
                    <div class="group rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 hover:border-[#4988C4] dark:hover:border-[#6BA3D8] hover:shadow-lg transition-all duration-300 cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#4988C4]/10 dark:bg-[#4988C4]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#4988C4] transition-colors">
                                <svg class="w-5 h-5 text-[#4988C4] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M9 11L12 14L22 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M21 12V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2 text-[#4988C4] dark:text-[#6BA3D8]" style="font-family: 'Space Grotesk', sans-serif;">General Dentistry</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Routine checkups, cleanings, and preventive care to maintain your oral health.</p>
                            </div>
                        </div>
                    </div>

                    <div class="group rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 hover:border-[#4988C4] dark:hover:border-[#6BA3D8] hover:shadow-lg transition-all duration-300 cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#4988C4]/10 dark:bg-[#4988C4]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#4988C4] transition-colors">
                                <svg class="w-5 h-5 text-[#4988C4] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M12 20H21" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M16.5 3.5C16.8978 3.10217 17.4374 2.87868 18 2.87868C18.5626 2.87868 19.1022 3.10217 19.5 3.5C19.8978 3.89783 20.1213 4.43739 20.1213 5C20.1213 5.56261 19.8978 6.10217 19.5 6.5L7 19L3 20L4 16L16.5 3.5Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2 text-[#4988C4] dark:text-[#6BA3D8]" style="font-family: 'Space Grotesk', sans-serif;">Cosmetic Dentistry</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Teeth whitening, veneers, and smile makeovers to enhance your appearance.</p>
                            </div>
                        </div>
                    </div>

                    <div class="group rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 hover:border-[#4988C4] dark:hover:border-[#6BA3D8] hover:shadow-lg transition-all duration-300 cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#4988C4]/10 dark:bg-[#4988C4]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#4988C4] transition-colors">
                                <svg class="w-5 h-5 text-[#4988C4] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2 text-[#4988C4] dark:text-[#6BA3D8]" style="font-family: 'Space Grotesk', sans-serif;">Orthodontics</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Braces and aligners to straighten teeth and correct bite issues.</p>
                            </div>
                        </div>
                    </div>

                    <div class="group rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 hover:border-[#4988C4] dark:hover:border-[#6BA3D8] hover:shadow-lg transition-all duration-300 cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#4988C4]/10 dark:bg-[#4988C4]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#4988C4] transition-colors">
                                <svg class="w-5 h-5 text-[#4988C4] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                    <path d="M12 6V12L16 14" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2 text-[#4988C4] dark:text-[#6BA3D8]" style="font-family: 'Space Grotesk', sans-serif;">Dental Implants</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Permanent tooth replacement solutions that look and feel natural.</p>
                            </div>
                        </div>
                    </div>

                    <div class="group rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 hover:border-[#4988C4] dark:hover:border-[#6BA3D8] hover:shadow-lg transition-all duration-300 cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#4988C4]/10 dark:bg-[#4988C4]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#4988C4] transition-colors">
                                <svg class="w-5 h-5 text-[#4988C4] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke-width="2"/>
                                    <path d="M8 12H16M12 8V16" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2 text-[#4988C4] dark:text-[#6BA3D8]" style="font-family: 'Space Grotesk', sans-serif;">Root Canal Treatment</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Pain-free procedures to save infected teeth and restore function.</p>
                            </div>
                        </div>
                    </div>

                    <div class="group rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 hover:border-[#4988C4] dark:hover:border-[#6BA3D8] hover:shadow-lg transition-all duration-300 cursor-pointer">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-lg bg-[#4988C4]/10 dark:bg-[#4988C4]/20 flex items-center justify-center flex-shrink-0 group-hover:bg-[#4988C4] transition-colors">
                                <svg class="w-5 h-5 text-[#4988C4] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M13 2L3 14H12L11 22L21 10H12L13 2Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold mb-2 text-[#4988C4] dark:text-[#6BA3D8]" style="font-family: 'Space Grotesk', sans-serif;">Emergency Care</h3>
                                <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Immediate attention for dental emergencies and urgent pain relief.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- CTA Section -->
        <section id="contact" class="relative py-16 sm:py-20 lg:py-32 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-[#4988C4] to-[#6BA3D8]"></div>
            <div class="absolute inset-0">
                <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
            </div>
            
            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6 tracking-tight" style="font-family: 'Space Grotesk', sans-serif;">
                    Ready to Transform Your Smile?
                </h2>
                <p class="text-lg lg:text-xl text-white/90 mb-10 leading-relaxed max-w-2xl mx-auto">
                    Schedule your appointment today and take the first step towards perfect dental health.
                </p>
                
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-lg px-10 h-14 bg-white text-[#4988C4] font-semibold text-lg shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-200">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg px-10 h-14 bg-white text-[#4988C4] font-semibold text-lg shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-200">
                            Book an Appointment
                        </a>
                    @endauth
                @else
                    <a href="#" class="inline-flex items-center justify-center rounded-lg px-10 h-14 bg-white text-[#4988C4] font-semibold text-lg shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-200">
                        Book an Appointment
                    </a>
                @endif
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 py-12 lg:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 mb-8">
                    <div>
                        <h4 class="font-bold text-lg mb-4" style="font-family: 'Space Grotesk', sans-serif;">
                            <span class="bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] bg-clip-text text-transparent">Dentio</span>
                        </h4>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Your trusted partner in dental care, committed to providing exceptional service and beautiful smiles.</p>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-4 text-zinc-900 dark:text-white" style="font-family: 'Space Grotesk', sans-serif;">Quick Links</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#services" class="text-zinc-600 dark:text-zinc-400 hover:text-[#4988C4] dark:hover:text-[#6BA3D8] transition-colors">Services</a></li>
                            <li><a href="#features" class="text-zinc-600 dark:text-zinc-400 hover:text-[#4988C4] dark:hover:text-[#6BA3D8] transition-colors">Features</a></li>
                            <li><a href="#contact" class="text-zinc-600 dark:text-zinc-400 hover:text-[#4988C4] dark:hover:text-[#6BA3D8] transition-colors">Contact</a></li>
                            <li><a href="#" class="text-zinc-600 dark:text-zinc-400 hover:text-[#4988C4] dark:hover:text-[#6BA3D8] transition-colors">Privacy Policy</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-4 text-zinc-900 dark:text-white" style="font-family: 'Space Grotesk', sans-serif;">Contact Info</h4>
                        <ul class="space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                            <li>📞 (555) 123-4567</li>
                            <li>📧 info@dentio.com</li>
                            <li>📍 123 Dental Street, City</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-4 text-zinc-900 dark:text-white" style="font-family: 'Space Grotesk', sans-serif;">Hours</h4>
                        <ul class="space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                            <li>Monday - Friday: 8am - 6pm</li>
                            <li>Saturday: 9am - 3pm</li>
                            <li>Sunday: Closed</li>
                        </ul>
                    </div>
                </div>
                
                <div class="pt-8 border-t border-zinc-200 dark:border-zinc-800 text-center">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">&copy; 2026 Dentio. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Appointment Booking Modal -->
        <x-appointment-modal :services="$services" />
    </body>
</html>
