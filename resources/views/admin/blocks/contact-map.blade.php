<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-map-pin" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Contact Map') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">الخريطة</span>
    </div>
    <div class="p-4 bg-stone-50">
        <div class="relative h-28 bg-emerald-50 border border-emerald-100 rounded-xl overflow-hidden flex items-center justify-center">
            <div class="absolute inset-0" style="background-image: linear-gradient(rgba(16,185,129,.1) 1px, transparent 1px), linear-gradient(90deg, rgba(16,185,129,.1) 1px, transparent 1px); background-size: 20px 20px;"></div>
            <div class="relative flex flex-col items-center gap-1">
                <x-icon name="heroicon-o-map-pin" class="size-8 text-red-500"/>
                <span class="text-xs font-medium text-stone-600 bg-white px-2 py-0.5 rounded-full shadow-sm">
                    {{ setting('general.name') ?? __('Mosque') }}
                </span>
            </div>
        </div>
        @if(!empty($block['data']['latitude']) || !empty($block['data']['longitude']))
            <div class="flex items-center gap-2 mt-2">
                <x-icon name="heroicon-o-globe-alt" class="size-4 text-emerald-600"/>
                <span class="text-xs text-stone-600">
                    {{ $block['data']['latitude'] ?? '—' }}, {{ $block['data']['longitude'] ?? '—' }}
                    @if(!empty($block['data']['zoom']))
                        · {{ __('Zoom') }}: {{ $block['data']['zoom'] }}
                    @endif
                </span>
            </div>
        @else
            <div class="flex items-center gap-1.5 mt-2">
                <x-icon name="heroicon-o-information-circle" class="size-4 text-amber-500"/>
                <span class="text-xs text-amber-700">{{ __('Set coordinates in block settings') }}</span>
            </div>
        @endif
    </div>
</div>
