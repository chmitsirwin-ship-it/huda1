<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-photo" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Gallery') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">معرض الصور</span>
    </div>
    <div class="p-4 bg-stone-50 space-y-3">
        <div class="grid grid-cols-4 gap-1.5">
            @foreach(range(1, 8) as $i)
                <div class="aspect-square rounded-lg bg-gradient-to-br {{ $i % 3 === 0 ? 'from-emerald-700 to-emerald-600' : ($i % 3 === 1 ? 'from-emerald-800 to-emerald-700' : 'from-emerald-900 to-emerald-800') }} flex items-center justify-center">
                    <x-icon name="heroicon-o-photo" class="size-4 text-emerald-400"/>
                </div>
            @endforeach
        </div>
        <div class="flex items-center gap-4">
            @if(!empty($block['data']['collection']))
                <div class="flex items-center gap-1.5">
                    <x-icon name="heroicon-o-folder" class="size-4 text-emerald-600"/>
                    <span class="text-xs text-stone-600">{{ __('Collection') }}: <strong>{{ $block['data']['collection'] }}</strong></span>
                </div>
            @endif
            <div class="flex items-center gap-1.5">
                <x-icon name="heroicon-o-list-bullet" class="size-4 text-emerald-600"/>
                <span class="text-xs text-stone-600">{{ __('Limit') }}: <strong>{{ $block['data']['limit'] ?? 12 }}</strong></span>
            </div>
        </div>
    </div>
</div>
