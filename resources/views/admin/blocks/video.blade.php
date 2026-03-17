<div class="rounded-2xl border border-emerald-200 overflow-hidden shadow-sm bg-white">
    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-emerald-800 to-emerald-700 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.3) 10px, rgba(255,255,255,.3) 11px)"></div>
        <x-icon name="heroicon-o-video-camera" class="relative size-5 text-amber-300 shrink-0"/>
        <span class="relative text-sm font-semibold text-white tracking-wide">{{ __('Video') }}</span>
        <span class="relative ms-auto text-xs text-emerald-300 font-medium">فيديو</span>
    </div>
    <div class="p-4 bg-stone-50 space-y-3">
        @php
            $url = $block['data']['video_url'] ?? null;
            $isYoutube = $url && (str_contains($url, 'youtube') || str_contains($url, 'youtu.be'));
            $isVimeo = $url && str_contains($url, 'vimeo');
        @endphp
        <div class="relative aspect-video bg-stone-900 rounded-xl overflow-hidden flex items-center justify-center">
            <div class="absolute inset-0 bg-gradient-to-br from-stone-800 to-stone-900"></div>
            <div class="relative flex flex-col items-center gap-2">
                <div class="size-12 rounded-full bg-emerald-600/20 border-2 border-emerald-500 flex items-center justify-center">
                    <x-icon name="heroicon-o-play" class="size-6 text-emerald-400"/>
                </div>
                @if($isYoutube)
                    <span class="text-xs text-red-400 font-medium">YouTube</span>
                @elseif($isVimeo)
                    <span class="text-xs text-blue-400 font-medium">Vimeo</span>
                @elseif($url)
                    <span class="text-xs text-stone-400">{{ Str::limit($url, 40) }}</span>
                @else
                    <span class="text-xs text-stone-500 italic">{{ __('No video URL set') }}</span>
                @endif
            </div>
        </div>
        @if(!empty($block['data']['caption']['en'] ?? $block['data']['caption'] ?? null))
            <div class="flex items-start gap-1.5">
                <x-icon name="heroicon-o-information-circle" class="size-4 text-emerald-600 shrink-0 mt-0.5"/>
                <span class="text-xs text-stone-600 italic">{{ $block['data']['caption']['en'] ?? $block['data']['caption'] }}</span>
            </div>
        @endif
    </div>
</div>
