@php
$data = $block['data'] ?? $data ?? [];
$hadith = null;
if (!empty($data['hadith_id'])) {
    $hadith = \App\Models\Hadith::find($data['hadith_id']);
}
if (!$hadith && !empty($data['id'])) {
    $hadith = \App\Models\Hadith::find($data['id']);
}
if (!$hadith) {
    $hadith = \App\Models\Hadith::where('is_featured', true)->inRandomOrder()->first();
}
@endphp

@if($hadith)
<section class="py-20 bg-stone-50 relative overflow-hidden">
    <div class="absolute top-0 left-0 w-48 h-48 bg-emerald-100 rounded-full blur-3xl opacity-40 -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-64 h-64 bg-amber-100 rounded-full blur-3xl opacity-30 translate-x-1/2 translate-y-1/2"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-6">
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 text-emerald-600 font-medium text-sm uppercase tracking-widest mb-3">
                <span class="w-8 h-px bg-emerald-600"></span>
                {{ __('Hadith of the Day') }}
                <span class="w-8 h-px bg-emerald-600"></span>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-lg border border-neutral-100 overflow-hidden">
            <div class="h-1.5 bg-gradient-to-r from-emerald-400 via-emerald-500 to-amber-400"></div>

            <div class="p-8 md:p-12">
                <div class="relative">
                    <div class="absolute -top-2 -left-2 text-emerald-200">
                        <svg class="w-14 h-14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/>
                            <path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/>
                        </svg>
                    </div>

                    <blockquote class="pt-6 pl-8">
                        <p class="text-neutral-800 text-xl md:text-2xl leading-relaxed" style="font-family: 'Amiri', serif; line-height: 2.2;">
                            {{ $hadith->text }}
                        </p>
                    </blockquote>
                </div>

                <div class="mt-10 pt-6 border-t border-neutral-100 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        @if($hadith->narrator)
                            <p class="text-emerald-700 font-semibold">
                                {{ __('Narrated by') }}: <span class="italic">{{ $hadith->narrator }}</span>
                            </p>
                        @endif
                        @if($hadith->source)
                            <p class="text-neutral-500 text-sm mt-1">
                                {{ $hadith->source }}
                            </p>
                        @endif
                    </div>

                    @if($hadith->grade)
                        <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-sm font-semibold px-3 py-1.5 rounded-full">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                            {{ $hadith->grade }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif
