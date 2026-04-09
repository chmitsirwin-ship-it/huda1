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

                @if($today->jummah_time)
                    <div class="mt-5 bg-amber-500/10 border border-amber-400/25 rounded-xl px-5 py-4 flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex items-center gap-3 sm:border-e border-amber-400/20 sm:pe-5">
                            <div class="w-9 h-9 rounded-lg bg-amber-400/15 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-medium text-amber-400 uppercase tracking-widest">{{ __("Jumu'ah") }}</p>
                                <p class="text-xs text-amber-300/70 mt-0.5">{{ __('Friday Prayer') }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-x-6 gap-y-3">

                            <div>
                                <p class="text-[10px] font-medium text-amber-400 uppercase tracking-wider">{{ __('Salah') }}</p>
                                <p class="text-base font-bold text-amber-100 tabular-nums mt-0.5">{{ \App\Support\LocalizedDate::time($today->jummah_time) }}</p>
                            </div>
                            @if($today->jummah_khutba_time)
                                <div>
                                    <p class="text-[10px] font-medium text-amber-400/70 uppercase tracking-wider">{{ __('Khutbah') }}</p>
                                    <p class="text-base font-bold text-amber-200 tabular-nums mt-0.5">{{ \App\Support\LocalizedDate::time($today->jummah_khutba_time) }}</p>
                                </div>
                            @endif
                            @if($today->jummah_iqamah)
                                <div>
                                    <p class="text-[10px] font-medium text-amber-400/70 uppercase tracking-wider">{{ __('Iqamah') }}</p>
                                    <p class="text-base font-bold text-amber-200 tabular-nums mt-0.5">{{ \App\Support\LocalizedDate::time($today->jummah_iqamah) }}</p>
                                </div>
                            @endif
                        </div>
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

                                    @if($prayer === 'dhuhr' && $hasJummah)
                                        <div class="mt-2 pt-2 border-t border-amber-200/50">
                                            <div class="text-[10px] font-semibold text-amber-600 uppercase tracking-wider mb-1.5">{{ __("Jumu'ah") }}</div>
                                            <div class="flex flex-wrap justify-center gap-x-3 gap-y-1">
                                                <div class="text-center">
                                                    <div class="text-[9px] text-amber-500 uppercase tracking-wide">{{ __('Salah') }}</div>
                                                    <div class="text-[12px] font-bold text-amber-700 tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_time) }}</div>
                                                </div>
                                                @if($pt->jummah_khutba_time ?? null)
                                                    <div class="text-center">
                                                        <div class="text-[9px] text-amber-400 uppercase tracking-wide">{{ __('Khutbah') }}</div>
                                                        <div class="text-[11px] font-medium text-amber-600 tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_khutba_time) }}</div>
                                                    </div>
                                                @endif
                                                @if($pt->jummah_iqamah ?? null)
                                                    <div class="text-center">
                                                        <div class="text-[9px] text-amber-400 uppercase tracking-wide">{{ __('Iqamah') }}</div>
                                                        <div class="text-[11px] font-medium text-amber-600 tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_iqamah) }}</div>
                                                    </div>
                                                @endif
                                            </div>
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

    @php
        $specialPrayers = \App\Models\SpecialPrayer::where('date', '>=', today())
            ->orderBy('date')
            ->orderBy('time')
            ->limit(10)
            ->get();
    @endphp

    @if($specialPrayers->isNotEmpty())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-10">
            <h2 class="text-lg font-semibold text-neutral-900 mb-4">{{ __('Upcoming Special Prayers') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($specialPrayers as $sp)
                    @php $isToday = $sp->date->isToday(); @endphp
                    <div class="relative border {{ $isToday ? 'border-emerald-300 bg-emerald-50' : 'border-neutral-200 bg-white' }} rounded-xl p-4 transition-colors hover:shadow-sm">
                        @if($isToday)
                            <span class="absolute top-3 right-3 text-[10px] font-medium bg-emerald-100 text-emerald-700 border border-emerald-200 rounded px-1.5 py-0.5">{{ __('Today') }}</span>
                        @endif
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center
                                {{ match($sp->type->value) {
                                    'ramadan' => 'bg-emerald-100',
                                    'eid' => 'bg-amber-100',
                                    'weekly' => 'bg-blue-100',
                                    default => 'bg-neutral-100',
                                } }}">
                                <svg class="w-5 h-5 {{ match($sp->type->value) {
                                    'ramadan' => 'text-emerald-600',
                                    'eid' => 'text-amber-600',
                                    'weekly' => 'text-blue-600',
                                    default => 'text-neutral-600',
                                } }}" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-neutral-900 text-sm">{{ $sp->name }}</h3>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium mt-1
                                    {{ match($sp->type->value) {
                                        'ramadan' => 'bg-emerald-100 text-emerald-700',
                                        'eid' => 'bg-amber-100 text-amber-700',
                                        'weekly' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-neutral-100 text-neutral-600',
                                    } }}">
                                    {{ $sp->type->getLabel() }}
                                </span>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1 text-xs">
                            <div class="flex justify-between">
                                <span class="text-neutral-500">{{ __('Date') }}</span>
                                <span class="text-neutral-700 font-medium">{{ \App\Support\LocalizedDate::date($sp->date) }} &middot; {{ \App\Support\LocalizedDate::weekday($sp->date) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-500">{{ __('Time') }}</span>
                                <span class="text-neutral-900 font-semibold">{{ \Carbon\Carbon::parse($sp->time)->format('g:i A') }}</span>
                            </div>
                            @if($sp->end_time)
                                <div class="flex justify-between">
                                    <span class="text-neutral-500">{{ __('Ends') }}</span>
                                    <span class="text-neutral-700">{{ \Carbon\Carbon::parse($sp->end_time)->format('g:i A') }}</span>
                                </div>
                            @endif
                        </div>
                        @if($sp->description)
                            <p class="text-neutral-500 text-xs mt-2">{{ $sp->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@endsection