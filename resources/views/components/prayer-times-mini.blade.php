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
    <div class="space-y-1">
        @foreach($prayers as $prayer)
            @if($prayer['time'])
                <div class="flex items-center justify-between py-1 px-2 rounded bg-neutral-800/50 hover:bg-neutral-800 transition-colors">
                    <span class="text-xs text-neutral-400">{{ $prayer['name'] }}</span>
                    <span class="text-xs font-medium text-emerald-400 tabular-nums">
                        {{ \App\Support\LocalizedDate::time($prayer['time']) }}
                    </span>
                </div>
            @endif

            {{-- Jumu'ah rows injected after Dhuhr --}}
            @if($prayer['name'] === __('Dhuhr') && $today->jummah_adhan)
                <div class="flex items-center justify-between py-1 px-2 rounded bg-amber-900/20 border border-amber-800/30">
                    <span class="text-xs text-amber-500">{{ __("Jumu'ah") }}</span>
                    <span class="text-xs font-medium text-amber-400 tabular-nums">
                        {{ \App\Support\LocalizedDate::time($today->jummah_adhan) }}
                    </span>
                </div>
                @if($today->jummah_khutba_time)
                    <div class="flex items-center justify-between py-1 px-2 rounded bg-amber-900/10 border border-amber-800/20">
                        <span class="text-xs text-amber-500/70 ps-2">↳ {{ __('Khutbah') }}</span>
                        <span class="text-xs font-medium text-amber-400/70 tabular-nums">
                            {{ \App\Support\LocalizedDate::time($today->jummah_khutba_time) }}
                        </span>
                    </div>
                @endif
            @endif
        @endforeach
    </div>
@else
    <div class="flex items-center gap-2 py-2 px-2 rounded bg-neutral-800/40 text-xs text-neutral-600">
        <x-heroicon-o-clock class="w-3.5 h-3.5 shrink-0" />
        {{ __('Times not available') }}
    </div>
@endif