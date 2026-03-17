@if($today)
    @php
        $prayers = [
            ['name' => __('Fajr'),    'time' => $today->fajr_adhan],
            ['name' => __('Sunrise'), 'time' => $today->sunrise],
            ['name' => __('Dhuhr'),   'time' => $today->dhuhr_adhan],
            ['name' => __('Asr'),     'time' => $today->asr_adhan],
            ['name' => __('Maghrib'), 'time' => $today->maghrib_adhan],
            ['name' => __('Isha'),    'time' => $today->isha_adhan],
        ];
    @endphp
    <div class="space-y-1.5">
        @foreach($prayers as $prayer)
            @if($prayer['time'])
                <div class="flex items-center justify-between py-1.5 px-2 rounded-md bg-neutral-800/60 hover:bg-neutral-800 transition-colors">
                    <span class="text-sm text-neutral-300">{{ $prayer['name'] }}</span>
                    <span class="text-sm font-semibold text-emerald-400 tabular-nums">
                        {{ \Carbon\Carbon::parse($prayer['time'])->format('H:i') }}
                    </span>
                </div>
            @endif
        @endforeach

        @if($today->jummah_time)
            <div class="flex items-center justify-between py-1.5 px-2 rounded-md bg-amber-700/20 border border-amber-700/30">
                <span class="text-sm text-amber-400">{{ __('Jumu\'ah') }}</span>
                <span class="text-sm font-semibold text-amber-400 tabular-nums">
                    {{ \Carbon\Carbon::parse($today->jummah_time)->format('H:i') }}
                </span>
            </div>
        @endif
    </div>
@else
    <div class="flex items-center gap-2 py-3 px-2 rounded-md bg-neutral-800/40 text-sm text-neutral-500">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ __('Times not available') }}
    </div>
@endif
