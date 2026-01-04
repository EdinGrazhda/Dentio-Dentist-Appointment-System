<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<script>
    // Force light mode as default for all users
    (function() {
        // Always initialize with light mode if not set
        if (!localStorage.getItem('flux.appearance')) {
            localStorage.setItem('flux.appearance', 'light');
        }
        
            // Get current appearance setting
        const appearance = localStorage.getItem('flux.appearance') || 'light';

            // Expose to Flux if it checks a global
            try {
                window.__fluxAppearance = appearance;
            } catch (e) {}

            // Only apply dark mode if explicitly set to 'dark' or 'system' with dark preference
            if (appearance === 'dark' || (appearance === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                // Ensure dark class is removed (force light mode)
                document.documentElement.classList.remove('dark');
            }
    })();
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])

@fluxAppearance
