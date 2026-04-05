@extends('layouts.public')
@section('title', $khutba->title)
@section('description', \Illuminate\Support\Str::limit($khutba->content ?: $khutba->topic ?: $khutba->title, 160))
@section('content')

    <div class="bg-emerald-950 py-16 text-white">
        <div class="mx-auto max-w-5xl px-6">
            <div class="mb-4 flex flex-wrap items-center gap-2 text-sm text-emerald-200">
                <a href="{{ route('khutba.index') }}" class="transition-colors hover:text-white">{{ __('Khutba') }}</a>
                <span>/</span>
                @if($khutba->date)
                    <span>{{ \App\Support\LocalizedDate::date($khutba->date) }}</span>
                @endif
            </div>

            <h1 class="max-w-4xl text-4xl font-bold tracking-tight md:text-5xl">{{ $khutba->title }}</h1>

            <div class="mt-6 flex flex-wrap gap-3 text-sm text-emerald-100">
                @if($khutba->speaker)
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">{{ $khutba->speaker }}</span>
                @endif
                @if($khutba->topic)
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">{{ $khutba->topic }}</span>
                @endif
                @foreach($khutba->categories as $category)
                    <a href="{{ route('khutba.index', ['category' => $category->id]) }}" class="rounded-full border border-white/10 bg-white/5 px-3 py-1 transition-colors hover:bg-white/10">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-6 py-12">
        @if($khutba->featured_image)
            <div class="mb-10 overflow-hidden rounded-3xl border border-neutral-100 bg-neutral-100 shadow-sm">
                <img src="{{ \App\Support\AssetPath::url($khutba->featured_image) }}" alt="{{ $khutba->title }}" class="h-full w-full object-cover">
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_320px]">
            <article class="whitespace-pre-line text-base leading-8 text-neutral-700">
                {{ $khutba->content ?: $khutba->topic ?: $khutba->title }}
            </article>

            <aside class="space-y-4">
                @if($khutba->audio_url)
                    <div class="rounded-2xl border border-neutral-100 bg-white p-5 shadow-sm">
                        <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-neutral-500">{{ __('Audio Recording') }}</p>
                        <audio controls src="{{ \App\Support\AssetPath::url($khutba->audio_url) }}" class="w-full h-10 rounded-lg accent-emerald-600"></audio>
                    </div>
                @endif

                @if($khutba->video_url)
                    <div class="rounded-2xl border border-neutral-100 bg-white p-5 shadow-sm">
                        <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-neutral-500">{{ __('Video') }}</p>
                        <a href="{{ \App\Support\AssetPath::url($khutba->video_url) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition-colors hover:text-emerald-900">
                            {{ __('Open Video') }}
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                @endif
            </aside>
        </div>

        @if($relatedKhutbas->isNotEmpty())
            <section class="mt-16 border-t border-neutral-100 pt-10">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <h2 class="text-2xl font-bold text-neutral-900">{{ __('More Khutbas') }}</h2>
                    <a href="{{ route('khutba.index') }}" class="text-sm font-semibold text-emerald-700 transition-colors hover:text-emerald-900">{{ __('View Archive') }}</a>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    @foreach($relatedKhutbas as $item)
                        <article class="rounded-2xl border border-neutral-100 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
                            <div class="mb-3 text-xs text-neutral-400">{{ \App\Support\LocalizedDate::date($item->date) }}</div>
                            <h3 class="mb-3 text-lg font-bold text-neutral-900">
                                <a href="{{ route('khutba.show', $item->slug) }}" class="transition-colors hover:text-emerald-700">{{ $item->title }}</a>
                            </h3>
                            <p class="mb-4 text-sm text-neutral-600">{{ \Illuminate\Support\Str::limit($item->content ?: $item->topic ?: $item->title, 120) }}</p>
                            <a href="{{ route('khutba.show', $item->slug) }}" class="text-sm font-semibold text-emerald-700 transition-colors hover:text-emerald-900">{{ __('Read More') }}</a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
