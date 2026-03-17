<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-cursor-arrow-rays" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Call to Action') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">دعوة للعمل</span>
    </div>
    <div class="p-4 bg-stone-50">
        @php $isDark = ($block['data']['style'] ?? 'dark') === 'dark'; @endphp
        <div class="{{ $isDark ? 'bg-stone-900' : 'bg-stone-100 border border-stone-200' }} rounded-xl p-4 text-center space-y-2">
            <div class="text-sm font-bold {{ $isDark ? 'text-white' : 'text-stone-900' }}">
                {{ $block['data']['title']['en'] ?? $block['data']['title'] ?? __('Call to Action') }}
            </div>
            @if(!empty($block['data']['description']['en'] ?? $block['data']['description'] ?? null))
                <div class="text-xs {{ $isDark ? 'text-stone-400' : 'text-stone-600' }} line-clamp-2 leading-relaxed">
                    {{ $block['data']['description']['en'] ?? $block['data']['description'] }}
                </div>
            @endif
            <div class="inline-flex items-center gap-1.5 bg-emerald-600 text-white text-xs font-semibold px-4 py-1.5 rounded-full">
                <x-icon name="heroicon-o-arrow-right" class="size-3.5"/>
                {{ $block['data']['button_text']['en'] ?? $block['data']['button_text'] ?? __('Get Started') }}
            </div>
        </div>
        <div class="flex items-center gap-1.5 mt-2">
            <x-icon name="heroicon-o-swatch" class="size-4 text-emerald-600"/>
            <span class="text-xs text-stone-500">{{ __('Style') }}: <strong>{{ ucfirst($block['data']['style'] ?? 'dark') }}</strong></span>
        </div>
    </div>
</div>
