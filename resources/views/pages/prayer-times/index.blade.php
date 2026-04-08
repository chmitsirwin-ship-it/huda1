@extends('layouts.public')
@section('title', __('Prayer Times'))
@section('content')

    @if($today)
        <div class="bg-emerald-900 text-white py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">

                <div class="mb-6">
                    <p class="text-xs font-medium text-emerald-400 uppercase tracking-widest mb-1">
                        {{ __("Today's Prayer Times") }}
                    </p>
                    <h2 class="text-xl font-semibold text-white">
                        {{ \App\Support\LocalizedDate::date($today->date) }}
                    </h2>
                    <p class="text-emerald-300/80 text-sm mt-0.5">{{ \App\Support\LocalizedDate::hijri($today->date) }}</p>
                </div>

                @php
                    $prayers = [
                        ['key' => 'fajr',    'label' => __('Fajr'),    'adhan' => $today->fajr_adhan,    'iqamah' => $today->fajr_iqamah],
                        ['key' => 'sunrise', 'label' => __('Sunrise'), 'adhan' => $today->sunrise,       'iqamah' => null],
                        ['key' => 'dhuhr',   'label' => __('Dhuhr'),   'adhan' => $today->dhuhr_adhan,   'iqamah' => $today->dhuhr_iqamah],
                        ['key' => 'asr',     'label' => __('Asr'),     'adhan' => $today->asr_adhan,     'iqamah' => $today->asr_iqamah],
                        ['key' => 'maghrib', 'label' => __('Maghrib'), 'adhan' => $today->maghrib_adhan, 'iqamah' => $today->maghrib_iqamah],
                        ['key' => 'isha',    'label' => __('Isha'),    'adhan' => $today->isha_adhan,    'iqamah' => $today->isha_iqamah],
                    ];
                @endphp

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    @foreach($prayers as $prayer)
                        <div class="bg-white/8 border border-white/10 rounded-xl p-4 text-center">
                            <div class="text-xs font-medium text-emerald-400 uppercase tracking-wider mb-2">
                                {{ $prayer['label'] }}
                            </div>
                            <div class="text-lg font-semibold text-white tabular-nums">
                                {{ \App\Support\LocalizedDate::time($prayer['adhan']) ?? '—' }}
                            </div>
                            @if($prayer['iqamah'])
                                <div class="text-xs text-emerald-300 mt-1.5 tabular-nums">
                                    {{ \App\Support\LocalizedDate::time($prayer['iqamah']) }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Jumu'ah strip — shown whenever jummah_time is set --}}
                @if($today->jummah_time)
                    <div class="mt-5 flex flex-wrap items-center gap-3">

                        {{-- Jumu'ah time --}}
                        <div class="inline-flex items-center gap-2.5 bg-amber-500/15 border border-amber-400/25
                                    rounded-lg px-4 py-2.5">
                            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857
                                         M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857
                                         m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div class="leading-tight">
                                <p class="text-[10px] font-medium text-amber-400 uppercase tracking-wider">
                                    {{ __("Jumu'ah") }}
                                </p>
                                <p class="text-sm font-semibold text-amber-200 tabular-nums">
                                    {{ \App\Support\LocalizedDate::time($today->jummah_time) }}
                                </p>
                            </div>
                        </div>
                        {{-- Khutbah time --}}
                        @if($today->jummah_khutba_time)
                            <div class="inline-flex items-center gap-2.5 bg-amber-500/10 border border-amber-400/20
                                        rounded-lg px-4 py-2.5">
                                <svg class="w-4 h-4 text-amber-400/70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4
                                             m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                </svg>
                                <div class="leading-tight">
                                    <p class="text-[10px] font-medium text-amber-400/80 uppercase tracking-wider">
                                        {{ __('Khutbah') }}
                                    </p>
                                    <p class="text-sm font-semibold text-amber-200/80 tabular-nums">
                                        {{ \App\Support\LocalizedDate::time($today->jummah_khutba_time) }}
                                    </p>
                                </div>
                            </div>
                        @endif
                        {{-- Jumu'ah Iqamah time --}}
                        @if($today->jummah_iqamah)
                            <div class="inline-flex items-center gap-2.5 bg-amber-500/10 border border-amber-400/20
                                        rounded-lg px-4 py-2.5">
                                <svg class="w-4 h-4 text-amber-400/70 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="leading-tight">
                                    <p class="text-[10px] font-medium text-amber-400/80 uppercase tracking-wider">
                                        {{ __('Iqamah') }}
                                    </p>
                                    <p class="text-sm font-semibold text-amber-200/80 tabular-nums">
                                        {{ \App\Support\LocalizedDate::time($today->jummah_iqamah) }}
                                    </p>
                                </div>
                            </div>
                        @endif

                    </div>
                @endif

            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <h2 class="text-lg font-semibold text-neutral-900">
                {{ \App\Support\LocalizedDate::monthYear(\Carbon\Carbon::createFromDate($year, $month, 1)) }}
            </h2>
            <div class="flex items-center gap-2">
                @php
                    $prevMonth = $month == 1 ? 12 : $month - 1;
                    $prevYear  = $month == 1 ? $year - 1 : $year;
                    $nextMonth = $month == 12 ? 1 : $month + 1;
                    $nextYear  = $month == 12 ? $year + 1 : $year;
                @endphp
                <a href="{{ route('prayer-times.index', ['year' => $prevYear, 'month' => $prevMonth]) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-neutral-200
                          text-neutral-600 hover:bg-neutral-50 hover:border-neutral-300 transition-colors text-sm font-medium">
                    <x-icon name="heroicon-o-chevron-left" class="rtl:rotate-180 w-3.5 h-3.5"/>
                    {{ __('Previous') }}
                </a>
                <a href="{{ route('prayer-times.index', ['year' => $nextYear, 'month' => $nextMonth]) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-neutral-200
                          text-neutral-600 hover:bg-neutral-50 hover:border-neutral-300 transition-colors text-sm font-medium">
                    {{ __('Next') }}
                    <x-icon name="heroicon-o-chevron-right" class="rtl:rotate-180 w-3.5 h-3.5"/>
                </a>
            </div>
        </div>

        <div class="border border-neutral-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                    <tr class="bg-neutral-50 border-b border-neutral-200">
                        <th class="px-3 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wide whitespace-nowrap">{{ __('Date') }}</th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wide">{{ __('Fajr') }}</th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wide">{{ __('Sunrise') }}</th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wide">{{ __('Dhuhr') }}</th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wide">{{ __('Asr') }}</th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wide">{{ __('Maghrib') }}</th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-neutral-500 uppercase tracking-wide">{{ __('Isha') }}</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                    @foreach($prayerTimes as $pt)
                        @php
                            $isToday   = $today && $pt->date->isSameDay($today->date);
                            $isFriday  = $pt->date->isFriday();
                            $hasJummah = $isFriday && ($pt->jummah_time ?? null);
                        @endphp
                        <tr class="{{ $isToday ? 'bg-emerald-50' : ($isFriday ? 'bg-amber-50/40' : 'bg-white hover:bg-neutral-50') }} transition-colors">

                            {{-- Date cell --}}
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                        <span class="font-medium {{ $isToday ? 'text-emerald-800' : ($isFriday ? 'text-amber-800' : 'text-neutral-800') }}">
                                            {{ \App\Support\LocalizedDate::date($pt->date) }}
                                            <span class="block text-[10px] font-normal {{ $isToday ? 'text-emerald-600' : ($isFriday ? 'text-amber-600' : 'text-neutral-400') }}">{{ \App\Support\LocalizedDate::hijri($pt->date) }}</span>
                                        </span>
                                    @if($isToday)
                                        <span class="text-[10px] font-medium bg-emerald-100 text-emerald-700
                                                         border border-emerald-200 rounded px-1.5 py-0.5 leading-none">
                                                {{ __('Today') }}
                                            </span>
                                    @elseif($isFriday)
                                        <span class="text-[10px] font-medium bg-amber-100 text-amber-700
                                                         border border-amber-200 rounded px-1.5 py-0.5 leading-none">
                                                {{ __('Friday') }}
                                            </span>
                                    @endif
                                </div>
                                <div class="text-xs text-neutral-400 mt-0.5">
                                    {{ \App\Support\LocalizedDate::weekday($pt->date) }}
                                </div>
                            </td>

                            {{-- Prayer cells --}}
                            @foreach(['fajr', 'sunrise', 'dhuhr', 'asr', 'maghrib', 'isha'] as $prayer)
                                @php
                                    $adhan  = $prayer === 'sunrise' ? $pt->sunrise : $pt->{$prayer.'_adhan'};
                                    $iqamah = $prayer === 'sunrise' ? null : $pt->{$prayer.'_iqamah'};
                                @endphp
                                <td class="px-3 py-3 text-center tabular-nums">
                                    <div class="{{ $isToday ? 'text-emerald-800' : 'text-neutral-700' }} font-medium">
                                        {{ \App\Support\LocalizedDate::time($adhan) ?? '—' }}
                                    </div>
                                    @if($iqamah)
                                        <div class="text-[11px] {{ $isToday ? 'text-emerald-500' : 'text-neutral-400' }} mt-0.5 tabular-nums">
                                            {{ \App\Support\LocalizedDate::time($iqamah) }}
                                        </div>
                                    @endif

                                    {{-- Jumu'ah sub-row inside Dhuhr cell --}}
                                    @if($prayer === 'dhuhr' && $hasJummah)
                                        <div class="mt-1.5 pt-1.5 border-t border-amber-200/60 space-y-0.5">
                                            <div class="text-[10px] font-medium text-amber-600 uppercase tracking-wide">
                                                {{ __("Jumu'ah") }}
                                            </div>
                                            <div class="text-[11px] font-semibold text-amber-700 tabular-nums">
                                                {{ \App\Support\LocalizedDate::time($pt->jummah_time) }}
                                            </div>
                                            @if($pt->jummah_iqamah ?? null)
                                                <div class="text-[10px] text-amber-500 tabular-nums">
                                                    {{ \App\Support\LocalizedDate::time($pt->jummah_iqamah) }}
                                                </div>
                                            @endif
                                            @if($pt->jummah_khutba_time ?? null)
                                                <div class="text-[10px] text-amber-500 tabular-nums">
                                                    {{ __('Khutbah') }}: {{ \App\Support\LocalizedDate::time($pt->jummah_khutba_time) }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            @endforeach

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection