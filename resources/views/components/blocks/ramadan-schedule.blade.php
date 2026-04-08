@php
    $data = $block['data'] ?? $data ?? [];
    $schedule = $data['schedule'] ?? [];
    $style = $data['style'] ?? 'table';
    $showCountdown = $data['show_countdown'] ?? true;
    $today = now()->toDateString();

    $nextIftar = null;
    foreach ($schedule as $entry) {
        if (($entry['date'] ?? '') >= $today && !empty($entry['iftar'])) {
            $nextIftar = $entry['date'] . ' ' . $entry['iftar'];
            break;
        }
    }
@endphp

@if(!empty($schedule))
    <section class="relative py-10 overflow-hidden bg-[#0d2137]">

        <div class="absolute inset-0 overflow-hidden opacity-[0.01] pointer-events-none" aria-hidden="true">
            <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="islamic-pattern" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                        <polygon points="40,0 80,40 40,80 0,40" fill="white"/>
                        <polygon points="40,10 70,40 40,70 10,40" fill="none" stroke="white" stroke-width="1"/>
                        <polygon points="40,20 60,40 40,60 20,40" fill="white" opacity="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#islamic-pattern)"/>
            </svg>
        </div>

        {{-- Top crescent accent --}}
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-emerald-400 to-emerald-600"></div>

        <div class="relative max-w-5xl mx-auto px-4">

            {{-- Header --}}
            <div class="text-center mb-6">
                {{-- Decorative divider --}}
                <div class="flex items-center justify-center gap-3 mb-3">
                    <div class="h-px w-12 bg-gradient-to-r from-transparent to-emerald-400"></div>
                    <svg class="w-5 h-5 text-emerald-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                        {{-- star of rub el hizb --}}
                    </svg>
                    {{-- Use a simple star/crescent SVG --}}
                    <svg class="w-6 h-6 text-emerald-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 1.5a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 12 1.5zm0 18a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-1.5 0v-1.5A.75.75 0 0 1 12 19.5zm10.5-7.5a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5a.75.75 0 0 1 .75.75zM4.5 12a.75.75 0 0 1-.75.75H2.25a.75.75 0 0 1 0-1.5h1.5A.75.75 0 0 1 4.5 12zm15.364-6.364a.75.75 0 0 1 0 1.06l-1.06 1.061a.75.75 0 1 1-1.061-1.06l1.06-1.061a.75.75 0 0 1 1.061 0zM6.697 17.303a.75.75 0 0 1 0 1.06l-1.06 1.061a.75.75 0 1 1-1.061-1.06l1.06-1.061a.75.75 0 0 1 1.061 0zM19.864 17.303a.75.75 0 0 1-1.06 0l-1.061-1.06a.75.75 0 1 1 1.06-1.061l1.061 1.06a.75.75 0 0 1 0 1.061zM7.757 6.636a.75.75 0 0 1-1.06 0L5.636 5.575a.75.75 0 1 1 1.06-1.061l1.061 1.06a.75.75 0 0 1 0 1.061zM12 7a5 5 0 1 0 0 10A5 5 0 0 0 12 7z"/>
                    </svg>
                    <div class="h-px w-12 bg-gradient-to-l from-transparent to-emerald-400"></div>
                </div>

                @if(!empty($data['title']))
                    <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-wide">{{ $data['title'] }}</h2>
                @endif
                @if(!empty($data['hijri_year']))
                    <p class="text-emerald-400 text-sm font-semibold mt-1">{{ $data['hijri_year'] }} {{ __('AH') }}</p>
                @endif
                @if(!empty($data['description']))
                    <p class="text-slate-400 text-sm mt-2 max-w-xl mx-auto leading-relaxed">{{ $data['description'] }}</p>
                @endif
            </div>

            {{-- Countdown Banner --}}
            @if($showCountdown && $nextIftar)
                <div class="mb-6 flex justify-center"
                     x-data="{
                     target: new Date('{{ $nextIftar }}').getTime(),
                     hours: '00', minutes: '00', seconds: '00',
                     expired: false,
                     tick() {
                         const diff = this.target - Date.now();
                         if (diff <= 0) { this.expired = true; return; }
                         this.hours   = String(Math.floor(diff / 3600000)).padStart(2, '0');
                         this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                         this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
                     }
                 }"
                     x-init="tick(); setInterval(() => tick(), 1000)">

                    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 px-6 py-4 shadow-xl shadow-emerald-900/40 w-full max-w-sm">
                    {{-- Countdown subtle pattern overlay --}}
                    <div class="absolute inset-0 overflow-hidden opacity-5 pointer-events-none rounded-2xl" aria-hidden="true">
                        <svg class="absolute inset-0 w-full h-full" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <pattern id="countdown-pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                    <polygon points="20,0 40,20 20,40 0,20" fill="white"/>
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#countdown-pattern)"/>
                        </svg>
                    </div>
                    <div class="relative flex flex-col items-center gap-1">
                        <span class="text-emerald-900 text-xs font-bold uppercase tracking-widest">🌙 {{ __('Next Iftar In') }}</span>
                        <template x-if="!expired">
                            <div class="flex items-center gap-2 mt-1">
                                @foreach(['hours', 'minutes', 'seconds'] as $unit)
                                    <div class="flex flex-col items-center">
                                        <div class="bg-emerald-900/30 rounded-lg px-3 py-1.5 min-w-[52px] text-center">
                                            <span x-text="{{ $unit }}" class="text-white text-2xl font-black tabular-nums leading-none"></span>
                                        </div>
                                        <span class="text-emerald-900/80 text-[10px] font-semibold mt-0.5 uppercase">{{ __($unit) }}</span>
                                    </div>
                                    @if(!$loop->last)
                                        <span class="text-white text-2xl font-black mb-3">:</span>
                                    @endif
                                @endforeach
                            </div>
                        </template>
                        <template x-if="expired">
                            <span class="text-white font-black text-xl mt-1">🌅 {{ __('Iftar Time!') }}</span>
                        </template>
                    </div>
                </div>
        </div>
        @endif

        {{-- Schedule --}}
        @if($style === 'table')
            {{-- Mobile: cards, Desktop: table --}}

            {{-- Mobile Card Stack (hidden on md+) --}}
            <div class="flex flex-col gap-2 md:hidden">
                @foreach($schedule as $entry)
                    @php $isToday = ($entry['date'] ?? '') === $today; @endphp
                    <div class="rounded-xl {{ $isToday ? 'bg-emerald-500/20 ring-1 ring-emerald-400' : 'bg-white/5' }} px-4 py-3">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold {{ $isToday ? 'text-emerald-400' : 'text-white' }}">
                                    {{ $entry['day'] ?? '' }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ !empty($entry['date']) ? \Carbon\Carbon::parse($entry['date'])->format('j M') : '' }}<br>
                                    <sub>{{ !empty($entry['date']) ? \App\Support\LocalizedDate::hijri($entry['date']) : '' }}</sub>
                                </span>
                                @if($isToday)
                                    <span class="bg-emerald-400 text-emerald-900 text-[10px] font-black px-1.5 py-0.5 rounded-full uppercase tracking-wide">{{ __('Today') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase tracking-wide mb-0.5">{{ __('Suhoor') }}</p>
                                <p class="text-xs font-semibold text-slate-200">{{ !empty($entry['suhoor']) ? \Carbon\Carbon::parse($entry['suhoor'])->format('g:i A') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase tracking-wide mb-0.5">{{ __('Iftar') }}</p>
                                <p class="text-xs font-bold {{ $isToday ? 'text-emerald-400' : 'text-emerald-400' }}">{{ !empty($entry['iftar']) ? \Carbon\Carbon::parse($entry['iftar'])->format('g:i A') : '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 uppercase tracking-wide mb-0.5">{{ __('Taraweeh') }}</p>
                                <p class="text-xs font-semibold text-slate-200">{{ !empty($entry['taraweeh']) ? \Carbon\Carbon::parse($entry['taraweeh'])->format('g:i A') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desktop Table (hidden on mobile) --}}
            <div class="hidden md:block rounded-2xl overflow-hidden border border-white/10 shadow-xl">
                <table class="w-full text-sm">
                    <thead>
                    <tr class="bg-gradient-to-r from-[#0a1a2a] to-[#0f2236] border-b border-emerald-500/30">
                        <th class="px-4 py-3 text-start text-emerald-400 font-semibold text-xs uppercase tracking-wider">{{ __('Day') }}</th>
                        <th class="px-4 py-3 text-start text-emerald-400 font-semibold text-xs uppercase tracking-wider">{{ __('Date') }}</th>
                        <th class="px-4 py-3 text-start text-emerald-400 font-semibold text-xs uppercase tracking-wider">{{ __('Suhoor') }}</th>
                        <th class="px-4 py-3 text-start text-emerald-400 font-semibold text-xs uppercase tracking-wider">{{ __('Iftar') }}</th>
                        <th class="px-4 py-3 text-start text-emerald-400 font-semibold text-xs uppercase tracking-wider">{{ __('Taraweeh') }}</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                    @foreach($schedule as $entry)
                        @php $isToday = ($entry['date'] ?? '') === $today; @endphp
                        <tr class="{{ $isToday ? 'bg-emerald-500/10' : ($loop->even ? 'bg-white/[0.02]' : 'bg-transparent') }} transition-colors hover:bg-white/5">
                            <td class="px-4 py-3">
                                @if($isToday)
                                    <span class="inline-flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                            <span class="text-emerald-400 font-bold">{{ $entry['day'] ?? '' }}</span>
                                        </span>
                                @else
                                    <span class="text-slate-300">{{ $entry['day'] ?? '' }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-400 text-xs">
                                {{ !empty($entry['date']) ? \Carbon\Carbon::parse($entry['date'])->format('j M Y') : '' }}<br>
                                <sub>{{ !empty($entry['date']) ? \App\Support\LocalizedDate::hijri($entry['date']) : '' }}</sub>
                            </td>
                            <td class="px-4 py-3 text-slate-300">
                                {{ !empty($entry['suhoor']) ? \Carbon\Carbon::parse($entry['suhoor'])->format('g:i A') : '-' }}
                            </td>
                            <td class="px-4 py-3 font-semibold {{ $isToday ? 'text-emerald-400' : 'text-emerald-400' }}">
                                {{ !empty($entry['iftar']) ? \Carbon\Carbon::parse($entry['iftar'])->format('g:i A') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-slate-300">
                                {{ !empty($entry['taraweeh']) ? \Carbon\Carbon::parse($entry['taraweeh'])->format('g:i A') : '-' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        @else
            {{-- Card Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                @foreach($schedule as $entry)
                    @php $isToday = ($entry['date'] ?? '') === $today; @endphp
                    <div class="relative rounded-xl {{ $isToday ? 'bg-gradient-to-b from-emerald-500/30 to-emerald-600/10 ring-1 ring-emerald-400/60' : 'bg-white/5 hover:bg-white/8' }} p-3 transition-all">
                        @if($isToday)
                            <span class="absolute top-2 right-2 bg-emerald-400 text-emerald-900 text-[9px] font-black px-1.5 py-0.5 rounded-full uppercase">{{ __('Today') }}</span>
                        @endif
                        <p class="text-xs font-bold {{ $isToday ? 'text-emerald-400' : 'text-slate-300' }} mb-0.5">{{ __('Day') }} {{ $entry['day'] ?? '' }}</p>
                        <p class="text-[10px] text-slate-500 mb-1">{{ !empty($entry['date']) ? \Carbon\Carbon::parse($entry['date'])->format('j M') : '' }}</p>
                        <p class="text-[9px] text-slate-600 mb-3">{{ !empty($entry['date']) ? \App\Support\LocalizedDate::hijri($entry['date']) : '' }}</p>
                        <div class="space-y-1.5 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">{{ __('Suhoor') }}</span>
                                <span class="text-slate-300 font-medium">{{ !empty($entry['suhoor']) ? \Carbon\Carbon::parse($entry['suhoor'])->format('g:i A') : '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-500">{{ __('Iftar') }}</span>
                                <span class="font-bold {{ $isToday ? 'text-emerald-400' : 'text-emerald-400' }}">{{ !empty($entry['iftar']) ? \Carbon\Carbon::parse($entry['iftar'])->format('g:i A') : '-' }}</span>
                            </div>
                            @if(!empty($entry['taraweeh']))
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">{{ __('Taraweeh') }}</span>
                                    <span class="text-slate-300 font-medium">{{ \Carbon\Carbon::parse($entry['taraweeh'])->format('g:i A') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Bottom accent --}}
        <div class="mt-8 flex items-center justify-center gap-2">
            <div class="h-px w-16 bg-gradient-to-r from-transparent to-emerald-500/40"></div>
            <span class="text-emerald-500/40 text-lg">☽</span>
            <div class="h-px w-16 bg-gradient-to-l from-transparent to-emerald-500/40"></div>
        </div>
        </div>
    </section>
@endif