<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-squares-2x2" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Slider') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">شرائح</span>
    </div>
    <div class="p-4 bg-stone-50">
        <div class="flex gap-2 overflow-hidden">
            @foreach(range(1, min((int)($block['data']['limit'] ?? 3), 4)) as $i)
                <div class="flex-1 h-16 rounded-lg bg-gradient-to-br from-emerald-800 to-emerald-600 flex items-center justify-center min-w-0">
                    <x-icon name="heroicon-o-photo" class="size-5 text-emerald-300"/>
                </div>
            @endforeach
        </div>
        <div class="flex items-center gap-4 mt-3">
            <div class="flex items-center gap-1.5">
                <x-icon name="heroicon-o-queue-list" class="size-4 text-emerald-600"/>
                <span class="text-xs text-stone-600">{{ __('Max slides') }}: <strong>{{ $block['data']['limit'] ?? 5 }}</strong></span>
            </div>
            <div class="flex items-center gap-1.5">
                <x-icon name="heroicon-o-play-circle" class="size-4 text-emerald-600"/>
                <span class="text-xs text-stone-600">{{ __('Autoplay') }}: <strong>{{ ($block['data']['autoplay'] ?? '1') === '1' ? __('On') : __('Off') }}</strong></span>
            </div>
        </div>
    </div>
</div>
