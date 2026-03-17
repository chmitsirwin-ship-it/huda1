@php
$data = $block['data'] ?? $data ?? [];
$today = \App\Models\PrayerTime::where('date', today()->toDateString())->first();
$style = $data['style'] ?? 'card';
$prayers = [
    ['key' => 'fajr',    'label' => __('Fajr'),    'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
    ['key' => 'sunrise',  'label' => __('Sunrise'), 'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
    ['key' => 'dhuhr',   'label' => __('Dhuhr'),   'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
    ['key' => 'asr',     'label' => __('Asr'),     'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
    ['key' => 'maghrib', 'label' => __('Maghrib'), 'icon' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'],
    ['key' => 'isha',    'label' => __('Isha'),    'icon' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'],
];
@endphp

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 text-emerald-600 font-medium text-sm uppercase tracking-widest mb-3">
                <span class="w-8 h-px bg-emerald-600"></span>
                {{ __('Daily Schedule') }}
                <span class="w-8 h-px bg-emerald-600"></span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900">{{ __('Prayer Times') }}</h2>
            @if($today)
                <p class="text-neutral-500 mt-2">{{ $today->date->translatedFormat('l, d F Y') }}</p>
            @endif
        </div>

        @if(!$today)
            <div class="text-center py-12 text-neutral-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>{{ __('Prayer times not available for today.') }}</p>
            </div>
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
                            <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-emerald-50/50' }} border-b border-neutral-100 last:border-0">
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-neutral-800">{{ $prayer['label'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-emerald-700 font-medium tabular-nums">
                                        {{ $today->{$prayer['key'].'_adhan'} ?? $today->{$prayer['key']} ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-amber-600 font-medium tabular-nums">
                                        {{ $today->{$prayer['key'].'_iqamah'} ?? '—' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif($style === 'compact')
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                @foreach($prayers as $prayer)
                    <div class="bg-emerald-50 rounded-xl p-4 text-center border border-emerald-100 hover:border-emerald-300 hover:shadow-md transition-all duration-200 group">
                        <p class="text-sm font-semibold text-neutral-500 uppercase tracking-wide mb-2 group-hover:text-emerald-600 transition-colors">
                            {{ $prayer['label'] }}
                        </p>
                        <p class="text-xl font-bold text-emerald-700 tabular-nums">
                            {{ $today->{$prayer['key'].'_adhan'} ?? $today->{$prayer['key']} ?? '—' }}
                        </p>
                        @if(isset($today->{$prayer['key'].'_iqamah'}) && $today->{$prayer['key'].'_iqamah'})
                            <p class="text-xs text-amber-600 font-medium mt-1 tabular-nums">
                                {{ __('Iqamah') }}: {{ $today->{$prayer['key'].'_iqamah'} }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="max-w-2xl mx-auto bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-2xl shadow-xl shadow-emerald-900/20 overflow-hidden">
                <div class="px-8 pt-8 pb-6 border-b border-emerald-500/30">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-white font-bold text-xl">{{ __("Today's Prayers") }}</h3>
                            <p class="text-emerald-200 text-sm mt-1">{{ $today->date->translatedFormat('l, d F Y') }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="divide-y divide-emerald-500/20">
                    @foreach($prayers as $prayer)
                        <div class="flex items-center justify-between px-8 py-4 hover:bg-white/5 transition-colors">
                            <span class="text-emerald-100 font-medium">{{ $prayer['label'] }}</span>
                            <div class="flex items-center gap-4">
                                <span class="text-white font-bold text-lg tabular-nums">
                                    {{ $today->{$prayer['key'].'_adhan'} ?? $today->{$prayer['key']} ?? '—' }}
                                </span>
                                @if(isset($today->{$prayer['key'].'_iqamah'}) && $today->{$prayer['key'].'_iqamah'})
                                    <span class="text-amber-300 text-sm tabular-nums px-2 py-0.5 rounded bg-amber-400/10">
                                        {{ $today->{$prayer['key'].'_iqamah'} }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
