<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-photo" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Hero Section') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">بطل الصفحة</span>
    </div>
    <div class="p-4 bg-stone-50 space-y-3">
        @if(!empty($block['data']['background_image']))
            <div class="w-full h-20 rounded-lg bg-emerald-900 flex items-center justify-center overflow-hidden relative">
                <div class="absolute inset-0 bg-emerald-900/60"></div>
                <x-icon name="heroicon-o-photo" class="relative size-8 text-emerald-300"/>
            </div>
        @else
            <div class="w-full h-20 rounded-lg bg-gradient-to-br from-emerald-900 to-emerald-700 flex items-center justify-center">
                <x-icon name="heroicon-o-photo" class="size-8 text-emerald-400"/>
            </div>
        @endif
        <div class="space-y-2">
            <div class="flex items-start gap-2">
                <span class="text-xs font-medium text-emerald-700 uppercase tracking-wide min-w-20 pt-0.5">{{ __('Heading EN') }}</span>
                <span class="text-sm font-semibold text-stone-800 flex-1">{{ $block['data']['heading']['en'] ?? $block['data']['heading'] ?? '—' }}</span>
            </div>
            <div class="flex items-start gap-2">
                <span class="text-xs font-medium text-emerald-700 uppercase tracking-wide min-w-20 pt-0.5">{{ __('Heading AR') }}</span>
                <span class="text-sm text-stone-700 flex-1 font-arabic" dir="rtl">{{ $block['data']['heading']['ar'] ?? '—' }}</span>
            </div>
            <div class="flex items-start gap-2">
                <span class="text-xs font-medium text-emerald-700 uppercase tracking-wide min-w-20 pt-0.5">{{ __('Subheading') }}</span>
                <span class="text-sm text-stone-600 flex-1">{{ $block['data']['subheading']['en'] ?? $block['data']['subheading'] ?? '—' }}</span>
            </div>
            @if(!empty($block['data']['button_url']))
                <div class="flex items-center gap-2 pt-1">
                    <x-icon name="heroicon-o-cursor-arrow-rays" class="size-4 text-amber-500"/>
                    <span class="text-xs text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200">
                        {{ $block['data']['button_text']['en'] ?? $block['data']['button_text'] ?? __('Button') }}
                    </span>
                    <span class="text-xs text-stone-400">→ {{ $block['data']['button_url'] ?? '' }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
