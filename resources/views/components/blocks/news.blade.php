@php
$data = $block['data'] ?? $data ?? [];
$newsQuery = \App\Models\News::query()->with('categories')->published();

if (filled($data['category_ids'] ?? [])) {
    $newsQuery->whereHas('categories', fn ($query) => $query->whereIn('news_categories.id', (array) $data['category_ids']));
}

$newsItems = $newsQuery->limit($data['limit'] ?? 6)->get();
$isGrid = ($data['style'] ?? 'grid') === 'grid';
$buttonUrl = $data['button_url'] ?? route('news.index');
@endphp

<section class="bg-white py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mb-12 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="mb-3 inline-flex items-center gap-2 text-sm font-medium uppercase tracking-widest text-emerald-600">
                    <span class="h-px w-8 bg-emerald-600"></span>
                    {{ __('Community Updates') }}
                </div>
                <h2 class="text-3xl font-bold text-neutral-900 md:text-4xl">{{ $data['heading'] ?? __('Latest News') }}</h2>
            </div>
            <a href="{{ $buttonUrl }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition-colors hover:text-emerald-900">
                {{ $data['button_text'] ?? __('View All News') }}
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        @if($newsItems->isEmpty())
            <div class="py-16 text-center text-neutral-400">
                <svg class="mx-auto mb-4 h-14 w-14 text-neutral-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h11l5 5v9a2 2 0 01-2 2zM9 9h1m-1 4h6m-6 4h6" />
                </svg>
                <p class="text-lg">{{ __('No news available.') }}</p>
            </div>
        @elseif($isGrid)
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach($newsItems as $newsItem)
                    <article class="overflow-hidden rounded-2xl border border-neutral-100 bg-white shadow-sm transition-shadow hover:shadow-md">
                        @if($newsItem->featured_image)
                            <a href="{{ route('news.show', $newsItem->slug) }}" class="block aspect-[16/10] overflow-hidden bg-neutral-100">
                                <img src="{{ \App\Support\AssetPath::url($newsItem->featured_image) }}" alt="{{ $newsItem->title }}" class="h-full w-full object-cover transition-transform duration-300 hover:scale-105">
                            </a>
                        @endif
                        <div class="p-6">
                            <div class="mb-3 flex flex-wrap gap-2 text-xs text-neutral-500">
                                @if($newsItem->published_at)
                                    <span>{{ \App\Support\LocalizedDate::date($newsItem->published_at) }}</span>
                                @endif
                                @foreach($newsItem->categories as $category)
                                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 font-medium text-emerald-700">{{ $category->name }}</span>
                                @endforeach
                            </div>
                            <h3 class="mb-3 text-xl font-bold text-neutral-900">
                                <a href="{{ route('news.show', $newsItem->slug) }}" class="transition-colors hover:text-emerald-700">{{ $newsItem->title }}</a>
                            </h3>
                            <p class="mb-4 text-sm leading-relaxed text-neutral-600">{{ \Illuminate\Support\Str::limit(strip_tags($newsItem->excerpt ?: $newsItem->content), 160) }}</p>
                            <a href="{{ route('news.show', $newsItem->slug) }}" class="text-sm font-semibold text-emerald-700 transition-colors hover:text-emerald-900">{{ __('Read More') }}</a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="space-y-4">
                @foreach($newsItems as $newsItem)
                    <article class="rounded-2xl border border-neutral-100 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
                        <div class="flex flex-col gap-5 md:flex-row md:items-start">
                            @if($newsItem->featured_image)
                                <a href="{{ route('news.show', $newsItem->slug) }}" class="block h-36 w-full shrink-0 overflow-hidden rounded-2xl bg-neutral-100 md:w-56">
                                    <img src="{{ \App\Support\AssetPath::url($newsItem->featured_image) }}" alt="{{ $newsItem->title }}" class="h-full w-full object-cover">
                                </a>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="mb-3 flex flex-wrap gap-2 text-xs text-neutral-500">
                                    @if($newsItem->published_at)
                                        <span>{{ \App\Support\LocalizedDate::date($newsItem->published_at) }}</span>
                                    @endif
                                    @foreach($newsItem->categories as $category)
                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 font-medium text-emerald-700">{{ $category->name }}</span>
                                    @endforeach
                                </div>
                                <h3 class="mb-3 text-2xl font-bold text-neutral-900">
                                    <a href="{{ route('news.show', $newsItem->slug) }}" class="transition-colors hover:text-emerald-700">{{ $newsItem->title }}</a>
                                </h3>
                                <p class="mb-4 text-sm leading-relaxed text-neutral-600">{{ \Illuminate\Support\Str::limit(strip_tags($newsItem->excerpt ?: $newsItem->content), 220) }}</p>
                                <a href="{{ route('news.show', $newsItem->slug) }}" class="text-sm font-semibold text-emerald-700 transition-colors hover:text-emerald-900">{{ __('Read More') }}</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
