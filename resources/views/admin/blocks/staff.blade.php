<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-user-group" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Staff') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">فريق العمل</span>
    </div>
    <div class="p-4 bg-stone-50 space-y-3">
        <div class="grid grid-cols-3 gap-2">
            @foreach(range(1, min((int)($block['data']['limit'] ?? 6), 3)) as $i)
                <div class="bg-white border border-emerald-100 rounded-xl p-3 text-center">
                    <div class="size-10 rounded-full bg-emerald-100 mx-auto mb-2 flex items-center justify-center">
                        <x-icon name="heroicon-o-user" class="size-5 text-emerald-600"/>
                    </div>
                    <div class="h-2 bg-stone-200 rounded w-full mb-1 mx-auto"></div>
                    <div class="h-2 bg-stone-100 rounded w-2/3 mx-auto"></div>
                </div>
            @endforeach
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-1.5">
                <x-icon name="heroicon-o-users" class="size-4 text-emerald-600"/>
                <span class="text-xs text-stone-600">{{ __('Limit') }}: <strong>{{ $block['data']['limit'] ?? 6 }}</strong></span>
            </div>
            <div class="flex items-center gap-1.5">
                <x-icon name="heroicon-o-squares-2x2" class="size-4 text-emerald-600"/>
                <span class="text-xs text-stone-600">{{ __('Style') }}: <strong>{{ ucfirst($block['data']['style'] ?? 'grid') }}</strong></span>
            </div>
        </div>
    </div>
</div>
