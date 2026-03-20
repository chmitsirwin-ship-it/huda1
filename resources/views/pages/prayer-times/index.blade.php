@extends('layouts.public')
@section('title', __('Prayer Times'))
@section('content')

    @if($today)
        <div class="bg-emerald-900 text-white py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">

                <div class="mb-6">
                    <p class="text-xs font-medium text-emerald-400 uppercase tracking-widest mb-1">{{ __("Today's Prayer Times") }}</p>
                    <h2 class="text-xl font-semibold text-white">{{ $today->date->translatedFormat('l, F j, Y') }}</h2>
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
                            <div class="text-xs font-medium text-emerald-400 uppercase tracking-wider mb-2">{{ $prayer['label'] }}</div>
                            <div class="text-lg font-semibold text-white tabular-nums">
                                {{ $prayer['adhan'] ? \Carbon\Carbon::parse($prayer['adhan'])->translatedFormat('h:i A') : '—' }}
                            </div>
                            @if($prayer['iqamah'])
                                <div class="text-xs text-emerald-300 mt-1.5 tabular-nums">
                                    {{ \Carbon\Carbon::parse($prayer['iqamah'])->translatedFormat('h:i A') }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($today->jummah_time)
                    <div class="mt-5 inline-flex items-center gap-2.5 bg-amber-500/15 border border-amber-400/25 rounded-lg px-4 py-2.5">
                        <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm text-amber-200 font-medium">
                            {{ __("Jumu'ah") }}: {{ \Carbon\Carbon::parse($today->jummah_time)->translatedFormat('h:i A') }}
                        </span>
                    </div>
                @endif

            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <h2 class="text-lg font-semibold text-neutral-900">
                {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}
            </h2>
            <div class="flex items-center gap-2">
                @php
                    $prevMonth = $month == 1 ? 12 : $month - 1;
                    $prevYear  = $month == 1 ? $year - 1 : $year;
                    $nextMonth = $month == 12 ? 1 : $month + 1;
                    $nextYear  = $month == 12 ? $year + 1 : $year;
                @endphp
                <a href="{{ route('prayer-times.index', ['year' => $prevYear, 'month' => $prevMonth]) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-neutral-200 text-neutral-600 hover:bg-neutral-50 hover:border-neutral-300 transition-colors text-sm font-medium">

                    <x-icon name="heroicon-o-chevron-left" class="rtl:rotate-180 w-3.5 h-3.5"/>
                    {{ __('Previous') }}
                </a>
                <a href="{{ route('prayer-times.index', ['year' => $nextYear, 'month' => $nextMonth]) }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-neutral-200 text-neutral-600 hover:bg-neutral-50 hover:border-neutral-300 transition-colors text-sm font-medium">
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
                        @php $isToday = $today && $pt->date->isSameDay($today->date); @endphp
                        <tr class="{{ $isToday ? 'bg-emerald-50' : 'bg-white hover:bg-neutral-50' }} transition-colors">
                            <td class="px-3 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                        <span class="font-medium {{ $isToday ? 'text-emerald-800' : 'text-neutral-800' }}">
                                            {{ $pt->date->translatedFormat('j M Y') }}
                                        </span>
                                    @if($isToday)
                                        <span class="text-[10px] font-medium bg-emerald-100 text-emerald-700 border border-emerald-200 rounded px-1.5 py-0.5 leading-none">
                                                {{ __('Today') }}
                                            </span>
                                    @endif
                                </div>
                                <div class="text-xs text-neutral-400 mt-0.5">{{ $pt->date->translatedFormat('l') }}</div>
                            </td>

                            @foreach(['fajr', 'sunrise', 'dhuhr', 'asr', 'maghrib', 'isha'] as $prayer)
                                @php
                                    $adhan = $prayer === 'sunrise'
                                        ? $pt->sunrise
                                        : $pt->{$prayer . '_adhan'};
                                    $iqamah = $prayer === 'sunrise'
                                        ? null
                                        : $pt->{$prayer . '_iqamah'};
                                @endphp
                                <td class="px-3 py-3 text-center tabular-nums">
                                    <div class="{{ $isToday ? 'text-emerald-800' : 'text-neutral-700' }}">
                                        {{ $adhan ? \Carbon\Carbon::parse($adhan)->translatedFormat('h:i') : '—' }}
                                    </div>
                                    @if($iqamah)
                                        <div class="text-[11px] {{ $isToday ? 'text-emerald-500' : 'text-neutral-400' }} mt-0.5">
                                            {{ \Carbon\Carbon::parse($iqamah)->translatedFormat('h:i') }}
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
