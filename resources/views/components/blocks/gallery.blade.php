@php
$data = $block['data'] ?? $data ?? [];
if (!empty($data['collection'])) {
    $images = \App\Models\MediaItem::images()->byCollection($data['collection'])->limit(12)->get();
} else {
    $images = \App\Models\MediaItem::images()->limit($data['limit'] ?? 12)->get();
}
@endphp

@if($images->isNotEmpty())
<section class="py-20 bg-neutral-50"
    x-data="{
        lightbox: false,
        activeIndex: 0,
        images: {{ $images->map(fn($img) => ['src' => Storage::url($img->file_path), 'title' => $img->getTranslation('title', app()->getLocale(), false) ?? ''])->toJson() }},
        open(index) {
            this.activeIndex = index;
            this.lightbox = true;
            document.body.style.overflow = 'hidden';
        },
        close() {
            this.lightbox = false;
            document.body.style.overflow = '';
        },
        prev() {
            this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length;
        },
        next() {
            this.activeIndex = (this.activeIndex + 1) % this.images.length;
        }
    }"
    @keydown.escape.window="close()"
    @keydown.arrow-left.window="lightbox && prev()"
    @keydown.arrow-right.window="lightbox && next()">

    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 text-emerald-600 font-medium text-sm uppercase tracking-widest mb-3">
                <span class="w-8 h-px bg-emerald-600"></span>
                {{ __('Memories') }}
                <span class="w-8 h-px bg-emerald-600"></span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900">{{ __('Gallery') }}</h2>
        </div>

        <div class="columns-2 sm:columns-3 lg:columns-4 gap-4 space-y-4">
            @foreach($images as $index => $image)
                <div class="break-inside-avoid cursor-pointer overflow-hidden rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 group"
                     @click="open({{ $index }})">
                    <div class="relative overflow-hidden">
                        <img src="{{ Storage::url($image->file_path) }}"
                             alt="{{ $image->getTranslation('alt_text', app()->getLocale(), false) ?? $image->getTranslation('title', app()->getLocale(), false) ?? '' }}"
                             class="w-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-emerald-900/0 group-hover:bg-emerald-900/30 transition-all duration-300 flex items-center justify-center">
                            <svg class="w-10 h-10 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-lg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div x-show="lightbox"
         x-cloak
         class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-4"
         x-transition:enter="transition duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="close()">

        <button @click="close()"
                class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors z-10">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <button @click="prev()"
                class="absolute left-4 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>

        <div class="max-w-5xl max-h-[85vh] flex items-center justify-center">
            <img :src="images[activeIndex]?.src"
                 :alt="images[activeIndex]?.title"
                 class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl">
        </div>

        <button @click="next()"
                class="absolute right-4 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        <div x-show="images[activeIndex]?.title"
             class="absolute bottom-4 left-0 right-0 text-center text-white/80 text-sm px-8">
            <span x-text="images[activeIndex]?.title"></span>
        </div>

        <div class="absolute bottom-10 left-0 right-0 text-center text-white/40 text-xs">
            <span x-text="(activeIndex + 1) + ' / ' + images.length"></span>
        </div>
    </div>
</section>
@endif
