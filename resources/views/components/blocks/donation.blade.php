@php $data = $block['data'] ?? $data ?? []; @endphp
<section class="bg-emerald-700 text-white py-16">
    <div class="max-w-3xl mx-auto px-6 text-center">
        @if(!empty($data['title']))
            <h2 class="text-3xl font-bold mb-4">{{ $data['title'] }}</h2>
        @endif

        @if(!empty($data['description']))
            <p class="text-emerald-100 text-lg mb-8 leading-relaxed">{{ $data['description'] }}</p>
        @endif

        @if(!empty($data['button_text']) && !empty($data['button_url']))
            <a href="{{ $data['button_url'] }}"
               class="inline-flex items-center gap-2 bg-white text-emerald-700 font-semibold px-8 py-3 rounded-full hover:bg-emerald-50 transition-colors">
                {{ $data['button_text'] }}
            </a>
        @endif
    </div>
</section>
