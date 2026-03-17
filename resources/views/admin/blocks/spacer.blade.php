<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-arrows-up-down" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Spacer') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">مساحة فارغة</span>
    </div>
    <div class="p-4 bg-stone-50">
        @php
            $sizes = ['sm' => ['label' => 'Small', 'bars' => 2], 'md' => ['label' => 'Medium', 'bars' => 3], 'lg' => ['label' => 'Large', 'bars' => 5], 'xl' => ['label' => 'Extra Large', 'bars' => 7]];
            $size = $block['data']['size'] ?? 'md';
            $info = $sizes[$size] ?? $sizes['md'];
        @endphp
        <div class="flex flex-col items-center gap-1 py-2">
            <div class="flex items-center gap-1">
                <x-icon name="heroicon-o-chevron-up" class="size-4 text-emerald-400"/>
            </div>
            @foreach(range(1, $info['bars']) as $i)
                <div class="w-16 h-px bg-emerald-200 border-dashed border-t border-emerald-300"></div>
            @endforeach
            <div class="flex items-center gap-1">
                <x-icon name="heroicon-o-chevron-down" class="size-4 text-emerald-400"/>
            </div>
        </div>
        <div class="text-center">
            <span class="text-xs text-stone-500">{{ __('Size') }}: <strong class="text-emerald-700">{{ __($info['label']) }}</strong></span>
        </div>
    </div>
</div>
