<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <flux:radio.group 
            x-data="{ 
                init() {
                    // Ensure default is always 'light' for new users
                    if (!localStorage.getItem('flux.appearance')) {
                        localStorage.setItem('flux.appearance', 'light');
                        // Force Flux to recognize the light mode
                        if (window.Flux && window.Flux.appearance) {
                            window.Flux.appearance = 'light';
                        }
                    }
                    
                    // Watch for appearance changes and save them
                    this.$watch('$flux.appearance', value => {
                        localStorage.setItem('flux.appearance', value);
                        
                        // Apply theme immediately
                        if (value === 'dark' || (value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    });
                }
            }" 
            variant="segmented" 
            x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
