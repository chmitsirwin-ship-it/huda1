<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-clock" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Prayer Times') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">أوقات الصلاة</span>
    </div>
    <div class="p-4 bg-stone-50">
        <div class="grid grid-cols-3 gap-2 mb-3">
            @foreach(['Fajr' => 'فجر', 'Dhuhr' => 'ظهر', 'Asr' => 'عصر', 'Maghrib' => 'مغرب', 'Isha' => 'عشاء', 'Jumu\'ah' => 'جمعة'] as $prayer => $arabic)
                <div class="bg-emerald-900 rounded-lg px-2 py-1.5 text-center">
                    <div class="text-xs text-amber-300 font-medium">{{ $prayer }}</div>
                    <div class="text-xs text-emerald-300 font-arabic">{{ $arabic }}</div>
                </div>
            @endforeach
        </div>
        <div class="flex items-center gap-1.5">
            <x-icon name="heroicon-o-adjustments-horizontal" class="size-4 text-emerald-600"/>
            <span class="text-xs text-stone-600">{{ __('Style') }}: <strong>{{ ucfirst($block['data']['style'] ?? 'compact') }}</strong></span>
        </div>
    </div>
</div>
