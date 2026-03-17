@php
$data = $block['data'] ?? $data ?? [];
$verse = null;
if (!empty($data['surah_number']) && !empty($data['verse_number'])) {
    $verse = \App\Models\QuranVerse::where('surah_number', $data['surah_number'])
        ->where('verse_number', $data['verse_number'])
        ->first();
}
if (!$verse) {
    $verse = \App\Models\QuranVerse::featured()->inRandomOrder()->first();
}
@endphp

@if($verse)
<section class="py-20 bg-gradient-to-br from-emerald-950 to-emerald-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
            <defs>
                <pattern id="quran-geo" x="0" y="0" width="120" height="120" patternUnits="userSpaceOnUse">
                    <polygon points="60,0 120,30 120,90 60,120 0,90 0,30" fill="none" stroke="#d97706" stroke-width="1"/>
                    <polygon points="60,15 105,37.5 105,82.5 60,105 15,82.5 15,37.5" fill="none" stroke="#10b981" stroke-width="0.5"/>
                    <circle cx="60" cy="60" r="20" fill="none" stroke="#d97706" stroke-width="0.5"/>
                    <circle cx="60" cy="60" r="6" fill="#d97706" opacity="0.3"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#quran-geo)"/>
        </svg>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 text-amber-400 font-medium text-sm uppercase tracking-widest mb-3">
                <span class="w-8 h-px bg-amber-400"></span>
                {{ __('Verse of the Day') }}
                <span class="w-8 h-px bg-amber-400"></span>
            </div>
        </div>

        <div class="bg-white/5 backdrop-blur-sm rounded-3xl border border-white/10 overflow-hidden shadow-2xl">
            <div class="relative px-8 md:px-16 pt-12 pb-8">
                <div class="absolute top-6 left-8 text-amber-400/20">
                    <svg class="w-16 h-16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/>
                        <path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/>
                    </svg>
                </div>

                <div class="text-right mb-8" dir="rtl">
                    <p class="text-white text-2xl md:text-4xl leading-loose font-serif" style="font-family: 'Amiri', 'Scheherazade New', 'Traditional Arabic', serif; line-height: 2.2;">
                        {{ $verse->arabic_text }}
                    </p>
                </div>

                @if($verse->getTranslation('translation', app()->getLocale(), false))
                    <div class="border-t border-white/10 pt-6">
                        <p class="text-emerald-100 text-lg md:text-xl leading-relaxed italic text-center">
                            "{{ $verse->getTranslation('translation', app()->getLocale(), false) }}"
                        </p>
                    </div>
                @endif
            </div>

            <div class="px-8 md:px-16 py-5 bg-white/5 border-t border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-400/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <span class="text-amber-400 font-semibold">
                        {{ $verse->surah_name ?? __('Surah') }} — {{ __('Verse') }} {{ $verse->verse_number }}
                    </span>
                </div>
                <span class="text-emerald-300/60 text-sm">({{ $verse->surah_number }}:{{ $verse->verse_number }})</span>
            </div>
        </div>
    </div>
</section>
@endif
