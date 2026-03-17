@extends('layouts.public')
@section('title', __('Events & Programs'))
@section('content')

    <div class="bg-emerald-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-4xl font-bold tracking-tight mb-3">{{ __('Events & Programs') }}</h1>
            <p class="text-emerald-200 text-lg">{{ __('Stay connected with upcoming events and programs') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12">

        @if($events->isEmpty())
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 mb-6">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-neutral-700 mb-2">{{ __('No events scheduled') }}</h3>
                <p class="text-neutral-500">{{ __('Check back soon for upcoming events and programs.') }}</p>
            </div>
        @else
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($events as $event)
                    <a href="{{ route('events.show', [app()->getLocale(), $event->id]) }}"
                       class="group bg-white rounded-2xl shadow-sm border border-neutral-100 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">

                        @if($event->image)
                            <div class="aspect-video overflow-hidden">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($event->image) }}"
                                     alt="{{ $event->getTranslation('title', app()->getLocale(), false) }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            <div class="aspect-video bg-emerald-700 flex items-center justify-center">
                                <svg class="w-16 h-16 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        <div class="p-6">
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 rounded-lg px-3 py-1.5 text-xs font-semibold shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $event->starts_at->format('M d, Y') }}
                                </div>

                                @php
                                    $statusClasses = match($event->status ?? 'upcoming') {
                                        'ongoing'   => 'bg-emerald-100 text-emerald-700',
                                        'completed' => 'bg-neutral-100 text-neutral-500',
                                        'cancelled' => 'bg-red-100 text-red-600',
                                        default     => 'bg-amber-100 text-amber-700',
                                    };
                                @endphp
                                <span class="inline-block text-xs font-medium px-2.5 py-1 rounded-full {{ $statusClasses }} capitalize shrink-0">
                                    {{ __($event->status ?? 'upcoming') }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-neutral-900 mb-2 group-hover:text-emerald-700 transition-colors leading-snug">
                                {{ $event->getTranslation('title', app()->getLocale(), false) }}
                            </h3>

                            @if($event->location)
                                <div class="flex items-center gap-2 text-sm text-neutral-500">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $event->location }}</span>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $events->links() }}
            </div>
        @endif

    </div>

@endsection
