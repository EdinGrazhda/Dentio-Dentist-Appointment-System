<div
    @if($eventClickEnabled)
        wire:click.stop="onEventClick('{{ $event['id']  }}')"
    @endif
    class="rounded-lg py-2 px-3 shadow-sm cursor-pointer transition-all hover:shadow-md"
    style="background: linear-gradient(to right, #4988C4, #6BA3D8); color: white;">

    <p class="text-xs font-semibold">
        {{ $event['title'] }}
    </p>
    <p class="mt-1 text-xs opacity-90">
        {{ $event['description'] ?? 'No description' }}
    </p>
</div>
