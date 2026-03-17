<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-question-mark-circle" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('FAQ') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">أسئلة شائعة</span>
    </div>
    <div class="p-4 bg-stone-50 space-y-3">
        @if(!empty($block['data']['title']['en'] ?? $block['data']['title'] ?? null))
            <div class="text-sm font-semibold text-stone-800 text-center">
                {{ $block['data']['title']['en'] ?? $block['data']['title'] }}
            </div>
        @endif
        @php $items = $block['data']['items'] ?? []; @endphp
        @if(count($items) > 0)
            <div class="space-y-1.5" x-data="{ open: 0 }">
                @foreach(array_slice($items, 0, 3) as $i => $item)
                    <div class="bg-white border border-emerald-100 rounded-xl overflow-hidden">
                        <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                                class="w-full flex items-center gap-2 px-3 py-2 text-left">
                            <x-icon name="heroicon-o-chevron-right" class="size-3.5 text-emerald-500 shrink-0 transition-transform" :class="{ 'rotate-90': open === {{ $i }} }"/>
                            <span class="text-xs font-medium text-stone-700 flex-1 truncate">
                                {{ $item['question']['en'] ?? $item['question'] ?? __('Question') }}
                            </span>
                        </button>
                        <div x-show="open === {{ $i }}" class="px-3 pb-2 text-xs text-stone-500 border-t border-stone-100 pt-2">
                            {{ Str::limit($item['answer']['en'] ?? $item['answer'] ?? '', 80) }}
                        </div>
                    </div>
                @endforeach
                @if(count($items) > 3)
                    <div class="text-center text-xs text-emerald-600">+{{ count($items) - 3 }} {{ __('more questions') }}</div>
                @endif
            </div>
        @else
            <div class="text-center py-3 text-sm text-stone-400">{{ __('No FAQ items added yet') }}</div>
        @endif
    </div>
</div>
