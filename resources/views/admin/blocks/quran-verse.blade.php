<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-book-open" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Quran Verse') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">آية قرآنية</span>
    </div>
    <div class="p-4 bg-stone-50">
        <div class="bg-gradient-to-br from-emerald-900 to-emerald-800 rounded-xl p-4 text-center space-y-3 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-px bg-amber-400/30"></div>
            <div class="absolute bottom-0 left-0 right-0 h-px bg-amber-400/30"></div>
            <div class="text-amber-400 text-xs font-medium tracking-widest uppercase">{{ __('Quran') }} — القرآن الكريم</div>
            <div class="text-emerald-200 text-sm font-arabic leading-relaxed" dir="rtl">
                ﴿ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ﴾
            </div>
            @if(!empty($block['data']['verse_id']))
                <div class="text-amber-300 text-xs">{{ __('Verse ID') }}: {{ $block['data']['verse_id'] }}</div>
            @else
                <div class="text-emerald-400 text-xs italic">{{ __('Random verse each visit') }}</div>
            @endif
        </div>
    </div>
</div>
