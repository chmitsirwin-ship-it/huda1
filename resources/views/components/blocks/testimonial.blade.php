@php $data = $block['data'] ?? $data ?? []; @endphp
<section class="py-16 bg-neutral-50">
    <div class="max-w-7xl mx-auto px-6">
        @if(!empty($data['title']))
            <h2 class="text-3xl font-bold text-neutral-900 text-center mb-12">{{ $data['title'] }}</h2>
        @endif

        @if(!empty($data['testimonials']))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($data['testimonials'] as $testimonial)
                    <div class="bg-white rounded-2xl p-8 shadow-sm border border-neutral-100">
                        <svg class="w-8 h-8 text-emerald-400 mb-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>

                        @if(!empty($testimonial['quote']))
                            <p class="text-neutral-600 leading-relaxed mb-6">{{ $testimonial['quote'] }}</p>
                        @endif

                        <div>
                            @if(!empty($testimonial['name']))
                                <div class="font-semibold text-neutral-900">{{ $testimonial['name'] }}</div>
                            @endif
                            @if(!empty($testimonial['role']))
                                <div class="text-sm text-emerald-600">{{ $testimonial['role'] }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
