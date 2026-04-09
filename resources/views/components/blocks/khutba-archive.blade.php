@php
$data = $block['data'] ?? $data ?? [];
$khutbasQuery = \App\Models\Khutba::query()->with('categories')->published();

if (filled($data['category_ids'] ?? [])) {
    $khutbasQuery->whereHas('categories', fn ($query) => $query->whereIn('khutba_categories.id', (array) $data['category_ids']));
}

$khutbas = $khutbasQuery->limit($data['limit'] ?? 5)->get();
@endphp

@if(\App\Support\PublicNavigation::isEnabled('khutba'))
<section class="py-20 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-end justify-between mb-12">
            <div>
                <div class="inline-flex items-center gap-2 text-emerald-600 font-medium text-sm uppercase tracking-widest mb-3">
                    <span class="w-8 h-px bg-emerald-600"></span>
                    {{ __('Weekly Sermons') }}
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-neutral-900">{{ __("Jumu'ah Khutba Archive") }}</h2>
            </div>
            <a href="{{ route('khutba.index', app()->getLocale()) }}"
               class="hidden sm:inline-flex items-center gap-2 text-emerald-600 font-semibold hover:text-emerald-700 transition-colors group">
                {{ __('View All') }}
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if($khutbas->isEmpty())
            <div class="text-center py-16 text-neutral-400">
                <svg class="w-14 h-14 mx-auto mb-4 text-neutral-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                </svg>
                <p class="text-lg">{{ __('No khutbas available.') }}</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($khutbas as $khutba)
                    <div class="bg-white rounded-xl border border-neutral-100 p-6 shadow-sm hover:shadow-md hover:border-emerald-200 transition-all duration-200 group">
                        <div class="flex items-start gap-5">
                            <div class="shrink-0 flex flex-col items-center justify-center w-16 bg-emerald-600 rounded-xl text-white px-2 py-2">
                                <span class="text-xl font-bold leading-none">{{ \App\Support\LocalizedDate::day($khutba->date) }}</span>
                                <span class="text-xs uppercase tracking-wide mt-0.5 opacity-80">{{ \App\Support\LocalizedDate::monthShort($khutba->date) }}</span>
                                <span class="text-[9px] text-emerald-200 mt-1 leading-tight text-center">{{ \App\Support\LocalizedDate::hijri($khutba->date) }}</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-start justify-between gap-3 mb-2">
                                    <h3 class="font-bold text-neutral-900 text-lg group-hover:text-emerald-600 transition-colors leading-snug">
                                        <a href="{{ route('khutba.show', $khutba->slug) }}">
                                            {{ $khutba->title }}
                                        </a>
                                    </h3>

                                    <div class="flex items-center gap-2 shrink-0">
                                        @if($khutba->audio_url)
                                            <a href="{{ \App\Support\AssetPath::url($khutba->audio_url) }}"
                                               target="_blank"
                                               class="flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2.5 py-1.5 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072M12 9.5l-3-3m0 0l-3 3m3-3v12"/>
                                                </svg>
                                                {{ __('Audio') }}
                                            </a>
                                        @endif

                                        @if($khutba->video_url)
                                            <a href="{{ \App\Support\AssetPath::url($khutba->video_url) }}"
                                               target="_blank"
                                               class="flex items-center gap-1.5 text-xs font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200 px-2.5 py-1.5 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ __('Video') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                                    @if($khutba->speaker)
                                        <span class="flex items-center gap-1.5 text-sm text-neutral-600">
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            {{ $khutba->speaker }}
                                        </span>
                                    @endif

                                    @if($khutba->topic)
                                        <span class="flex items-center gap-1.5 text-sm text-neutral-500">
                                            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                            </svg>
                                            {{ $khutba->topic }}
                                        </span>
                                    @endif

                                    <span class="text-sm text-neutral-400 leading-relaxed">
                                        {{ \App\Support\LocalizedDate::date($khutba->date) }}
                                        <span class="block text-xs opacity-70">{{ \App\Support\LocalizedDate::hijri($khutba->date) }}</span>
                                    </span>
                                </div>

                                @if($khutba->content)
                                    <p class="text-neutral-500 text-sm mt-3 leading-relaxed line-clamp-3 whitespace-pre-line">
                                        {{ $khutba->content }}
                                    </p>
                                @endif

                                <div class="mt-4">
                                    <a href="{{ route('khutba.show', $khutba->slug) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition-colors hover:text-emerald-900">
                                        {{ __('Read Full Khutba') }}
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="sm:hidden mt-8 text-center">
                <a href="{{ route('khutba.index', app()->getLocale()) }}"
                   class="inline-flex items-center gap-2 text-emerald-600 font-semibold hover:text-emerald-700 transition-colors">
                    {{ __('View All') }}
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</section>
@endif
