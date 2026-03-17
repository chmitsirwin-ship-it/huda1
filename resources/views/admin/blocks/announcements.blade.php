<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-megaphone" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Announcements') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">الإعلانات</span>
    </div>
    <div class="p-4 bg-stone-50 space-y-2">
        @foreach(range(1, min((int)($block['data']['limit'] ?? 5), 4)) as $i)
            <div class="flex items-center gap-3 bg-white border border-emerald-100 rounded-xl px-3 py-2">
                <div class="size-2 rounded-full bg-amber-400 shrink-0"></div>
                <div class="flex-1 space-y-1">
                    <div class="h-2 bg-stone-200 rounded w-3/4"></div>
                    <div class="h-2 bg-stone-100 rounded w-1/2"></div>
                </div>
                <x-icon name="heroicon-o-chevron-right" class="size-4 text-emerald-400 shrink-0"/>
            </div>
        @endforeach
        <div class="flex items-center gap-1.5 pt-1">
            <x-icon name="heroicon-o-list-bullet" class="size-4 text-emerald-600"/>
            <span class="text-xs text-stone-600">{{ __('Showing') }} <strong>{{ $block['data']['limit'] ?? 5 }}</strong> {{ __('announcements') }}</span>
        </div>
    </div>
</div>
