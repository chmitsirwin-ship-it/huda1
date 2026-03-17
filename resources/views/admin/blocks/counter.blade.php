<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-chart-bar" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Counters') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">إحصائيات</span>
    </div>
    <div class="p-4 bg-stone-50 space-y-3">
        @if(!empty($block['data']['title']['en'] ?? $block['data']['title'] ?? null))
            <div class="text-sm font-semibold text-stone-800 text-center">
                {{ $block['data']['title']['en'] ?? $block['data']['title'] }}
            </div>
        @endif
        @php $counters = $block['data']['counters'] ?? []; @endphp
        @if(count($counters) > 0)
            <div class="bg-emerald-900 rounded-xl p-3">
                <div class="grid grid-cols-{{ min(count($counters), 4) }} gap-3">
                    @foreach(array_slice($counters, 0, 4) as $counter)
                        <div class="text-center">
                            <div class="text-lg font-bold text-amber-400">{{ $counter['value'] ?? '0' }}</div>
                            <div class="text-xs text-emerald-300 leading-tight">{{ $counter['label']['en'] ?? $counter['label'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @if(count($counters) > 4)
                <div class="text-center text-xs text-emerald-600">+{{ count($counters) - 4 }} {{ __('more counters') }}</div>
            @endif
        @else
            <div class="bg-emerald-900 rounded-xl p-3 grid grid-cols-4 gap-2">
                @foreach(range(1,4) as $i)
                    <div class="text-center space-y-1">
                        <div class="h-5 bg-amber-400/20 rounded w-10 mx-auto"></div>
                        <div class="h-2 bg-emerald-700 rounded w-full"></div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
