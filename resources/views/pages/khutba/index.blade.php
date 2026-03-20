@extends('layouts.public')
@section('title', __("Jumu'ah Khutba Archive"))
@section('content')

    <div class="bg-emerald-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-4xl font-bold tracking-tight mb-3">{{ __("Jumu'ah Khutba Archive") }}</h1>
            <p class="text-emerald-200 text-lg">{{ __('Friday sermon recordings and transcripts') }}</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 py-12">

        <form method="GET" action="{{ route('khutba.index', app()->getLocale()) }}" class="mb-10">
            <div class="flex gap-3">
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="{{ __('Search by title, speaker, or topic...') }}"
                           class="w-full pl-12 pr-4 py-3 border border-neutral-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-neutral-800 placeholder-neutral-400 bg-white">
                </div>
                <button type="submit"
                        class="px-6 py-3 bg-emerald-700 text-white rounded-xl font-medium hover:bg-emerald-800 transition-colors">
                    {{ __('Search') }}
                </button>
                @if($search)
                    <a href="{{ route('khutba.index', app()->getLocale()) }}"
                       class="px-4 py-3 border border-neutral-200 text-neutral-600 rounded-xl hover:bg-neutral-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>

        @if($search)
            <p class="text-sm text-neutral-500 mb-6">
                {{ __('Results for') }}: <span class="font-medium text-neutral-700">"{{ $search }}"</span>
                &mdash; {{ $khutbas->total() }} {{ __('found') }}
            </p>
        @endif

        @if($khutbas->isEmpty())
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 mb-6">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-neutral-700 mb-2">
                    {{ $search ? __('No khutbas found') : __('No khutbas yet') }}
                </h3>
                <p class="text-neutral-500">
                    {{ $search ? __('Try a different search term.') : __('Khutba recordings will appear here.') }}
                </p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($khutbas as $khutba)
                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-neutral-900 mb-1">
                                    {{ $khutba->title }}
                                </h3>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-neutral-500">
                                    @if($khutba->date)
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ \App\Support\LocalizedDate::date($khutba->date) }}
                                        </span>
                                    @endif
                                    @if($khutba->speaker)
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            {{ $khutba->speaker }}
                                        </span>
                                    @endif
                                    @if($khutba->topic)
                                        <span class="inline-flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                            {{ $khutba->topic }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($khutba->audio_url)
                            <div class="mt-4">
                                <p class="text-xs font-medium text-neutral-500 mb-2 uppercase tracking-wide">{{ __('Audio Recording') }}</p>
                                <audio controls src="{{ $khutba->audio_url }}"
                                       class="w-full h-10 rounded-lg accent-emerald-600">
                                </audio>
                            </div>
                        @endif

                        @if($khutba->video_url)
                            <div class="mt-4">
                                <a href="{{ $khutba->video_url }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 text-sm font-medium text-emerald-700 hover:text-emerald-900 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ __('Watch Video') }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $khutbas->appends(['search' => $search])->links() }}
            </div>
        @endif

    </div>

@endsection
