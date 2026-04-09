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

        @php
            $prevMonth = $month == 1 ? 12 : $month - 1;
            $prevYear  = $month == 1 ? $year - 1 : $year;
            $nextMonth = $month == 12 ? 1 : $month + 1;
            $nextYear  = $month == 12 ? $year + 1 : $year;
            $tablePrayers = [
                ['key' => 'fajr', 'label' => __('Fajr')],
                ['key' => 'sunrise', 'label' => __('Sunrise')],
                ['key' => 'dhuhr', 'label' => __('Dhuhr')],
                ['key' => 'asr', 'label' => __('Asr')],
                ['key' => 'maghrib', 'label' => __('Maghrib')],
                ['key' => 'isha', 'label' => __('Isha')],
            ];
        @endphp

        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-600">{{ __('Monthly Schedule') }}</p>
                <h2 class="mt-1 text-xl font-semibold text-neutral-900 sm:text-2xl">
                    {{ \App\Support\LocalizedDate::monthYear(\Carbon\Carbon::createFromDate($year, $month, 1)) }}
                </h2>
            </div>
            <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:justify-end">
                <a href="{{ route('prayer-times.index', ['year' => $prevYear, 'month' => $prevMonth]) }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm font-medium text-neutral-600 transition-colors hover:border-neutral-300 hover:bg-neutral-50">
                    <x-icon name="heroicon-o-chevron-left" class="h-3.5 w-3.5 rtl:rotate-180"/>
                    {{ __('Previous') }}
                </a>
                <a href="{{ route('prayer-times.index', ['year' => $nextYear, 'month' => $nextMonth]) }}"
                   class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-neutral-200 bg-white px-4 py-2.5 text-sm font-medium text-neutral-600 transition-colors hover:border-neutral-300 hover:bg-neutral-50">
                    {{ __('Next') }}
                    <x-icon name="heroicon-o-chevron-right" class="h-3.5 w-3.5 rtl:rotate-180"/>
                </a>
            </div>
        </div>

        <div class="space-y-4 md:hidden">
            @foreach($prayerTimes as $pt)
                @php
                    $isToday = $today && $pt->date->isSameDay($today->date);
                    $isFriday = $pt->date->isFriday();
                    $hasJummah = $isFriday && ($pt->jummah_time ?? null);
                @endphp
                <article class="overflow-hidden rounded-2xl border {{ $isToday ? 'border-emerald-300 bg-emerald-50/80 shadow-sm shadow-emerald-100/60' : ($isFriday ? 'border-amber-200 bg-amber-50/60 shadow-sm shadow-amber-100/60' : 'border-neutral-200 bg-white shadow-sm') }}">
                    <div class="border-b {{ $isToday ? 'border-emerald-200/80 bg-emerald-100/70' : ($isFriday ? 'border-amber-200/80 bg-amber-100/70' : 'border-neutral-200 bg-neutral-50/80') }} px-4 py-3.5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold {{ $isToday ? 'text-emerald-900' : ($isFriday ? 'text-amber-900' : 'text-neutral-900') }}">
                                    {{ \App\Support\LocalizedDate::date($pt->date) }}
                                </p>
                                <p class="mt-1 text-xs {{ $isToday ? 'text-emerald-700' : ($isFriday ? 'text-amber-700' : 'text-neutral-500') }}">
                                    {{ \App\Support\LocalizedDate::weekday($pt->date) }}
                                    <span class="mx-1 text-neutral-300">&middot;</span>
                                    {{ \App\Support\LocalizedDate::hijri($pt->date) }}
                                </p>
                            </div>
                            <div class="flex flex-wrap justify-end gap-2">
                                @if($isToday)
                                    <span class="inline-flex items-center rounded-full bg-emerald-600 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-white">{{ __('Today') }}</span>
                                @endif
                                @if($isFriday)
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-amber-700 ring-1 ring-amber-200">{{ __('Friday') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 px-4 py-4">
                        @foreach($tablePrayers as $prayer)
                            @php
                                $adhan = $prayer['key'] === 'sunrise' ? $pt->sunrise : $pt->{$prayer['key'].'_adhan'};
                                $iqamah = $prayer['key'] === 'sunrise' ? null : $pt->{$prayer['key'].'_iqamah'};
                            @endphp
                            <div class="rounded-xl border border-neutral-200/80 bg-neutral-50/70 px-3 py-2.5">
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-neutral-500">{{ $prayer['label'] }}</p>
                                <p class="mt-1 text-base font-semibold tabular-nums {{ $isToday ? 'text-emerald-800' : 'text-neutral-900' }}">
                                    {{ \App\Support\LocalizedDate::time($adhan) ?? '—' }}
                                </p>
                                <p class="mt-1 text-xs tabular-nums {{ $iqamah ? ($isToday ? 'text-emerald-600' : 'text-neutral-500') : 'text-neutral-300' }}">
                                    {{ $iqamah ? __('Iqamah: :time', ['time' => \App\Support\LocalizedDate::time($iqamah)]) : __('No iqamah') }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    @if($hasJummah)
                        <div class="border-t border-amber-200/80 bg-amber-50/80 px-4 py-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.22em] text-amber-600">{{ __("Jumu'ah") }}</p>
                                    <p class="mt-1 text-sm text-amber-800">{{ __('Friday Prayer Schedule') }}</p>
                                </div>
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-3 grid grid-cols-1 gap-2 sm:grid-cols-3">
                                <div class="rounded-xl bg-white/70 px-3 py-2.5 ring-1 ring-amber-200/70">
                                    <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-500">{{ __('Salah') }}</p>
                                    <p class="mt-1 text-sm font-bold text-amber-800 tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_time) }}</p>
                                </div>
                                @if($pt->jummah_khutba_time ?? null)
                                    <div class="rounded-xl bg-white/70 px-3 py-2.5 ring-1 ring-amber-200/70">
                                        <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-500">{{ __('Khutbah') }}</p>
                                        <p class="mt-1 text-sm font-semibold text-amber-700 tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_khutba_time) }}</p>
                                    </div>
                                @endif
                                @if($pt->jummah_iqamah ?? null)
                                    <div class="rounded-xl bg-white/70 px-3 py-2.5 ring-1 ring-amber-200/70">
                                        <p class="text-[10px] font-semibold uppercase tracking-wide text-amber-500">{{ __('Iqamah') }}</p>
                                        <p class="mt-1 text-sm font-semibold text-amber-700 tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_iqamah) }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </article>
            @endforeach
        </div>

        <div class="hidden overflow-hidden rounded-2xl border border-neutral-200 bg-white shadow-sm md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse text-sm">
                    <thead>
                    <tr class="border-b border-neutral-200 bg-neutral-50/90">
                        <th class="px-4 py-4 text-start text-xs font-semibold uppercase tracking-[0.22em] text-neutral-500 whitespace-nowrap">{{ __('Date') }}</th>
                        @foreach($tablePrayers as $prayer)
                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-[0.22em] text-neutral-500 whitespace-nowrap">{{ $prayer['label'] }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200/80">
                    @foreach($prayerTimes as $pt)
                        @php
                            $isToday = $today && $pt->date->isSameDay($today->date);
                            $isFriday = $pt->date->isFriday();
                            $hasJummah = $isFriday && ($pt->jummah_time ?? null);
                        @endphp
                        <tr class="align-top transition-colors {{ $isToday ? 'bg-emerald-50/80' : ($isFriday ? 'bg-amber-50/50' : 'bg-white hover:bg-neutral-50/80') }}">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-start gap-3">
                                    <div class="min-w-0">
                                        <p class="font-semibold {{ $isToday ? 'text-emerald-900' : ($isFriday ? 'text-amber-900' : 'text-neutral-900') }}">
                                            {{ \App\Support\LocalizedDate::date($pt->date) }}
                                        </p>
                                        <p class="mt-1 text-xs {{ $isToday ? 'text-emerald-700' : ($isFriday ? 'text-amber-700' : 'text-neutral-500') }}">
                                            {{ \App\Support\LocalizedDate::weekday($pt->date) }}
                                        </p>
                                        <p class="mt-0.5 text-[11px] {{ $isToday ? 'text-emerald-600' : ($isFriday ? 'text-amber-600' : 'text-neutral-400') }}">
                                            {{ \App\Support\LocalizedDate::hijri($pt->date) }}
                                        </p>
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        @if($isToday)
                                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-emerald-700 ring-1 ring-emerald-200">{{ __('Today') }}</span>
                                        @endif
                                        @if($isFriday)
                                            <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-amber-700 ring-1 ring-amber-200">{{ __('Friday') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            @foreach($tablePrayers as $prayer)
                                @php
                                    $adhan = $prayer['key'] === 'sunrise' ? $pt->sunrise : $pt->{$prayer['key'].'_adhan'};
                                    $iqamah = $prayer['key'] === 'sunrise' ? null : $pt->{$prayer['key'].'_iqamah'};
                                @endphp
                                <td class="px-4 py-4 text-center tabular-nums">
                                    <p class="font-semibold {{ $isToday ? 'text-emerald-800' : 'text-neutral-800' }}">
                                        {{ \App\Support\LocalizedDate::time($adhan) ?? '—' }}
                                    </p>
                                    <p class="mt-1 text-[11px] {{ $iqamah ? ($isToday ? 'text-emerald-600' : 'text-neutral-500') : 'text-neutral-300' }}">
                                        {{ $iqamah ? __('Iqamah: :time', ['time' => \App\Support\LocalizedDate::time($iqamah)]) : __('No iqamah') }}
                                    </p>

                                    @if($prayer['key'] === 'dhuhr' && $hasJummah)
                                        <div class="mx-auto mt-3 max-w-[12rem] rounded-xl border border-amber-200/80 bg-amber-50/90 px-3 py-2 text-start shadow-sm shadow-amber-100/50">
                                            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-amber-600">{{ __("Jumu'ah") }}</p>
                                            <div class="mt-2 space-y-1 text-[11px] text-amber-700">
                                                <div class="flex items-center justify-between gap-2">
                                                    <span>{{ __('Salah') }}</span>
                                                    <span class="font-bold tabular-nums text-amber-800">{{ \App\Support\LocalizedDate::time($pt->jummah_time) }}</span>
                                                </div>
                                                @if($pt->jummah_khutba_time ?? null)
                                                    <div class="flex items-center justify-between gap-2">
                                                        <span>{{ __('Khutbah') }}</span>
                                                        <span class="font-semibold tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_khutba_time) }}</span>
                                                    </div>
                                                @endif
                                                @if($pt->jummah_iqamah ?? null)
                                                    <div class="flex items-center justify-between gap-2">
                                                        <span>{{ __('Iqamah') }}</span>
                                                        <span class="font-semibold tabular-nums">{{ \App\Support\LocalizedDate::time($pt->jummah_iqamah) }}</span>
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
