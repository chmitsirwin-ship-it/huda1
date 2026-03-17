<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-document-text" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Rich Text') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">نص منسق</span>
    </div>
    <div class="p-4 bg-stone-50">
        @php
            $content = $block['data']['content']['en'] ?? $block['data']['content'] ?? null;
            $contentAr = $block['data']['content']['ar'] ?? null;
            $preview = $content ? strip_tags($content) : null;
        @endphp
        @if($preview)
            <div class="bg-white border border-stone-200 rounded-xl p-3 space-y-2">
                <p class="text-sm text-stone-700 leading-relaxed line-clamp-3">{{ Str::limit($preview, 150) }}</p>
                @if($contentAr)
                    <div class="border-t border-stone-100 pt-2">
                        <p class="text-sm text-stone-600 leading-relaxed line-clamp-2 font-arabic" dir="rtl">{{ Str::limit(strip_tags($contentAr), 100) }}</p>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white border border-dashed border-stone-300 rounded-xl p-4 space-y-2">
                <div class="h-3 bg-stone-200 rounded w-full"></div>
                <div class="h-3 bg-stone-200 rounded w-5/6"></div>
                <div class="h-3 bg-stone-200 rounded w-4/6"></div>
                <div class="h-3 bg-stone-100 rounded w-full"></div>
                <div class="h-3 bg-stone-100 rounded w-3/4"></div>
            </div>
        @endif
    </div>
</div>
