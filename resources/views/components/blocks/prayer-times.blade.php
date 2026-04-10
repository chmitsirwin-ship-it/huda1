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

    $hasJummah    = $today && ($today->jummah_adhan ?? null);
    $jummahTime   = $hasJummah ? \App\Support\LocalizedDate::time($today->jummah_adhan) : null;
    $jummahIqamah = ($today && ($today->jummah_iqamah ?? null))
                        ? \App\Support\LocalizedDate::time($today->jummah_iqamah)
                        : null;
    $khutbaTime   = ($today && ($today->jummah_khutba_time ?? null))
                        ? \App\Support\LocalizedDate::time($today->jummah_khutba_time)
                        : null;
    $isFriday     = today()->isFriday();

    $showJummah  = (bool) ($data['show_jummah'] ?? true);
    $showNextSalahCountdown = (bool) ($data['show_next_salah_countdown'] ?? true);
    $nextJummah  = $showJummah
        ? \App\Models\PrayerTime::whereNotNull('jummah_adhan')
            ->where('date', '>=', today()->toDateString())
            ->orderBy('date')
            ->first()
        : null;

    $nextSalah = null;
    $now = now();

    if ($today) {
        $todaySalahs = collect($prayers)
            ->reject(fn (array $prayer): bool => $prayer['key'] === 'sunrise')
            ->map(function (array $prayer) use ($today) {
                $rawTime = $today->{$prayer['key'].'_adhan'} ?? $today->{$prayer['key']} ?? null;

                if (!$rawTime) {
                    return null;
                }

                return [
                    'label' => $prayer['label'],
                    'icon' => $prayer['icon'],
                    'time' => \Carbon\Carbon::parse($today->date->toDateString().' '.$rawTime),
                ];
            })
            ->filter()
            ->first(fn (array $prayer): bool => $prayer['time']->isFuture());

        if ($todaySalahs) {
            $nextSalah = $todaySalahs;
        } else {
            $tomorrowPrayerTime = \App\Models\PrayerTime::where('date', '>', today()->toDateString())
                ->orderBy('date')
                ->first();

            if ($tomorrowPrayerTime) {
                $tomorrowFajr = $tomorrowPrayerTime->fajr_adhan ?? $tomorrowPrayerTime->fajr ?? null;

                if ($tomorrowFajr) {
                    $nextSalah = [
                        'label' => __('Fajr'),
                        'icon' => $sunIcon,
                        'time' => \Carbon\Carbon::parse($tomorrowPrayerTime->date->toDateString().' '.$tomorrowFajr),
                    ];
                }
            }
        }
    }
@endphp

@if(\App\Support\PublicNavigation::isEnabled('prayer_times'))
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">

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
            <div class="max-w-3xl mx-auto">
                <div class="overflow-hidden rounded-2xl border border-neutral-100 shadow-sm md:hidden">
                    <div class="grid grid-cols-[minmax(0,1.2fr)_minmax(0,0.9fr)_minmax(0,0.9fr)] bg-emerald-600 text-white">
                        <div class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em]">{{ __('Prayer') }}</div>
                        <div class="px-2 py-3 text-center text-xs font-semibold uppercase tracking-[0.18em]">{{ __('Adhan') }}</div>
                        <div class="px-2 py-3 text-center text-xs font-semibold uppercase tracking-[0.18em]">{{ __('Iqamah') }}</div>
                    </div>
                    <div>
                        @foreach($prayers as $i => $prayer)
                            @php
                                $adhan  = \App\Support\LocalizedDate::time($today->{$prayer['key'].'_adhan'} ?? $today->{$prayer['key']} ?? null);
                                $iqamah = \App\Support\LocalizedDate::time($today->{$prayer['key'].'_iqamah'} ?? null);
                            @endphp
                            <div class="grid grid-cols-[minmax(0,1.2fr)_minmax(0,0.9fr)_minmax(0,0.9fr)] items-center border-b border-neutral-100 {{ $i % 2 === 0 ? 'bg-white' : 'bg-emerald-50/50' }}">
                                <div class="flex items-center gap-2 px-3 py-3">
                                    <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $prayer['icon'] }}"/>
                                    </svg>
                                    <span class="truncate text-sm font-semibold text-neutral-800">{{ $prayer['label'] }}</span>
                                </div>
                                <div class="px-2 py-3 text-center text-sm font-semibold text-emerald-700 tabular-nums">{{ $adhan ?? '—' }}</div>
                                <div class="px-2 py-3 text-center text-sm font-semibold text-amber-600 tabular-nums">{{ $iqamah ?? '—' }}</div>
                            </div>
                            @if($prayer['key'] === 'dhuhr' && $hasJummah)
                                <div class="grid grid-cols-[minmax(0,1.2fr)_minmax(0,0.9fr)_minmax(0,0.9fr)] items-center border-b border-neutral-100 bg-amber-50/70">
                                    <div class="flex items-center gap-2 px-3 py-3">
                                        <svg class="h-3.5 w-3.5 shrink-0 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $jummahIcon }}"/>
                                        </svg>
                                        <span class="truncate text-sm font-semibold text-neutral-800">{{ __("Jumu'ah") }}</span>
                                        @if($isFriday)
                                            <span class="rounded-full bg-amber-500 px-1.5 py-0.5 text-[10px] font-medium text-white">{{ __('Today') }}</span>
                                        @endif
                                    </div>
                                    <div class="px-2 py-3 text-center text-sm font-semibold text-emerald-700 tabular-nums">{{ $jummahTime ?? '—' }}</div>
                                    <div class="px-2 py-3 text-center text-sm font-semibold text-amber-600 tabular-nums">{{ $jummahIqamah ?? '—' }}</div>
                                </div>
                                @if($khutbaTime)
                                    <div class="grid grid-cols-[minmax(0,1.2fr)_minmax(0,0.9fr)_minmax(0,0.9fr)] items-center border-b border-neutral-100 bg-amber-50/40">
                                        <div class="px-3 py-2.5 text-sm font-medium text-neutral-500">↳ {{ __('Khutbah') }}</div>
                                        <div class="px-2 py-2.5 text-center text-sm font-semibold text-amber-700 tabular-nums">{{ $khutbaTime }}</div>
                                        <div class="px-2 py-2.5 text-center text-sm text-neutral-400">—</div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="hidden overflow-hidden rounded-2xl border border-neutral-100 shadow-sm md:block">
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
                            @if($prayer['key'] === 'dhuhr' && $hasJummah)
                                <tr class="bg-amber-50/60 border-b border-neutral-100">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span class="font-semibold text-neutral-800">{{ __("Jumu'ah") }}</span>
                                            @if($isFriday)
                                                <span class="text-xs font-medium text-white bg-amber-500 rounded-full px-2 py-0.5">{{ __('Today') }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-emerald-700 font-medium tabular-nums">{{ $jummahTime ?? '—' }}</td>
                                    <td class="px-6 py-4 text-center text-amber-600 font-medium tabular-nums">{{ $jummahIqamah ?? '—' }}</td>
                                </tr>
                                @if($khutbaTime)
                                    <tr class="bg-amber-50/40 border-b border-neutral-100">
                                        <td class="px-6 py-4 pl-10 text-neutral-500 text-sm font-medium">↳ {{ __('Khutbah') }}</td>
                                        <td class="px-6 py-4 text-center text-amber-600 font-medium tabular-nums text-sm">{{ $khutbaTime }}</td>
                                        <td class="px-6 py-4 text-center text-neutral-400 text-sm">—</td>
                                    </tr>
                                @endif
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ───────────────────────── COMPACT ───────────────────────── --}}
        @elseif($style === 'compact')
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-6">
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
                        <div class="col-span-2 rounded-xl border p-4 text-center transition-all duration-200 sm:col-span-1
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

                <div class="border-b border-emerald-500/30 px-5 pb-5 pt-6 sm:px-8 sm:pb-6 sm:pt-8">
                    <div class="flex items-start justify-between gap-4 sm:items-center">
                        <div>
                            <h3 class="text-white font-bold text-xl">{{ __("Today's Prayers") }}</h3>
                            <p class="text-emerald-200 text-sm mt-1">
                                {{ \App\Support\LocalizedDate::date($today->date) }}
                            </p>
                            <p class="text-emerald-300/70 text-xs mt-0.5">
                                {{ \App\Support\LocalizedDate::hijri($today->date) }}
                            </p>
                        </div>
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white/10">
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
                        <div class="px-5 py-4 transition-colors hover:bg-white/5 sm:px-8">
                            <div class="flex items-start justify-between gap-4 sm:items-center">
                                <div class="flex min-w-0 items-center gap-3">
                                    <svg class="w-4 h-4 shrink-0 text-emerald-300" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $prayer['icon'] }}"/>
                                    </svg>
                                    <span class="text-emerald-100 font-medium">{{ $prayer['label'] }}</span>
                                </div>
                                <span class="shrink-0 text-lg font-bold text-white tabular-nums sm:hidden">{{ $adhan ?? '—' }}</span>
                            </div>
                            <div class="mt-3 flex flex-wrap items-center gap-2 sm:mt-0 sm:justify-end sm:gap-4">
                                <span class="inline-flex items-center rounded-full bg-white/10 px-2.5 py-1 text-[11px] font-medium uppercase tracking-[0.2em] text-emerald-100/80 sm:hidden">{{ __('Adhan') }}</span>
                                <span class="text-base font-bold text-white tabular-nums sm:hidden">{{ $adhan ?? '—' }}</span>
                                <span class="hidden text-lg font-bold text-white tabular-nums sm:block">{{ $adhan ?? '—' }}</span>
                                @if($iqamah)
                                    <span class="rounded bg-amber-400/10 px-2 py-0.5 text-sm text-amber-300 tabular-nums">
                                        <span class="sm:hidden">{{ __('Iqamah') }}:</span>
                                        {{ $iqamah }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Jumu'ah rows injected after Dhuhr --}}
                        @if($prayer['key'] === 'dhuhr' && $hasJummah)
                            {{-- Jumu'ah Adhan row --}}
                            <div class="px-5 py-4 transition-colors sm:px-8 {{ $isFriday ? 'bg-amber-400/10' : 'hover:bg-white/5' }}">
                                <div class="flex items-start justify-between gap-4 sm:items-center">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <svg class="w-4 h-4 shrink-0 text-amber-300" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $jummahIcon }}"/>
                                        </svg>
                                        <span class="text-amber-200 font-medium">
                                            {{ __("Jumu'ah") }}
                                            @if($isFriday)
                                                <span class="ml-2 rounded-full bg-amber-400/20 px-2 py-0.5 text-xs text-amber-300">{{ __('Today') }}</span>
                                            @endif
                                        </span>
                                    </div>
                                    <span class="shrink-0 text-lg font-bold text-amber-200 tabular-nums sm:hidden">{{ $jummahTime ?? '—' }}</span>
                                </div>
                                <div class="mt-3 flex flex-wrap items-center gap-2 sm:mt-0 sm:justify-end sm:gap-4">
                                    <span class="inline-flex items-center rounded-full bg-amber-400/10 px-2.5 py-1 text-[11px] font-medium uppercase tracking-[0.2em] text-amber-200/80 sm:hidden">{{ __('Adhan') }}</span>
                                    <span class="text-base font-bold text-amber-200 tabular-nums sm:hidden">{{ $jummahTime ?? '—' }}</span>
                                    <span class="hidden text-lg font-bold text-amber-200 tabular-nums sm:block">{{ $jummahTime ?? '—' }}</span>
                                    @if($jummahIqamah)
                                        <span class="rounded bg-amber-400/10 px-2 py-0.5 text-sm text-amber-300 tabular-nums">
                                            <span class="sm:hidden">{{ __('Iqamah') }}:</span>
                                            {{ $jummahIqamah }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Khutbah row --}}
                            @if($khutbaTime)
                                <div class="flex flex-col gap-2 px-5 py-3 transition-colors sm:flex-row sm:items-center sm:justify-between sm:px-8
                                            {{ $isFriday ? 'bg-amber-400/5' : 'hover:bg-white/5' }}">
                                    <div class="flex items-center gap-3 sm:pl-7">
                                        <span class="text-amber-300/70 text-sm font-medium">
                                            ↳ {{ __('Khutbah') }}
                                        </span>
                                    </div>
                                    <span class="w-fit rounded bg-amber-400/10 px-2 py-0.5 text-sm font-medium text-amber-300 tabular-nums sm:ms-auto">
                                        {{ $khutbaTime }}
                                    </span>
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        @if($showNextSalahCountdown && $nextSalah)
            <div class="mx-auto mt-5 max-w-3xl">
                <div class="relative overflow-hidden rounded-2xl border border-emerald-200/70 bg-gradient-to-br from-emerald-50 via-white to-emerald-100/80 shadow-lg shadow-emerald-100/60"
                     x-data="{
                         target: new Date('{{ $nextSalah['time']->toIso8601String() }}').getTime(),
                         hours: '00', minutes: '00', seconds: '00', expired: false,
                         tick() {
                             const diff = this.target - Date.now();

                             if (diff <= 0) {
                                 this.expired = true;
                                 this.hours = '00';
                                 this.minutes = '00';
                                 this.seconds = '00';
                                 return;
                             }

                             this.hours = String(Math.floor(diff / 3600000)).padStart(2, '0');
                             this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                             this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
                         }
                     }"
                     x-init="tick(); setInterval(() => tick(), 1000)">
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(16,185,129,0.14),transparent_34%),radial-gradient(circle_at_bottom_left,rgba(5,150,105,0.12),transparent_28%)]"></div>
                    <div class="relative flex flex-col gap-4 px-4 py-4 sm:px-6 sm:py-5 lg:flex-row lg:items-center lg:justify-between lg:gap-6">
                        <div class="flex items-center gap-3 sm:gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-600 text-white shadow-sm shadow-emerald-200">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $nextSalah['icon'] }}"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.28em] text-emerald-600">{{ __('Next Salah') }}</p>
                                <div class="mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <h3 class="text-xl font-bold text-neutral-900 sm:text-2xl">{{ $nextSalah['label'] }}</h3>
                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200/80 tabular-nums">{{ \App\Support\LocalizedDate::time($nextSalah['time']) }}</span>
                                </div>
                                <p class="mt-1 text-sm text-neutral-500">{{ __('Begins in') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 sm:gap-3 lg:min-w-[290px]">
                            @foreach([['value' => 'hours', 'label' => __('Hours')], ['value' => 'minutes', 'label' => __('Minutes')], ['value' => 'seconds', 'label' => __('Seconds')]] as $unit)
                                <div class="rounded-2xl border border-white/80 bg-white/90 px-3 py-3 text-center shadow-sm shadow-emerald-100/60 backdrop-blur-sm">
                                    <template x-if="!expired">
                                        <span x-text="{{ $unit['value'] }}" class="block text-2xl font-black leading-none text-emerald-700 tabular-nums sm:text-3xl"></span>
                                    </template>
                                    <template x-if="expired">
                                        <span class="block text-2xl font-black leading-none text-emerald-700 tabular-nums sm:text-3xl">00</span>
                                    </template>
                                    <p class="mt-2 text-[10px] font-semibold uppercase tracking-[0.22em] text-neutral-500">{{ $unit['label'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Next Jummah Banner --}}
        @if($nextJummah)
            @php
                $isNextFriday   = $nextJummah->date->isToday();
                $daysUntil      = (int) today()->startOfDay()->diffInDays($nextJummah->date->startOfDay(), false);
                $nextJummahDate = \App\Support\LocalizedDate::date($nextJummah->date);
                $nextJummahDay  = \App\Support\LocalizedDate::weekday($nextJummah->date);
            @endphp
            <div class="mt-4 mx-auto max-w-3xl">
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-lg shadow-emerald-500/20">

                    {{-- decorative circles --}}
                    <div class="absolute -top-8 -end-8 w-40 h-40 rounded-full bg-white/10 pointer-events-none"></div>
                    <div class="absolute -bottom-10 -start-6 w-32 h-32 rounded-full bg-white/5 pointer-events-none"></div>

                    <div class="relative flex flex-col gap-5 px-4 py-5 sm:flex-row sm:items-center sm:gap-5 sm:px-8 sm:py-6">

                        {{-- icon + label --}}
                        <div class="flex items-center gap-3 sm:gap-4 sm:border-e sm:border-emerald-400/40 sm:pe-8">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white/15 sm:h-12 sm:w-12">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $jummahIcon }}"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[10px] font-semibold text-emerald-100/80 uppercase tracking-widest">
                                    {{ $isNextFriday ? __('Today') : ($daysUntil === 1 ? __('Tomorrow') : __('Upcoming')) }}
                                </p>
                                <p class="text-lg font-bold text-white leading-tight">{{ __("Jumu'ah") }}</p>
                                <p class="mt-0.5 text-xs text-emerald-100/70 sm:text-xs">{{ $nextJummahDay }}, {{ $nextJummahDate }}</p>
                            </div>
                        </div>

                        {{-- times --}}
                        <div class="grid flex-1 gap-3 {{ $nextJummah->jummah_khutba_time && $nextJummah->jummah_iqamah ? 'grid-cols-3' : 'grid-cols-2' }}">
                            <div class="rounded-2xl bg-white/16 px-4 py-3 shadow-sm ring-1 ring-white/10">
                                <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-100/75">{{ __('Salah Time') }}</p>
                                <p class="mt-1 text-2xl font-bold text-white tabular-nums">{{ \App\Support\LocalizedDate::time($nextJummah->jummah_adhan) }}</p>
                            </div>
                            @if($nextJummah->jummah_khutba_time)
                                <div class="rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/8">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-100/70">{{ __('Khutbah') }}</p>
                                    <p class="mt-1 text-xl font-bold text-white tabular-nums">{{ \App\Support\LocalizedDate::time($nextJummah->jummah_khutba_time) }}</p>
                                </div>
                            @endif
                            @if($nextJummah->jummah_iqamah)
                                <div class="rounded-2xl bg-white/10 px-4 py-3 ring-1 ring-white/8">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-emerald-100/70">{{ __('Iqamah') }}</p>
                                    <p class="mt-1 text-xl font-bold text-white tabular-nums">{{ \App\Support\LocalizedDate::time($nextJummah->jummah_iqamah) }}</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @endif

    </div>
</section>
@endif
