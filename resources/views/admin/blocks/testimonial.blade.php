<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white" x-data="{ expanded: false }">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-chat-bubble-bottom-center-text" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Testimonials') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">شهادات</span>
    </div>
    <div class="p-4 bg-stone-50 space-y-3">
        @if(!empty($block['data']['title']['en'] ?? $block['data']['title'] ?? null))
            <div class="text-sm font-semibold text-stone-800 text-center">
                {{ $block['data']['title']['en'] ?? $block['data']['title'] }}
            </div>
        @endif
        @php $testimonials = $block['data']['testimonials'] ?? []; @endphp
        @if(count($testimonials) > 0)
            <div class="space-y-2">
                @foreach(array_slice($testimonials, 0, 2) as $t)
                    <div class="bg-white border border-emerald-100 rounded-xl p-3">
                        <div class="flex items-start gap-2">
                            <x-icon name="heroicon-o-chat-bubble-left" class="size-4 text-amber-400 shrink-0 mt-0.5"/>
                            <div class="flex-1 min-w-0">
                                @if(!empty($t['quote']['en'] ?? $t['quote'] ?? null))
                                    <p class="text-xs text-stone-600 italic line-clamp-2">{{ $t['quote']['en'] ?? $t['quote'] }}</p>
                                @endif
                                <div class="flex items-center gap-1.5 mt-1.5">
                                    <div class="size-5 rounded-full bg-emerald-100 flex items-center justify-center">
                                        <x-icon name="heroicon-o-user" class="size-3 text-emerald-600"/>
                                    </div>
                                    <span class="text-xs font-medium text-stone-700">{{ $t['name']['en'] ?? $t['name'] ?? __('Member') }}</span>
                                    @if(!empty($t['role']['en'] ?? $t['role'] ?? null))
                                        <span class="text-xs text-emerald-600">· {{ $t['role']['en'] ?? $t['role'] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @if(count($testimonials) > 2)
                    <div class="text-center">
                        <span class="text-xs text-emerald-600">+{{ count($testimonials) - 2 }} {{ __('more') }}</span>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-3 text-sm text-stone-400">{{ __('No testimonials added yet') }}</div>
        @endif
    </div>
</div>
