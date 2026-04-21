@extends('layouts.public')
@section('title', $newsItem->meta_title ?: $newsItem->title)
@section('description', $newsItem->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($newsItem->excerpt ?: $newsItem->content), 160))
@section('content')

    <div class="bg-neutral-950 py-16 text-white">
        <div class="mx-auto max-w-5xl px-6">
            <div class="mb-4 flex flex-wrap items-center gap-2 text-sm text-neutral-300">
                <a href="{{ route('news.index') }}" class="transition-colors hover:text-white">{{ __('News') }}</a>
                <span>/</span>
                @if($newsItem->published_at)
                    <span>
                        {{ \App\Support\LocalizedDate::date($newsItem->published_at) }}
                        <span class="block text-xs text-neutral-400">{{ \App\Support\LocalizedDate::hijri($newsItem->published_at) }}</span>
                    </span>
                @endif
            </div>
            <h1 class="max-w-4xl text-4xl font-bold tracking-tight md:text-5xl">{{ $newsItem->title }}</h1>
            @if($newsItem->categories->isNotEmpty())
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach($newsItem->categories as $category)
                        <a href="{{ route('news.index', ['category' => $category->id]) }}" class="rounded-full border border-white/15 bg-white/5 px-3 py-1 text-sm font-medium text-emerald-200 transition-colors hover:bg-white/10">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="mx-auto max-w-5xl px-6 py-12">
        @if($newsItem->featured_image)
            <div class="mb-10 overflow-hidden rounded-3xl border border-neutral-100 bg-neutral-100 shadow-sm">
                <img src="{{ \App\Support\AssetPath::url($newsItem->featured_image) }}" alt="{{ $newsItem->title }}" class="h-full w-full object-cover">
            </div>
        @endif

        <article class="max-w-none whitespace-pre-line text-base leading-8 text-neutral-700">
            {!! $newsItem->content !!}
        </article>

        @if($relatedNews->isNotEmpty())
            <section class="mt-16 border-t border-neutral-100 pt-10">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <h2 class="text-2xl font-bold text-neutral-900">{{ __('Related News') }}</h2>
                    <a href="{{ route('news.index') }}" class="text-sm font-semibold text-emerald-700 transition-colors hover:text-emerald-900">{{ __('View All News') }}</a>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach($relatedNews as $item)
                        <article class="rounded-2xl border border-neutral-100 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
                            <div class="mb-3 text-xs text-neutral-400">
                                {{ \App\Support\LocalizedDate::date($item->published_at) }}
                                <span class="block text-[10px] opacity-70">{{ \App\Support\LocalizedDate::hijri($item->published_at) }}</span>
                            </div>
                            <h3 class="mb-3 text-lg font-bold text-neutral-900">
                                <a href="{{ route('news.show', $item->slug) }}" class="transition-colors hover:text-emerald-700">{{ $item->title }}</a>
                            </h3>
                            <p class="mb-4 text-sm text-neutral-600">{{ \Illuminate\Support\Str::limit(strip_tags($item->excerpt ?: $item->content), 120) }}</p>
                            <a href="{{ route('news.show', $item->slug) }}" class="text-sm font-semibold text-emerald-700 transition-colors hover:text-emerald-900">{{ __('Read More') }}</a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
