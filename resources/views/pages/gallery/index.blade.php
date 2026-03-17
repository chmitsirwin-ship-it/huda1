@extends('layouts.public')
@section('title', __('Gallery'))
@section('content')

    <div class="bg-emerald-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-4xl font-bold tracking-tight mb-3">{{ __('Gallery') }}</h1>
            <p class="text-emerald-200 text-lg">{{ __('Photos and memories from our community') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-10"
         x-data="{
             lightbox: false,
             activeImage: '',
             activeAlt: '',
             openLightbox(src, alt) {
                 this.activeImage = src;
                 this.activeAlt = alt;
                 this.lightbox = true;
             }
         }">

        @if($collections->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-8">
                <a href="{{ route('gallery.index') }}"
                   class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ is_null($collection) ? 'bg-emerald-700 text-white' : 'bg-neutral-100 text-neutral-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                    {{ __('All') }}
                </a>
                @foreach($collections as $col)
                    <a href="{{ route('gallery.index', ['collection' => $col]) }}"
                       class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $collection === $col ? 'bg-emerald-700 text-white' : 'bg-neutral-100 text-neutral-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                        {{ $col }}
                    </a>
                @endforeach
            </div>
        @endif

        @if($items->isEmpty())
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 mb-6">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-neutral-700 mb-2">{{ __('No photos yet') }}</h3>
                <p class="text-neutral-500">{{ __('Check back soon for photos from our community.') }}</p>
            </div>
        @else
            <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4">
                @foreach($items as $item)
                    <div class="break-inside-avoid rounded-xl overflow-hidden cursor-pointer group"
                         @click="openLightbox('{{ \Illuminate\Support\Facades\Storage::url($item->file_path) }}', '{{ $item->getTranslation('alt_text', app()->getLocale(), false) }}')">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($item->file_path) }}"
                             alt="{{ $item->getTranslation('alt_text', app()->getLocale(), false) }}"
                             class="w-full object-cover group-hover:scale-105 transition-transform duration-300 rounded-xl">
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $items->links() }}
            </div>
        @endif

        <div x-show="lightbox"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4"
             @click.self="lightbox = false"
             @keydown.escape.window="lightbox = false"
             x-cloak>
            <div class="relative max-w-5xl max-h-full w-full flex items-center justify-center">
                <button @click="lightbox = false"
                        class="absolute top-0 right-0 -mt-10 -mr-2 text-white/70 hover:text-white p-2 rounded-full transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <img :src="activeImage" :alt="activeAlt" class="max-h-screen max-w-full rounded-xl shadow-2xl object-contain">
            </div>
        </div>

    </div>

@endsection
