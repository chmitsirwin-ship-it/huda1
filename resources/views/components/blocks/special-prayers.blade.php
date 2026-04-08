@php
    $data = $block['data'] ?? $data ?? [];
    $limit = (int) ($data['limit'] ?? 10);
    $title = $data['title'] ?? __('Special Prayers');

    $prayers = \App\Models\SpecialPrayer::where('date', '>=', today())
        ->orderBy('date')
        ->orderBy('time')
        ->limit($limit)
        ->get();

    $grouped = $prayers->groupBy(fn ($p) => $p->date->toDateString());
@endphp

@if($prayers->isNotEmpty())
    <section class="relative py-10 overflow-hidden bg-emerald-950">
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-700 via-emerald-400 to-emerald-700"></div>

        <div class="absolute inset-0 overflow-hidden opacity-[0.01] pointer-events-none" aria-hidden="true">
            <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="sp-pattern" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                        <polygon points="40,0 80,40 40,80 0,40" fill="white"/>
                        <polygon points="40,10 70,40 40,70 10,40" fill="none" stroke="white" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#sp-pattern)"/>
            </svg>
        </div>

        <div class="relative max-w-4xl mx-auto px-4">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <div class="h-px w-12 bg-gradient-to-r from-transparent to-emerald-400"></div>
                    <svg class="w-5 h-5 text-emerald-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <div class="h-px w-12 bg-gradient-to-l from-transparent to-emerald-400"></div>
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-wide">{{ $title }}</h2>
            </div>

            <div class="space-y-3">
                @foreach($grouped as $dateStr => $datePrayers)
                    @php
                        $dateObj = \Carbon\Carbon::parse($dateStr);
                        $isToday = $dateObj->isToday();
                    @endphp
                    <div class="rounded-xl {{ $isToday ? 'bg-emerald-500/10 ring-1 ring-emerald-400/30' : 'bg-white/5' }} p-3">
                        <div class="flex items-center gap-2 mb-2 pb-2 border-b {{ $isToday ? 'border-emerald-400/20' : 'border-white/10' }}">
                            @if($isToday)
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            @endif
                            <span class="text-xs font-semibold {{ $isToday ? 'text-emerald-400' : 'text-emerald-300/60' }}">
                                {{ \App\Support\LocalizedDate::weekday($dateObj) }}, {{ \App\Support\LocalizedDate::date($dateObj) }}
                            </span>
                            <span class="text-[10px] text-emerald-300/40">{{ \App\Support\LocalizedDate::hijri($dateObj) }}</span>
                            @if($isToday)
                                <span class="bg-emerald-400 text-emerald-950 text-[9px] font-black px-1.5 py-0.5 rounded-full uppercase ms-auto">{{ __('Today') }}</span>
                            @endif
                        </div>
                        <div class="space-y-1">
                            @foreach($datePrayers as $prayer)
                                <div class="flex items-center justify-between py-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-white font-medium">{{ $prayer->name }}</span>
                                        <span class="text-[9px] px-1.5 py-0.5 rounded-full font-semibold uppercase bg-emerald-500/20 text-emerald-300">{{ $prayer->type->getLabel() }}</span>
                                    </div>
                                    <span class="text-sm text-white font-bold tabular-nums">
                                        {{ \Carbon\Carbon::parse($prayer->time)->format('g:i A') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex items-center justify-center gap-2">
                <div class="h-px w-16 bg-gradient-to-r from-transparent to-emerald-500/40"></div>
                <span class="text-emerald-500/40 text-lg">&#9734;</span>
                <div class="h-px w-16 bg-gradient-to-l from-transparent to-emerald-500/40"></div>
            </div>
        </div>
    </section>
@endif
