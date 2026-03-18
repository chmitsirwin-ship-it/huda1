@php
$data = $block['data'] ?? $data ?? [];
$title = $data['title'] ?? null;
$description = $data['description'] ?? null;

@endphp

<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-6">

        @if($title || $description)
            <div class="text-center mb-10">
                @if($title)
                    <h2 class="text-3xl font-bold text-neutral-900 mb-3">{{ $title }}</h2>
                @endif
                @if($description)
                    <p class="text-neutral-600 text-lg leading-relaxed">{{ $description }}</p>
                @endif
            </div>
        @endif
        <div class="bg-white rounded-2xl border border-neutral-200 shadow-sm p-8">
            <livewire:contact-form />
        </div>
    </div>
</section>
