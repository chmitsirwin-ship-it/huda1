<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-stone-800 to-stone-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-code-bracket" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Custom HTML') }}</span>
        <span class="relative ms-auto text-xs text-stone-400 font-medium">HTML مخصص</span>
    </div>
    <div class="p-4 bg-stone-50">
        @php $html = $block['data']['html'] ?? null; @endphp
        @if($html)
            <div class="bg-stone-900 rounded-xl p-3 font-mono text-xs overflow-hidden">
                <div class="text-green-400 opacity-80 leading-relaxed break-all line-clamp-4">{{ Str::limit($html, 200) }}</div>
            </div>
            <div class="flex items-center gap-1.5 mt-2">
                <x-icon name="heroicon-o-check-circle" class="size-4 text-emerald-500"/>
                <span class="text-xs text-stone-500">{{ strlen($html) }} {{ __('characters of HTML') }}</span>
            </div>
        @else
            <div class="bg-stone-900 rounded-xl p-3 font-mono text-xs">
                <div class="text-stone-500">&lt;!-- {{ __('Your HTML here') }} --&gt;</div>
            </div>
        @endif
    </div>
</div>
