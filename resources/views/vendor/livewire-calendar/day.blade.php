
<div
    ondragenter="onLivewireCalendarEventDragEnter(event, '{{ $componentId }}', '{{ $day }}', '{{ $dragAndDropClasses }}');"
    ondragleave="onLivewireCalendarEventDragLeave(event, '{{ $componentId }}', '{{ $day }}', '{{ $dragAndDropClasses }}');"
    ondragover="onLivewireCalendarEventDragOver(event);"
    ondrop="onLivewireCalendarEventDrop(event, '{{ $componentId }}', '{{ $day }}', {{ $day->year }}, {{ $day->month }}, {{ $day->day }}, '{{ $dragAndDropClasses }}');"
    class="flex-1 h-40 lg:h-48 border border-zinc-200 dark:border-zinc-700 -mt-px -ml-px"
    style="min-width: 10rem;">

    {{-- Wrapper for Drag and Drop --}}
    <div
        class="w-full h-full"
        id="{{ $componentId }}-{{ $day }}">

        <div
            @if($dayClickEnabled)
                wire:click="onDayClick({{ $day->year }}, {{ $day->month }}, {{ $day->day }})"
            @endif
            class="w-full h-full p-2 {{ $dayInMonth ? $isToday ? 'bg-blue-50 dark:bg-blue-900/20' : ' bg-white dark:bg-zinc-900 ' : 'bg-zinc-100 dark:bg-zinc-800' }} flex flex-col {{ $dayClickEnabled ? 'cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/50' : '' }} transition-colors">

            {{-- Number of Day --}}
            <div class="flex items-center">
                <p class="text-sm font-semibold {{ $dayInMonth ? 'text-zinc-900 dark:text-zinc-50' : 'text-zinc-400 dark:text-zinc-600' }}">
                    {{ $day->format('j') }}
                </p>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 ml-4">
                    @if($events->isNotEmpty())
                        {{ $events->count() }} {{ Str::plural('event', $events->count()) }}
                    @endif
                </p>
            </div>

            {{-- Events --}}
            <div class="p-2 my-2 flex-1 overflow-y-auto">
                <div class="grid grid-cols-1 grid-flow-row gap-2">
                    @foreach($events as $event)
                        <div
                            @if($dragAndDropEnabled)
                                draggable="true"
                            @endif
                            ondragstart="onLivewireCalendarEventDragStart(event, '{{ $event['id'] }}')">
                            @include($eventView, [
                                'event' => $event,
                            ])
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</div>
