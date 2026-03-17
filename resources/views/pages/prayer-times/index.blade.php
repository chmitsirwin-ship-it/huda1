@extends('layouts.public')
@section('title', __('Prayer Times'))
@section('content')

    <div class="bg-emerald-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/10 mb-6">
                <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
            </div>
            <h1 class="text-4xl font-bold tracking-tight mb-3">{{ __('Prayer Times') }}</h1>
            <p class="text-emerald-200 text-lg">{{ __('Daily prayer schedule for') }} {{ setting('general.name') }}</p>
        </div>
    </div>

    @if($today)
        <div class="bg-emerald-800 text-white py-10">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-xl font-semibold mb-6 text-amber-400 tracking-wide uppercase text-sm">
                    {{ __("Today's Prayer Times") }} &mdash; {{ $today->date->format('l, F j, Y') }}
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">

                    @php
                        $prayers = [
                            ['key' => 'fajr',    'label' => __('Fajr'),    'adhan' => $today->fajr_adhan,    'iqamah' => $today->fajr_iqamah,    'icon' => '🌙'],
                            ['key' => 'sunrise', 'label' => __('Sunrise'), 'adhan' => $today->sunrise,       'iqamah' => null,                    'icon' => '🌅'],
                            ['key' => 'dhuhr',   'label' => __('Dhuhr'),   'adhan' => $today->dhuhr_adhan,   'iqamah' => $today->dhuhr_iqamah,   'icon' => '☀️'],
                            ['key' => 'asr',     'label' => __('Asr'),     'adhan' => $today->asr_adhan,     'iqamah' => $today->asr_iqamah,     'icon' => '🌤'],
                            ['key' => 'maghrib', 'label' => __('Maghrib'), 'adhan' => $today->maghrib_adhan, 'iqamah' => $today->maghrib_iqamah, 'icon' => '🌇'],
                            ['key' => 'isha',    'label' => __('Isha'),    'adhan' => $today->isha_adhan,    'iqamah' => $today->isha_iqamah,    'icon' => '🌑'],
                        ];
                        $now = now()->format('H:i');
                    @endphp

                    @foreach($prayers as $prayer)
                        <div class="bg-white/10 rounded-2xl p-5 text-center hover:bg-white/20 transition-colors">
                            <div class="text-2xl mb-2">{{ $prayer['icon'] }}</div>
                            <div class="font-bold text-lg text-white">{{ $prayer['label'] }}</div>
                            <div class="text-amber-300 font-mono text-xl mt-1">
                                {{ $prayer['adhan'] ? \Carbon\Carbon::parse($prayer['adhan'])->format('h:i A') : '—' }}
                            </div>
                            @if($prayer['iqamah'])
                                <div class="text-emerald-300 text-sm mt-1">
                                    {{ __('Iqamah') }}: {{ \Carbon\Carbon::parse($prayer['iqamah'])->format('h:i A') }}
                                </div>
                            @endif
                        </div>
                    @endforeach

                </div>

                @if($today->jummah_time)
                    <div class="mt-6 inline-flex items-center gap-3 bg-amber-500/20 border border-amber-400/30 rounded-xl px-6 py-3">
                        <svg class="w-5 h-5 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-amber-200 font-medium">
                            {{ __("Jumu'ah") }}: {{ \Carbon\Carbon::parse($today->jummah_time)->format('h:i A') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-6 py-12">

        <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
            <h2 class="text-2xl font-bold text-neutral-900">
                {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}
            </h2>
            <div class="flex items-center gap-3">
                @php
                    $prevMonth = $month == 1 ? 12 : $month - 1;
                    $prevYear  = $month == 1 ? $year - 1 : $year;
                    $nextMonth = $month == 12 ? 1 : $month + 1;
                    $nextYear  = $month == 12 ? $year + 1 : $year;
                @endphp
                <a href="{{ route('prayer-times.index', ['year' => $prevYear, 'month' => $prevMonth]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-emerald-200 text-emerald-700 hover:bg-emerald-50 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('Previous') }}
                </a>
                <a href="{{ route('prayer-times.index', ['year' => $nextYear, 'month' => $nextMonth]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-emerald-200 text-emerald-700 hover:bg-emerald-50 transition-colors text-sm font-medium">
                    {{ __('Next') }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl shadow-sm border border-neutral-200">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-emerald-900 text-white">
                        <th class="px-4 py-4 text-left font-semibold">{{ __('Date') }}</th>
                        <th class="px-4 py-4 text-center font-semibold">{{ __('Fajr') }}</th>
                        <th class="px-4 py-4 text-center font-semibold">{{ __('Sunrise') }}</th>
                        <th class="px-4 py-4 text-center font-semibold">{{ __('Dhuhr') }}</th>
                        <th class="px-4 py-4 text-center font-semibold">{{ __('Asr') }}</th>
                        <th class="px-4 py-4 text-center font-semibold">{{ __('Maghrib') }}</th>
                        <th class="px-4 py-4 text-center font-semibold">{{ __('Isha') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @foreach($prayerTimes as $pt)
                        @php $isToday = $today && $pt->date->isSameDay($today->date); @endphp
                        <tr class="{{ $isToday ? 'bg-emerald-50 font-semibold' : 'bg-white hover:bg-neutral-50' }} transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="{{ $isToday ? 'text-emerald-800' : 'text-neutral-900' }} font-medium">
                                    {{ $pt->date->format('D, M j') }}
                                </div>
                                @if($isToday)
                                    <span class="inline-block text-xs bg-emerald-600 text-white rounded px-2 py-0.5 mt-0.5">{{ __('Today') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="text-neutral-800">{{ $pt->fajr_adhan ? \Carbon\Carbon::parse($pt->fajr_adhan)->format('h:i') : '—' }}</div>
                                @if($pt->fajr_iqamah)
                                    <div class="text-xs text-emerald-600">{{ \Carbon\Carbon::parse($pt->fajr_iqamah)->format('h:i') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-neutral-800">
                                {{ $pt->sunrise ? \Carbon\Carbon::parse($pt->sunrise)->format('h:i') : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="text-neutral-800">{{ $pt->dhuhr_adhan ? \Carbon\Carbon::parse($pt->dhuhr_adhan)->format('h:i') : '—' }}</div>
                                @if($pt->dhuhr_iqamah)
                                    <div class="text-xs text-emerald-600">{{ \Carbon\Carbon::parse($pt->dhuhr_iqamah)->format('h:i') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="text-neutral-800">{{ $pt->asr_adhan ? \Carbon\Carbon::parse($pt->asr_adhan)->format('h:i') : '—' }}</div>
                                @if($pt->asr_iqamah)
                                    <div class="text-xs text-emerald-600">{{ \Carbon\Carbon::parse($pt->asr_iqamah)->format('h:i') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="text-neutral-800">{{ $pt->maghrib_adhan ? \Carbon\Carbon::parse($pt->maghrib_adhan)->format('h:i') : '—' }}</div>
                                @if($pt->maghrib_iqamah)
                                    <div class="text-xs text-emerald-600">{{ \Carbon\Carbon::parse($pt->maghrib_iqamah)->format('h:i') }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="text-neutral-800">{{ $pt->isha_adhan ? \Carbon\Carbon::parse($pt->isha_adhan)->format('h:i') : '—' }}</div>
                                @if($pt->isha_iqamah)
                                    <div class="text-xs text-emerald-600">{{ \Carbon\Carbon::parse($pt->isha_iqamah)->format('h:i') }}</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="mt-4 text-xs text-neutral-400 text-center">{{ __('Adhan time shown above, Iqamah time shown in green below.') }}</p>

    </div>

@endsection
