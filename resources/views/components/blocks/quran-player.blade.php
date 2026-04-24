@php
    $data = $block['data'] ?? $data ?? [];
    $playerSeed = $block['id'] ?? $block['uuid'] ?? $data['id'] ?? md5(json_encode($data));
    $playerId = 'quran-player-'.preg_replace('/[^a-zA-Z0-9_-]/', '-', (string) $playerSeed);
    $language = ($data['language_mode'] ?? 'auto') === 'auto' ? app()->getLocale() : ($data['language_mode'] ?? 'ar');
    $config = [
        'blockId' => $playerId,
        'title' => $data['title'] ?? __('Quran Player'),
        'intro' => $data['intro'] ?? '',
        'language' => $language,
        'locale' => app()->getLocale(),
        'dir' => app()->getLocale() === 'ar' ? 'rtl' : 'ltr',
        'routes' => [
            'bootstrap' => route('quran-player.bootstrap'),
        ],
        'features' => [
            'autoplay' => false,
            'rememberState' => true,
        ],
        'labels' => [
            'loading' => __('Loading Quran player...'),
            'failed' => __('Unable to load Quran player data right now.'),
            'ready' => __('Player ready'),
            'empty' => __('No items available right now.'),
            'previous' => __('Previous'),
            'next' => __('Next'),
            'shuffle' => __('Shuffle'),
            'loop' => __('Loop'),
            'playingNow' => __('Playing Now'),
            'chooseReciter' => __('Choose a reciter'),
            'searchReciters' => __('Search reciters'),
            'pickReciter' => __('Pick Reciter'),
            'chooseRiwayah' => __('Choose riwayah'),
            'chooseSurah' => __('Choose a surah'),
            'recitations' => __('Recitations'),
            'playThis' => __('Play this item'),
            'searchSurahs' => __('Search surahs'),
            'surahLibrary' => __('Surah Library'),
            'showingResults' => __('Showing Results'),
            'clear' => __('Clear'),
            'browseReciters' => __('Browse Reciters'),
            'browseSurahs' => __('Browse Surahs'),
            'close' => __('Close'),
            'filters' => __('Filters'),
            'library' => __('Library'),
        ],
    ];
@endphp

<section class="relative bg-[#041416] py-10 sm:py-14">
    <div
        id="{{ $playerId }}"
        class="relative mx-auto max-w-7xl px-4 sm:px-6"
        data-quran-player='@json($config)'
    >
        <div class="absolute inset-x-4 top-0 h-full rounded-[2rem] bg-[radial-gradient(circle_at_top,rgba(44,194,149,0.18),transparent_35%),linear-gradient(135deg,#0a272a_0%,#051215_60%,#040d0f_100%)] shadow-[0_24px_80px_rgba(0,0,0,0.28)] sm:inset-x-6"></div>

        <div class="relative z-10 overflow-hidden rounded-[2rem] border border-white/10 p-4 text-white shadow-[inset_0_1px_0_rgba(255,255,255,0.04)] sm:p-6 lg:p-8">
            @if($config['title'])
                <div class="border-b border-white/10 pb-6">
                    <div class="min-w-0">
                        <h2 class="mt-3 text-2xl font-semibold tracking-tight text-white sm:text-3xl lg:text-4xl">{{ $config['title'] }}</h2>
                        @if(!empty($config['intro']))
                            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-300 sm:text-base">{{ $config['intro'] }}</p>
                        @endif
                    </div>
                </div>
            @endif


            <div class="mt-6 space-y-4 sm:space-y-5">
                <div class="rounded-[1.4rem] border border-white/10 bg-white/5 p-3 backdrop-blur sm:rounded-[1.6rem] sm:p-4">
                    <div class="rounded-[1.2rem] border border-white/10 bg-[#071d20]/80 p-3 sm:rounded-[1.4rem] sm:p-5">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0">
                                <div class="text-xs uppercase tracking-[0.3em] text-amber-300/80" data-active-type>{{ __('Recitations') }}</div>
                                <h3 class="mt-2 break-words text-lg font-semibold text-white sm:text-2xl" data-active-title>{{ __('Choose a reciter') }}</h3>
                                <p class="mt-1.5 text-sm text-slate-300" data-active-subtitle></p>
                            </div>

                            <span class="inline-flex w-fit items-center rounded-full border border-emerald-300/20 bg-emerald-300/10 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.24em] text-emerald-100 sm:text-[11px]">
                                {{ __('Playing Now') }}
                            </span>
                        </div>

                        <div class="mt-4 rounded-[1.15rem] border border-white/10 bg-[#021012] p-2 sm:mt-5 sm:rounded-[1.3rem] sm:p-3" wire:ignore>
                            <audio controls playsinline preload="metadata" data-audio-element></audio>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-2 sm:mt-4">
                            <button type="button" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-slate-100 transition hover:border-amber-300/40 hover:bg-amber-300/10 disabled:cursor-not-allowed disabled:opacity-50" data-action="previous">
                                <x-icon name="heroicon-o-chevron-left" class="h-4 w-4 rtl:rotate-180" />
                                <span>{{ __('Previous') }}</span>
                            </button>
                            <button type="button" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-slate-100 transition hover:border-amber-300/40 hover:bg-amber-300/10 disabled:cursor-not-allowed disabled:opacity-50" data-action="next">
                                <span>{{ __('Next') }}</span>
                                <x-icon name="heroicon-o-chevron-right" class="h-4 w-4 rtl:rotate-180" />
                            </button>
                        </div>
                    </div>
                </div>

                <div data-player-toolbar wire:ignore></div>
                <div data-player-content wire:ignore></div>
            </div>
        </div>
    </div>
</section>
