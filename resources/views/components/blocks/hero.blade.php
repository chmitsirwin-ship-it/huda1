@php $data = $block['data'] ?? $data ?? []; @endphp
<section class="relative min-h-[80vh] flex items-center justify-center overflow-hidden bg-emerald-950">
    @if(!empty($data['background_image']))
        <img src="{{ Storage::url($data['background_image']) }}" alt="" class="absolute inset-0 w-full h-full object-cover opacity-40">
    @else
        <div class="absolute inset-0 opacity-10">
            <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                <defs>
                    <pattern id="geometric" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                        <polygon points="40,0 80,20 80,60 40,80 0,60 0,20" fill="none" stroke="#10b981" stroke-width="1"/>
                        <polygon points="40,10 70,25 70,55 40,70 10,55 10,25" fill="none" stroke="#d97706" stroke-width="0.5"/>
                        <line x1="40" y1="0" x2="40" y2="80" stroke="#10b981" stroke-width="0.3"/>
                        <line x1="0" y1="40" x2="80" y2="40" stroke="#10b981" stroke-width="0.3"/>
                        <circle cx="40" cy="40" r="8" fill="none" stroke="#d97706" stroke-width="0.5"/>
                        <circle cx="40" cy="40" r="3" fill="#d97706" opacity="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#geometric)"/>
            </svg>
        </div>
    @endif

    <div class="absolute inset-0 bg-gradient-to-b from-emerald-950/60 via-transparent to-emerald-950/80"></div>

    <div class="relative z-10 text-center text-white px-6 max-w-4xl mx-auto py-20">
        @if(!empty($data['heading']))
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight tracking-tight">
                {{ $data['heading'] }}
            </h1>
        @endif

        @if(!empty($data['subheading']))
            <p class="text-xl md:text-2xl text-emerald-100 mb-10 leading-relaxed max-w-2xl mx-auto">
                {{ $data['subheading'] }}
            </p>
        @endif

        @if(!empty($data['button_text']) && !empty($data['button_url']))
            <a href="{{ $data['button_url'] }}"
               class="inline-block bg-emerald-500 hover:bg-emerald-400 active:bg-emerald-600 text-white font-semibold px-8 py-4 rounded-lg transition-all duration-200 text-lg shadow-lg shadow-emerald-900/50 hover:shadow-emerald-900/30 hover:-translate-y-0.5">
                {{ $data['button_text'] }}
            </a>
        @endif
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent"></div>
</section>
