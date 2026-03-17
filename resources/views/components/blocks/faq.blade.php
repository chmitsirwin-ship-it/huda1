@php $data = $block['data'] ?? $data ?? []; @endphp
<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-6">
        @if(!empty($data['title']))
            <h2 class="text-3xl font-bold text-neutral-900 text-center mb-12">{{ $data['title'] }}</h2>
        @endif

        @if(!empty($data['items']))
            <div class="space-y-4">
                @foreach($data['items'] as $index => $item)
                    <x-filament::section
                        :heading="$item['question'] ?? ''"
                        :collapsible="true"
                        :collapsed="$index !== 0"
                        :contained="true"
                    >
                        <p class="text-neutral-600 leading-relaxed">{{ $item['answer'] ?? '' }}</p>
                    </x-filament::section>
                @endforeach
            </div>
        @endif
    </div>
</section>
