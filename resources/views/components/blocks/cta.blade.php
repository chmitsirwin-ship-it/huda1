@php
    $data = $block['data'] ?? $data ?? [];
    $isDark = ($data['style'] ?? 'dark') === 'dark';
@endphp

<section class="{{ $isDark ? 'bg-neutral-900 text-white' : 'bg-neutral-50 text-neutral-900' }} py-16">
    <div class="max-w-3xl mx-auto px-6 text-center">
        @if(!empty($data['title']))
            <h2 class="text-3xl font-bold mb-4">{{ $data['title'] }}</h2>
        @endif

        @if(!empty($data['description']))
            <p class="{{ $isDark ? 'text-neutral-300' : 'text-neutral-600' }} text-lg mb-8 leading-relaxed">{{ $data['description'] }}</p>
        @endif

        @if(!empty($data['button_text']) && !empty($data['button_url']))
            <a href="{{ $data['button_url'] }}"
               class="{{ $isDark ? 'bg-emerald-600 text-white hover:bg-emerald-500' : 'bg-emerald-700 text-white hover:bg-emerald-600' }} inline-flex items-center gap-2 font-semibold px-8 py-3 rounded-full transition-colors">
                {{ $data['button_text'] }}
            </a>
        @endif
    </div>
</section>
