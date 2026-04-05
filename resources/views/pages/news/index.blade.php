@extends('layouts.public')
@section('title', __('News'))
@section('description', __('Latest news, articles, and updates from the mosque'))
@section('content')

    <div class="bg-emerald-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-4xl font-bold tracking-tight mb-3">{{ __('News') }}</h1>
            <p class="text-emerald-200 text-lg">{{ __('Latest articles, community updates, and important news') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-12">
        <form method="GET" action="{{ route('news.index') }}" class="mb-10 space-y-4">
            <div class="flex flex-col gap-3 lg:flex-row">
                <div class="relative flex-1">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text"
                           name="search"
                           value="{{ $search }}"
                           placeholder="{{ __('Search news...') }}"
                           class="w-full rounded-xl border border-neutral-200 bg-white py-3 pl-12 pr-4 text-neutral-800 placeholder-neutral-400 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="lg:w-80">
                    <select name="category"
                            class="w-full rounded-xl border border-neutral-200 bg-white px-4 py-3 text-neutral-800 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                        class="rounded-xl bg-emerald-700 px-6 py-3 font-medium text-white transition-colors hover:bg-emerald-800">
                    {{ __('Filter') }}
                </button>
                @if($search || $categoryId)
                    <a href="{{ route('news.index') }}"
                       class="rounded-xl border border-neutral-200 px-4 py-3 text-neutral-600 transition-colors hover:bg-neutral-50">
                        {{ __('Reset') }}
                    </a>
                @endif
            </div>
        </form>

        @if($search || $activeCategory)
            <p class="mb-6 text-sm text-neutral-500">
                @if($search)
                    {{ __('Results for') }}: <span class="font-medium text-neutral-700">"{{ $search }}"</span>
                @endif
                @if($activeCategory)
                    @if($search)
                        &mdash;
                    @endif
                    {{ __('Category') }}: <span class="font-medium text-neutral-700">{{ $activeCategory->name }}</span>
                @endif
                &mdash; {{ $newsItems->total() }} {{ __('found') }}
            </p>
        @endif

        @if($newsItems->isEmpty())
            <div class="py-20 text-center">
                <div class="mb-6 inline-flex h-20 w-20 items-center justify-center rounded-full bg-emerald-50">
                    <svg class="h-10 w-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2zM9 9h1m-1 4h6m-6 4h6" />
                    </svg>
                </div>
                <h3 class="mb-2 text-xl font-semibold text-neutral-700">{{ __('No news found') }}</h3>
                <p class="text-neutral-500">{{ __('Try changing your search or selected category.') }}</p>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach($newsItems as $newsItem)
                    <article class="overflow-hidden rounded-2xl border border-neutral-100 bg-white shadow-sm transition-shadow hover:shadow-md">
                        @if($newsItem->featured_image)
                            <a href="{{ route('news.show', $newsItem->slug) }}" class="block aspect-[16/10] overflow-hidden bg-neutral-100">
                                <img src="{{ \App\Support\AssetPath::url($newsItem->featured_image) }}" alt="{{ $newsItem->title }}" class="h-full w-full object-cover transition-transform duration-300 hover:scale-105">
                            </a>
                        @endif
                        <div class="p-6">
                            <div class="mb-4 flex flex-wrap items-center gap-2 text-xs text-neutral-500">
                                @if($newsItem->published_at)
                                    <span>{{ \App\Support\LocalizedDate::date($newsItem->published_at) }}</span>
                                @endif
                                @foreach($newsItem->categories as $category)
                                    <a href="{{ route('news.index', ['category' => $category->id]) }}" class="rounded-full bg-emerald-50 px-2.5 py-1 font-medium text-emerald-700 transition-colors hover:bg-emerald-100">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                            <h2 class="mb-3 text-xl font-bold text-neutral-900">
                                <a href="{{ route('news.show', $newsItem->slug) }}" class="transition-colors hover:text-emerald-700">
                                    {{ $newsItem->title }}
                                </a>
                            </h2>
                            <p class="mb-5 text-sm leading-relaxed text-neutral-600">
                                {{ \Illuminate\Support\Str::limit(strip_tags($newsItem->excerpt ?: $newsItem->content), 180) }}
                            </p>
                            <a href="{{ route('news.show', $newsItem->slug) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition-colors hover:text-emerald-900">
                                {{ __('Read More') }}
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $newsItems->links() }}
            </div>
        @endif
    </div>
@endsection
