<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-heart" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Donation') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">التبرعات</span>
    </div>
    <div class="p-4 bg-stone-50 space-y-3">
        <div class="bg-emerald-700 rounded-xl p-4 text-center space-y-2">
            <div class="text-sm font-semibold text-white">
                {{ $block['data']['title']['en'] ?? $block['data']['title'] ?? __('Support Our Mosque') }}
            </div>
            @if(!empty($block['data']['description']['en'] ?? $block['data']['description'] ?? null))
                <div class="text-emerald-200 text-xs leading-relaxed line-clamp-2">
                    {{ $block['data']['description']['en'] ?? $block['data']['description'] }}
                </div>
            @endif
            <div class="inline-flex items-center gap-1.5 bg-white text-emerald-700 text-xs font-semibold px-4 py-1.5 rounded-full mt-1">
                <x-icon name="heroicon-o-heart" class="size-3.5 text-red-500"/>
                {{ $block['data']['button_text']['en'] ?? $block['data']['button_text'] ?? __('Donate Now') }}
            </div>
        </div>
        @if(!empty($block['data']['button_url']))
            <div class="flex items-center gap-1.5">
                <x-icon name="heroicon-o-link" class="size-4 text-emerald-600"/>
                <span class="text-xs text-stone-500">{{ $block['data']['button_url'] }}</span>
            </div>
        @endif
    </div>
</div>
