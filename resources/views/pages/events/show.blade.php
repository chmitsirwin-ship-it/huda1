@extends('layouts.public')
@section('title', $event->title)
@section('content')

    @if($event->image)
        <div class="w-full h-80 md:h-96 overflow-hidden">
            <img src="{{ \Illuminate\Support\Facades\Storage::url($event->image) }}"
                 alt="{{ $event->title }}"
                 class="w-full h-full object-cover">
        </div>
    @else
        <div class="bg-emerald-900 text-white py-16">
            <div class="max-w-7xl mx-auto px-6">
                <a href="{{ route('events.index') }}"
                   class="inline-flex items-center gap-2 text-emerald-300 hover:text-white text-sm mb-6 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Back to Events') }}
                </a>
                <h1 class="text-4xl font-bold tracking-tight">{{ $event->title }}</h1>
            </div>
        </div>
    @endif

    <div class="max-w-4xl mx-auto px-6 py-12">

        @if($event->image)
            <a href="{{ route('events.index') }}"
               class="inline-flex items-center gap-2 text-emerald-600 hover:text-emerald-800 text-sm mb-8 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                {{ __('Back to Events') }}
            </a>

            <h1 class="text-4xl font-bold text-neutral-900 mb-6 leading-tight">
                {{ $event->title }}
            </h1>
        @endif

        <div class="flex flex-wrap gap-3 mb-8">

            @php
                $statusClasses = match($event->status ?? 'upcoming') {
                    'ongoing'   => 'bg-emerald-100 text-emerald-700',
                    'completed' => 'bg-neutral-100 text-neutral-500',
                    'cancelled' => 'bg-red-100 text-red-600',
                    default     => 'bg-amber-100 text-amber-700',
                };
            @endphp

            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium {{ $statusClasses }} capitalize">
                {{ $event->status->getLabel() }}
            </span>

            <span class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 rounded-full px-3 py-1.5 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ $event->starts_at->translatedFormat('l, F j, Y') }}
            </span>

            @if($event->starts_at)
                <span class="inline-flex items-center gap-2 bg-neutral-100 text-neutral-600 rounded-full px-3 py-1.5 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $event->starts_at->translatedFormat('g:i A') }}
                    @if($event->ends_at)
                        &mdash; {{ $event->ends_at->translatedFormat('g:i A') }}
                    @endif
                </span>
            @endif

            @if($event->location)
                <span class="inline-flex items-center gap-2 bg-neutral-100 text-neutral-600 rounded-full px-3 py-1.5 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $event->location }}
                </span>
            @endif
        </div>

        @if($event->description)
            <div class="prose prose-emerald prose-lg max-w-none text-neutral-700 leading-relaxed">
                {!! $event->description !!}
            </div>
        @endif

    </div>

@endsection
