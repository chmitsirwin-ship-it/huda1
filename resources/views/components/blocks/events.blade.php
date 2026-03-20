@php
$data = $block['data'] ?? $data ?? [];
$events = \App\Models\Event::published()->limit($data['limit'] ?? 3)->get();
$style = $data['style'] ?? 'grid';
@endphp

<section class="py-20 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-12">
            <div>
                <div class="inline-flex items-center gap-2 text-emerald-600 font-medium text-sm uppercase tracking-widest mb-3">
                    <span class="w-8 h-px bg-emerald-600"></span>
                    {{ __('Community') }}
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-neutral-900">{{ __('Upcoming Events') }}</h2>
            </div>
            <a href="{{ route('events.index', app()->getLocale()) }}"
               class="hidden sm:inline-flex items-center gap-2 text-emerald-600 font-semibold hover:text-emerald-700 transition-colors group">
                {{ __('View All Events') }}
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if($events->isEmpty())
            <div class="text-center py-16 text-neutral-400">
                <svg class="w-14 h-14 mx-auto mb-4 text-neutral-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-lg">{{ __('No upcoming events at this time.') }}</p>
            </div>
        @elseif($style === 'list')
            <div class="space-y-4">
                @foreach($events as $event)
                    <div class="flex gap-6 bg-white rounded-xl p-6 shadow-sm border border-neutral-100 hover:border-emerald-200 hover:shadow-md transition-all duration-200 group">
                        <div class="shrink-0 flex flex-col items-center justify-center w-16 h-16 bg-emerald-600 rounded-xl text-white">
                            <span class="text-2xl font-bold leading-none">{{ $event->starts_at->format('d') }}</span>
                            <span class="text-xs uppercase tracking-wide mt-0.5">{{ $event->starts_at->format('M') }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-neutral-900 text-lg mb-1 group-hover:text-emerald-600 transition-colors truncate">
                                {{ $event->title }}
                            </h3>
                            @if($event->location)
                                <div class="flex items-center gap-1.5 text-neutral-500 text-sm">
                                    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $event->location }}
                                </div>
                            @endif
                            @if($event->description)
                                <p class="text-neutral-500 text-sm mt-2 line-clamp-2">
                                    {{ str($event->description)->squish()->stripTags() }}
                                </p>
                            @endif
                        </div>
                        <div class="shrink-0 self-center">
                            <span class="text-sm text-neutral-400">{{ $event->starts_at->format('g:i A') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($style === 'carousel')
            <div class="relative" x-data="{ current: 0, total: {{ $events->count() }} }">
                <div class="overflow-hidden rounded-2xl">
                    <div class="flex transition-transform duration-500" :style="`transform: translateX(-${current * 100}%)`">
                        @foreach($events as $event)
                            <div class="w-full shrink-0">
                                <div class="bg-white rounded-2xl overflow-hidden shadow-md border border-neutral-100">
                                    @if($event->image)
                                        <img src="{{ Storage::url($event->image) }}"
                                             alt="{{ $event->title }}"
                                             class="w-full h-64 object-cover">
                                    @else
                                        <div class="w-full h-64 bg-emerald-600 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-emerald-400/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="p-6">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                                {{ $event->starts_at->translatedFormat('d M Y') }}
                                            </span>
                                        </div>
                                        <h3 class="text-xl font-bold text-neutral-900 mb-2">
                                            {{ $event->title }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @if($events->count() > 1)
                    <div class="flex justify-center gap-2 mt-4">
                        @foreach($events as $i => $event)
                            <button @click="current = {{ $i }}"
                                    class="w-2.5 h-2.5 rounded-full transition-all duration-300"
                                    :class="current === {{ $i }} ? 'bg-emerald-600 w-6' : 'bg-neutral-300'">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($events as $event)
                    <article class="bg-white rounded-2xl overflow-hidden shadow-sm border border-neutral-100 hover:shadow-lg hover:border-emerald-200 transition-all duration-300 group flex flex-col">
                        @if($event->image)
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ Storage::url($event->image) }}"
                                     alt="{{ $event->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute top-4 left-4">
                                    <div class="bg-white rounded-lg px-3 py-1.5 shadow-md text-center min-w-[48px]">
                                        <div class="text-xl font-bold text-emerald-600 leading-none">{{ $event->starts_at->format('d') }}</div>
                                        <div class="text-xs text-neutral-500 uppercase tracking-wide">{{ $event->starts_at->format('M') }}</div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="relative h-48 bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center">
                                <svg class="w-16 h-16 text-emerald-400/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div class="absolute top-4 left-4">
                                    <div class="bg-white/20 backdrop-blur-sm rounded-lg px-3 py-1.5 text-center min-w-[48px]">
                                        <div class="text-xl font-bold text-white leading-none">{{ $event->starts_at->format('d') }}</div>
                                        <div class="text-xs text-white/80 uppercase tracking-wide">{{ $event->starts_at->format('M') }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="p-6 flex flex-col flex-1">
                            <h3 class="font-bold text-neutral-900 text-lg mb-2 group-hover:text-emerald-600 transition-colors line-clamp-2 flex-1">
                                {{ $event->title }}
                            </h3>

                            @if($event->location)
                                <div class="flex items-center gap-1.5 text-neutral-500 text-sm mt-auto pt-4 border-t border-neutral-100">
                                    <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="truncate">{{ $event->location }}</span>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        <div class="sm:hidden mt-8 text-center">
            <a href="{{ route('events.index', app()->getLocale()) }}"
               class="inline-flex items-center gap-2 text-emerald-600 font-semibold hover:text-emerald-700 transition-colors">
                {{ __('View All Events') }}
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</section>
