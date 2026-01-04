<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Dentio - Your Trusted Dental Care Partner</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:400,500,600,700|inter:400,500,600&display=swap" rel="stylesheet" />
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
        <body class="antialiased bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 transition-colors duration-300" 
            x-data="{ mobileMenuOpen: false }"
            style="font-family: 'Inter', sans-serif;">
        
        <!-- Navigation -->
        <nav class="fixed top-0 w-full bg-white/80 dark:bg-zinc-950/80 backdrop-blur-xl border-b border-zinc-200 dark:border-zinc-800 z-50 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-20">
                    <!-- Logo -->
                    <a href="/" class="text-2xl lg:text-3xl font-bold tracking-tight" style="font-family: 'Space Grotesk', sans-serif;">
                        <span class="bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] bg-clip-text text-transparent">Dentio</span>
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
                        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
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
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-lg px-4 lg:px-6 h-9 lg:h-10 bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white text-sm font-semibold shadow-md shadow-[#4988C4]/20 hover:shadow-lg hover:shadow-[#4988C4]/30 transition-all duration-200">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg px-4 lg:px-6 h-9 lg:h-10 bg-gradient-to-r from-[#4988C4] to-[#6BA3D8] text-white text-sm font-semibold shadow-md shadow-[#4988C4]/20 hover:shadow-lg hover:shadow-[#4988C4]/30 transition-all duration-200">
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
                    
                    <!-- Hero Image -->
                    <div class="flex justify-center lg:justify-end">
                        <div class="relative w-full max-w-md lg:max-w-lg">
                            <div class="aspect-square rounded-3xl bg-gradient-to-br from-[#4988C4] to-[#6BA3D8] shadow-2xl shadow-[#4988C4]/20 flex items-center justify-center overflow-hidden">
                                <!-- Decorative Elements -->
                                <div class="absolute inset-0">
                                    <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                                </div>
                                
                                <!-- Smile Icon -->
                                <svg class="w-48 h-48 lg:w-64 lg:h-64 relative z-10" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="100" cy="100" r="80" stroke="white" stroke-width="4" stroke-linecap="round"/>
                                    <path d="M60 90C60 90 75 105 100 105C125 105 140 90 140 90" stroke="white" stroke-width="5" stroke-linecap="round"/>
                                    <circle cx="70" cy="70" r="5" fill="white"/>
                                    <circle cx="130" cy="70" r="5" fill="white"/>
                                    <path d="M70 115C70 115 80 130 100 130C120 130 130 115 130 115" stroke="white" stroke-width="3" stroke-linecap="round" opacity="0.5"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-16 sm:py-20 lg:py-32">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 lg:mb-16">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight mb-4" style="font-family: 'Space Grotesk', sans-serif;">
                        Why Choose Dentio?
                    </h2>
                    <p class="text-lg text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">
                        Experience exceptional dental care with our modern approach and expert team.
                    </p>
                </div>
                
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                    <!-- Feature Card 1 -->
                    <div class="group relative rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 hover:border-[#4988C4] dark:hover:border-[#6BA3D8] hover:shadow-lg dark:hover:shadow-2xl dark:hover:shadow-[#4988C4]/10 transition-all duration-300">
                        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#4988C4] to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="w-12 h-12 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4 group-hover:bg-gradient-to-br group-hover:from-[#4988C4] group-hover:to-[#6BA3D8] transition-all duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6 text-[#4988C4] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                <path d="M12 2C14.5 4 16 7.5 16 12C16 16.5 14.5 20 12 22M12 2C9.5 4 8 7.5 8 12C8 16.5 9.5 20 12 22M2 12H22" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        
                        <h3 class="text-lg font-semibold mb-2" style="font-family: 'Space Grotesk', sans-serif;">Expert Team</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Highly qualified dentists with years of experience in modern dental care.</p>
                    </div>

                    <!-- Feature Card 2 -->
                    <div class="group relative rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 hover:border-[#4988C4] dark:hover:border-[#6BA3D8] hover:shadow-lg dark:hover:shadow-2xl dark:hover:shadow-[#4988C4]/10 transition-all duration-300">
                        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#4988C4] to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="w-12 h-12 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4 group-hover:bg-gradient-to-br group-hover:from-[#4988C4] group-hover:to-[#6BA3D8] transition-all duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6 text-[#4988C4] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M12 2L15 8L22 9L17 14L18 21L12 18L6 21L7 14L2 9L9 8L12 2Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="11" r="3" stroke-width="2"/>
                            </svg>
                        </div>
                        
                        <h3 class="text-lg font-semibold mb-2" style="font-family: 'Space Grotesk', sans-serif;">Advanced Technology</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">State-of-the-art equipment for accurate diagnosis and comfortable treatment.</p>
                    </div>

                    <!-- Feature Card 3 -->
                    <div class="group relative rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 hover:border-[#4988C4] dark:hover:border-[#6BA3D8] hover:shadow-lg dark:hover:shadow-2xl dark:hover:shadow-[#4988C4]/10 transition-all duration-300">
                        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#4988C4] to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="w-12 h-12 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4 group-hover:bg-gradient-to-br group-hover:from-[#4988C4] group-hover:to-[#6BA3D8] transition-all duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6 text-[#4988C4] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="12" cy="7" r="4" stroke-width="2"/>
                                <path d="M12 11V15M10 13H14" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        
                        <h3 class="text-lg font-semibold mb-2" style="font-family: 'Space Grotesk', sans-serif;">Quality Care</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Personalized treatment plans tailored to your unique dental needs.</p>
                    </div>

                    <!-- Feature Card 4 -->
                    <div class="group relative rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 hover:border-[#4988C4] dark:hover:border-[#6BA3D8] hover:shadow-lg dark:hover:shadow-2xl dark:hover:shadow-[#4988C4]/10 transition-all duration-300">
                        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#4988C4] to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="w-12 h-12 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-4 group-hover:bg-gradient-to-br group-hover:from-[#4988C4] group-hover:to-[#6BA3D8] transition-all duration-300 group-hover:scale-110">
                            <svg class="w-6 h-6 text-[#4988C4] group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/>
                                <path d="M16 2V6M8 2V6M3 10H21" stroke-width="2" stroke-linecap="round"/>
                                <path d="M8 14L10 16L12.5 13.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        
                        <h3 class="text-lg font-semibold mb-2" style="font-family: 'Space Grotesk', sans-serif;">Flexible Scheduling</h3>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">Convenient appointment times that fit your busy lifestyle.</p>
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
    </body>
</html>
