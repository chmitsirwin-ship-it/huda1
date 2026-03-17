<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-chat-bubble-left-right" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Hadith') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">حديث شريف</span>
    </div>
    <div class="p-4 bg-stone-50">
        <div class="relative bg-white border border-amber-200 rounded-xl p-4">
            <div class="absolute -top-3 left-4 bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full font-medium">
                {{ __('Hadith') }}
            </div>
            <div class="text-stone-500 text-sm leading-relaxed italic mb-3 mt-1">
                "{{ __('The Prophet (ﷺ) said...') }}"
            </div>
            <div class="flex items-center gap-2 text-xs text-amber-700 border-t border-amber-100 pt-2 mt-2">
                <x-icon name="heroicon-o-bookmark" class="size-3.5 text-amber-500"/>
                @if(!empty($block['data']['hadith_id']))
                    {{ __('Hadith ID') }}: {{ $block['data']['hadith_id'] }}
                @else
                    {{ __('Random hadith each visit') }}
                @endif
            </div>
        </div>
    </div>
</div>
