@php
    $data   = $block['data'] ?? $data ?? [];
    $today  = \App\Models\PrayerTime::where('date', today()->toDateString())->first();
    $style  = $data['style'] ?? 'card';

    $sunIcon  = 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z';
    $moonIcon = 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z';
    $jummahIcon = 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z';

    $prayers = [
        ['key' => 'fajr',    'label' => __('Fajr'),    'icon' => $sunIcon],
        ['key' => 'sunrise', 'label' => __('Sunrise'), 'icon' => $sunIcon],
        ['key' => 'dhuhr',   'label' => __('Dhuhr'),   'icon' => $sunIcon],
        ['key' => 'asr',     'label' => __('Asr'),     'icon' => $sunIcon],
        ['key' => 'maghrib', 'label' => __('Maghrib'), 'icon' => $moonIcon],
        ['key' => 'isha',    'label' => __('Isha'),    'icon' => $moonIcon],
    ];

    $hasJummah    = $today && ($today->jummah_time ?? null);
    $jummahTime   = $hasJummah ? \App\Support\LocalizedDate::time($today->jummah_time) : null;
    $jummahIqamah = ($today && ($today->jummah_iqamah ?? null))
                        ? \App\Support\LocalizedDate::time($today->jummah_iqamah)
                        : null;
    $khutbaTime   = ($today && ($today->jummah_khutba_time ?? null))
                        ? \App\Support\LocalizedDate::time($today->jummah_khutba_time)
                        : null;
    $isFriday     = today()->isFriday();
@endphp

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 text-emerald-600 font-medium text-sm uppercase tracking-widest mb-3">
                <span class="w-8 h-px bg-emerald-600"></span>
                {{ __('Daily Schedule') }}
                <span class="w-8 h-px bg-emerald-600"></span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900">{{ __('Prayer Times') }}</h2>
            @if($today)
                <p class="text-neutral-500 mt-2">{{ \App\Support\LocalizedDate::date($today->date) }}</p>
                <p class="text-neutral-400 text-sm mt-0.5">{{ \App\Support\LocalizedDate::hijri($today->date) }}</p>
            @endif
        </div>

        {{-- No data fallback --}}
        @if(!$today)
            <div class="text-center py-12 text-neutral-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>{{ __('Prayer times not available for today.') }}</p>
            </div>

            {{-- ───────────────────────── DETAILED ───────────────────────── --}}
        @elseif($style === 'detailed')
            <div class="max-w-3xl mx-auto overflow-hidden rounded-2xl border border-neutral-100 shadow-sm">
                <table class="w-full">
                    <thead>
                    <tr class="bg-emerald-600 text-white">
                        <th class="px-6 py-4 text-left font-semibold">{{ __('Prayer') }}</th>
                        <th class="px-6 py-4 text-center font-semibold">{{ __('Adhan') }}</th>
                        <th class="px-6 py-4 text-center font-semibold">{{ __('Iqamah') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($prayers as $i => $prayer)
                        @php
                            $adhan  = \App\Support\LocalizedDate::time($today->{$prayer['key'].'_adhan'} ?? $today->{$prayer['key']} ?? null);
                            $iqamah = \App\Support\LocalizedDate::time($today->{$prayer['key'].'_iqamah'} ?? null);
                        @endphp
                        <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-emerald-50/50' }} border-b border-neutral-100">
                            <td class="px-6 py-4 font-semibold text-neutral-800">{{ $prayer['label'] }}</td>
                            <td class="px-6 py-4 text-center text-emerald-700 font-medium tabular-nums">{{ $adhan ?? '—' }}</td>
                            <td class="px-6 py-4 text-center text-amber-600 font-medium tabular-nums">{{ $iqamah ?? '—' }}</td>
                        </tr>

                        {{-- Jumu'ah rows injected after Dhuhr --}}
                        @if($prayer['key'] === 'dhuhr' && $hasJummah)
                            {{-- Jumu'ah Adhan row --}}
                            <tr class="bg-amber-50/60 border-b border-neutral-100">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-neutral-800">{{ __("Jumu'ah") }}</span>
                                        @if($isFriday)
                                            <span class="text-xs font-medium text-white bg-amber-500 rounded-full px-2 py-0.5">
                                                {{ __('Today') }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-emerald-700 font-medium tabular-nums">
                                    {{ $jummahTime ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-center text-amber-600 font-medium tabular-nums">
                                    {{ $jummahIqamah ?? '—' }}
                                </td>
                            </tr>
                            {{-- Khutbah row --}}
                            @if($khutbaTime)
                                <tr class="bg-amber-50/40 border-b border-neutral-100">
                                    <td class="px-6 py-4 pl-10 text-neutral-500 text-sm font-medium">
                                        ↳ {{ __('Khutbah') }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-amber-600 font-medium tabular-nums text-sm">
                                        {{ $khutbaTime }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-neutral-400 text-sm">—</td>
                                </tr>
                            @endif
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ───────────────────────── COMPACT ───────────────────────── --}}
        @elseif($style === 'compact')
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($prayers as $prayer)
                    @php
                        $adhan  = \App\Support\LocalizedDate::time($today->{$prayer['key'].'_adhan'} ?? $today->{$prayer['key']} ?? null);
                        $iqamah = \App\Support\LocalizedDate::time($today->{$prayer['key'].'_iqamah'} ?? null);
                    @endphp
                    <div class="bg-emerald-50 rounded-xl p-4 text-center border border-emerald-100
                                hover:border-emerald-300 hover:shadow-md transition-all duration-200 group">
                        <p class="text-sm font-semibold text-neutral-500 uppercase tracking-wide mb-2
                                  group-hover:text-emerald-600 transition-colors">
                            {{ $prayer['label'] }}
                        </p>
                        <p class="text-xl font-bold text-emerald-700 tabular-nums">{{ $adhan ?? '—' }}</p>
                        @if($iqamah)
                            <p class="text-xs text-amber-600 font-medium mt-1 tabular-nums">
                                {{ __('Iqamah') }}: {{ $iqamah }}
                            </p>
                        @endif
                    </div>

                    {{-- Jumu'ah card injected after Dhuhr --}}
                    @if($prayer['key'] === 'dhuhr' && $hasJummah)
                        <div class="rounded-xl p-4 text-center border transition-all duration-200
                                    {{ $isFriday
                                        ? 'bg-amber-50 border-amber-400 ring-2 ring-amber-300/40'
                                        : 'bg-amber-50/60 border-amber-100 hover:border-amber-300 hover:shadow-md' }}">
                            <p class="text-sm font-semibold text-amber-700 uppercase tracking-wide mb-1">
                                {{ __("Jumu'ah") }}
                            </p>
                            @if($isFriday)
                                <span class="inline-block text-xs text-amber-500 mb-2">{{ __('Today') }}</span>
                            @endif
                            <p class="text-xl font-bold text-amber-700 tabular-nums">{{ $jummahTime }}</p>
                            @if($jummahIqamah)
                                <p class="text-xs text-amber-600 font-medium mt-1 tabular-nums">
                                    {{ __('Iqamah') }}: {{ $jummahIqamah }}
                                </p>
                            @endif
                            @if($khutbaTime)
                                <p class="text-xs text-amber-600 font-medium mt-1 tabular-nums">
                                    {{ __('Khutbah') }}: {{ $khutbaTime }}
                                </p>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- ───────────────────────── CARD (default) ───────────────────────── --}}
        @else
            <div class="max-w-2xl mx-auto bg-gradient-to-br from-emerald-600 to-emerald-800
                        rounded-2xl shadow-xl shadow-emerald-900/20 overflow-hidden">

                <div class="px-8 pt-8 pb-6 border-b border-emerald-500/30">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-white font-bold text-xl">{{ __("Today's Prayers") }}</h3>
                            <p class="text-emerald-200 text-sm mt-1">
                                {{ \App\Support\LocalizedDate::date($today->date) }}
                            </p>
                            <p class="text-emerald-300/70 text-xs mt-0.5">
                                {{ \App\Support\LocalizedDate::hijri($today->date) }}
                            </p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $sunIcon }}"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="divide-y divide-emerald-500/20">
                    @foreach($prayers as $prayer)
                        @php
                            $adhan  = \App\Support\LocalizedDate::time($today->{$prayer['key'].'_adhan'} ?? $today->{$prayer['key']} ?? null);
                            $iqamah = \App\Support\LocalizedDate::time($today->{$prayer['key'].'_iqamah'} ?? null);
                        @endphp
                        <div class="flex items-center justify-between px-8 py-4 hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-emerald-300 shrink-0" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $prayer['icon'] }}"/>
                                </svg>
                                <span class="text-emerald-100 font-medium">{{ $prayer['label'] }}</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-white font-bold text-lg tabular-nums">{{ $adhan ?? '—' }}</span>
                                @if($iqamah)
                                    <span class="text-amber-300 text-sm tabular-nums px-2 py-0.5 rounded bg-amber-400/10">
                                        {{ $iqamah }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Jumu'ah rows injected after Dhuhr --}}
                        @if($prayer['key'] === 'dhuhr' && $hasJummah)
                            {{-- Jumu'ah Adhan row --}}
                            <div class="flex items-center justify-between px-8 py-4 transition-colors
                                        {{ $isFriday ? 'bg-amber-400/10' : 'hover:bg-white/5' }}">
                                <div class="flex items-center gap-3">
                                    <svg class="w-4 h-4 text-amber-300 shrink-0" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $jummahIcon }}"/>
                                    </svg>
                                    <span class="text-amber-200 font-medium">
                                        {{ __("Jumu'ah") }}
                                        @if($isFriday)
                                            <span class="ml-2 text-xs bg-amber-400/20 text-amber-300 rounded-full px-2 py-0.5">
                                                {{ __('Today') }}
                                            </span>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-amber-200 font-bold text-lg tabular-nums">
                                        {{ $jummahTime ?? '—' }}
                                    </span>
                                    @if($jummahIqamah)
                                        <span class="text-amber-300 text-sm tabular-nums px-2 py-0.5 rounded bg-amber-400/10">
                                            {{ $jummahIqamah }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Khutbah row --}}
                            @if($khutbaTime)
                                <div class="flex items-center justify-between px-8 py-3
                                            {{ $isFriday ? 'bg-amber-400/5' : 'hover:bg-white/5' }} transition-colors">
                                    <div class="flex items-center gap-3 pl-7">
                                        <span class="text-amber-300/70 text-sm font-medium">
                                            ↳ {{ __('Khutbah') }}
                                        </span>
                                    </div>
                                    <span class="text-amber-300 text-sm font-medium tabular-nums px-2 py-0.5 rounded bg-amber-400/10">
                                        {{ $khutbaTime }}
                                    </span>
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</section>