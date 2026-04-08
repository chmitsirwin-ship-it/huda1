@php
    $data = $block['data'] ?? $data ?? [];
    $title = trim((string) ($data['title'] ?? ''));
    $style = $data['style'] ?? 'timeline';
    $showPast = (bool) ($data['show_past'] ?? true);
    $today = now()->startOfDay();
    $iconMap = [
        'moon' => 'heroicon-o-moon',
        'star' => 'heroicon-o-star',
        'mosque' => 'fas-mosque',
        'book' => 'heroicon-o-book-open',
        'calendar' => 'heroicon-o-calendar-date-range',
    ];

    $events = collect($data['events'] ?? [])
        ->filter(fn ($event) => is_array($event))
        ->map(function (array $event) use ($today, $iconMap): array {
            $parsedDate = null;

            try {
                $parsedDate = filled($event['gregorian_date'] ?? null)
                    ? \Illuminate\Support\Carbon::parse($event['gregorian_date'])->startOfDay()
                    : null;
            } catch (Throwable) {
                $parsedDate = null;
            }

            $isPast = $parsedDate?->lt($today) ?? false;
            $isToday = $parsedDate?->equalTo($today) ?? false;
            $isUpcoming = $parsedDate?->gt($today) ?? false;

            $event['name'] = trim((string) ($event['name'] ?? ''));
            $event['description'] = trim((string) ($event['description'] ?? ''));
            $event['hijri_date'] = \App\Support\LocalizedDate::hijri($parsedDate);
            $event['icon'] = array_key_exists($event['icon'] ?? 'calendar', $iconMap) ? $event['icon'] : 'calendar';
            $event['highlight'] = (bool) ($event['highlight'] ?? false);
            $event['_parsed_date'] = $parsedDate;
            $event['_gregorian_label'] = $parsedDate ? \App\Support\LocalizedDate::date($parsedDate) : null;
            $event['_is_past'] = $isPast;
            $event['_is_today'] = $isToday;
            $event['_is_upcoming'] = $isUpcoming;

            return $event;
        })
        ->filter(fn (array $event) => filled($event['name']) && filled($event['hijri_date']) && $event['_parsed_date'])
        ->sortBy(fn (array $event) => $event['_parsed_date']->timestamp)
        ->values();

    if (! $showPast) {
        $events = $events->reject(fn (array $event) => $event['_is_past'])->values();
    }

    $featuredIndex = $events->search(fn (array $event) => $event['_is_today'] || $event['_is_upcoming']);
    $featuredIndex = $featuredIndex === false ? null : $featuredIndex;
    $heading = $title !== '' ? $title : __('Islamic Calendar');
@endphp

<section class="bg-white py-16">
    <div class="mx-auto max-w-5xl px-6">
        <div class="mb-12 text-center">
            <div class="mb-3 inline-flex items-center gap-2 text-sm font-medium uppercase tracking-widest text-emerald-600">
                <span class="h-px w-8 bg-emerald-600"></span>
                {{ __('Sacred Dates') }}
                <span class="h-px w-8 bg-emerald-600"></span>
            </div>
            <h2 class="text-3xl font-bold text-neutral-900 md:text-4xl">{{ $heading }}</h2>
        </div>

        @if($events->isEmpty())
            <div class="rounded-2xl border border-neutral-200 bg-neutral-50 px-6 py-12 text-center text-neutral-500 shadow-sm">
                <div class="mx-auto mb-4 h-12 w-12 text-neutral-300">
                    <x-icon name="calendar" class="h-full w-full" />
                </div>
                <p>{{ __('No Islamic calendar events available.') }}</p>
            </div>
        @elseif($style === 'list')
            <div class="space-y-4">
                @foreach($events as $index => $event)
                    @php
                        $isFeatured = $featuredIndex === $index;
                        $cardClasses = 'rounded-2xl border bg-white p-5 shadow-sm transition-colors';

                        if ($event['_is_past']) {
                            $cardClasses .= ' border-neutral-200 text-neutral-400';
                        } elseif ($isFeatured || $event['highlight']) {
                            $cardClasses .= ' border-emerald-200 bg-emerald-50/60';
                        } else {
                            $cardClasses .= ' border-neutral-100';
                        }
                    @endphp
                    <article class="{{ $cardClasses }}">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div class="flex min-w-0 gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $event['_is_past'] ? 'bg-neutral-100 text-neutral-400' : 'bg-emerald-100 text-emerald-700' }}">
                                    <x-icon :name="$iconMap[$event['icon']]" class="h-5 w-5" />
                                </div>
                                <div class="min-w-0">
                                    <div class="mb-2 flex flex-wrap items-center gap-2">
                                        <h3 class="text-lg font-bold {{ $event['_is_past'] ? 'text-neutral-500' : 'text-neutral-900' }}">{{ $event['name'] }}</h3>
                                        @if($event['_is_today'])
                                            <span class="rounded-full bg-emerald-600 px-2.5 py-1 text-xs font-semibold text-white">{{ __('Today') }}</span>
                                        @elseif($isFeatured)
                                            <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">{{ __('Upcoming') }}</span>
                                        @elseif($event['highlight'])
                                            <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">{{ __('Highlighted') }}</span>
                                        @endif
                                    </div>
                                    <p class="font-medium {{ $event['_is_past'] ? 'text-neutral-400' : 'text-emerald-700' }}">{{ $event['hijri_date'] }}</p>
                                    @if($event['description'] !== '')
                                        <p class="mt-2 text-sm leading-relaxed {{ $event['_is_past'] ? 'text-neutral-400' : 'text-neutral-600' }}">{{ $event['description'] }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="shrink-0 text-sm font-medium {{ $event['_is_past'] ? 'text-neutral-400' : 'text-neutral-500' }}">
                                {{ $event['_gregorian_label'] }}
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @elseif($style === 'card')
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($events as $index => $event)
                    @php
                        $isFeatured = $featuredIndex === $index;
                        $cardClasses = 'flex h-full flex-col rounded-2xl border p-6 shadow-sm transition-colors';

                        if ($event['_is_past']) {
                            $cardClasses .= ' border-neutral-200 bg-white text-neutral-400';
                        } elseif ($isFeatured) {
                            $cardClasses .= ' border-emerald-300 bg-gradient-to-br from-emerald-50 to-white ring-1 ring-emerald-200';
                        } elseif ($event['highlight']) {
                            $cardClasses .= ' border-amber-200 bg-amber-50/50';
                        } else {
                            $cardClasses .= ' border-neutral-100 bg-white';
                        }
                    @endphp
                    <article class="{{ $cardClasses }}">
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $event['_is_past'] ? 'bg-neutral-100 text-neutral-400' : 'bg-emerald-100 text-emerald-700' }}">
                                <x-icon :name="$iconMap[$event['icon']]" class="h-5 w-5" />
                            </div>
                            @if($event['_is_today'])
                                <span class="rounded-full bg-emerald-600 px-2.5 py-1 text-xs font-semibold text-white">{{ __('Today') }}</span>
                            @elseif($isFeatured)
                                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">{{ __('Next') }}</span>
                            @elseif($event['highlight'])
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">{{ __('Important') }}</span>
                            @endif
                        </div>

                        <h3 class="mb-2 text-xl font-bold {{ $event['_is_past'] ? 'text-neutral-500' : 'text-neutral-900' }}">{{ $event['name'] }}</h3>
                        <p class="mb-2 font-medium {{ $event['_is_past'] ? 'text-neutral-400' : 'text-emerald-700' }}">{{ $event['hijri_date'] }}</p>
                        <p class="mb-4 text-sm {{ $event['_is_past'] ? 'text-neutral-400' : 'text-neutral-500' }}">{{ $event['_gregorian_label'] }}</p>

                        @if($event['description'] !== '')
                            <p class="mt-auto text-sm leading-relaxed {{ $event['_is_past'] ? 'text-neutral-400' : 'text-neutral-600' }}">{{ $event['description'] }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        @else
            <div class="relative space-y-6 before:absolute before:bottom-0 before:start-[1.45rem] before:top-0 before:w-px before:bg-neutral-200 md:before:start-1/2 md:before:-translate-x-1/2">
                @foreach($events as $index => $event)
                    @php
                        $isFeatured = $featuredIndex === $index;
                        $cardClasses = 'relative rounded-2xl border bg-white p-6 shadow-sm';

                        if ($event['_is_past']) {
                            $cardClasses .= ' border-neutral-200 text-neutral-400';
                        } elseif ($event['_is_today']) {
                            $cardClasses .= ' border-emerald-300 bg-emerald-50/70 ring-1 ring-emerald-200';
                        } elseif ($isFeatured) {
                            $cardClasses .= ' border-emerald-200 bg-emerald-50/50';
                        } elseif ($event['highlight']) {
                            $cardClasses .= ' border-amber-200 bg-amber-50/50';
                        } else {
                            $cardClasses .= ' border-neutral-100';
                        }
                    @endphp
                    <div class="relative grid gap-4 ps-12 md:grid-cols-2 md:gap-10 md:ps-0">
                        <div class="hidden md:flex {{ $index % 2 === 0 ? 'justify-end text-end' : 'order-2 justify-start text-start' }}">
                            <div class="max-w-sm">
                                <p class="text-sm font-semibold {{ $event['_is_past'] ? 'text-neutral-400' : 'text-emerald-700' }}">{{ $event['hijri_date'] }}</p>
                                <p class="mt-1 text-sm {{ $event['_is_past'] ? 'text-neutral-400' : 'text-neutral-500' }}">{{ $event['_gregorian_label'] }}</p>
                            </div>
                        </div>

                        <div class="relative {{ $index % 2 === 0 ? 'md:col-start-2' : 'md:col-start-1 md:row-start-1' }}">

                            <article class="{{ $cardClasses }}">
                                <div class="mb-4 flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $event['_is_past'] ? 'bg-neutral-100 text-neutral-400' : 'bg-emerald-100 text-emerald-700' }}">
                                            <x-icon :name="$iconMap[$event['icon']]" class="h-5 w-5" />
                                        </div>
                                        <div class="md:hidden">
                                            <p class="text-sm font-semibold {{ $event['_is_past'] ? 'text-neutral-400' : 'text-emerald-700' }}">{{ $event['hijri_date'] }}</p>
                                            <p class="text-sm {{ $event['_is_past'] ? 'text-neutral-400' : 'text-neutral-500' }}">{{ $event['_gregorian_label'] }}</p>
                                        </div>
                                    </div>

                                    @if($event['_is_today'])
                                        <span class="rounded-full bg-emerald-600 px-2.5 py-1 text-xs font-semibold text-white">{{ __('Today') }}</span>
                                    @elseif($isFeatured)
                                        <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700">{{ __('Upcoming') }}</span>
                                    @elseif($event['highlight'])
                                        <span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">{{ __('Highlighted') }}</span>
                                    @endif
                                </div>

                                <h3 class="mb-2 text-xl font-bold {{ $event['_is_past'] ? 'text-neutral-500' : 'text-neutral-900' }}">{{ $event['name'] }}</h3>

                                @if($event['description'] !== '')
                                    <p class="text-sm leading-relaxed {{ $event['_is_past'] ? 'text-neutral-400' : 'text-neutral-600' }}">{{ $event['description'] }}</p>
                                @endif
                            </article>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>