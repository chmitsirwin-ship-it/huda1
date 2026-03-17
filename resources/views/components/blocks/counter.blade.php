@php $data = $block['data'] ?? $data ?? []; @endphp
<section class="py-16 bg-emerald-900 text-white">
    <div class="max-w-7xl mx-auto px-6">
        @if(!empty($data['title']))
            <h2 class="text-3xl font-bold text-center mb-12">{{ $data['title'] }}</h2>
        @endif

        @if(!empty($data['counters']))
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @foreach($data['counters'] as $counter)
                    <div class="text-center">
                        <div class="text-4xl font-bold text-amber-400 mb-2">{{ $counter['value'] ?? '' }}</div>
                        <div class="text-emerald-200 text-sm font-medium uppercase tracking-wider">{{ $counter['label'] ?? '' }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
