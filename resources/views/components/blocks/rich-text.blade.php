@php $data = $block['data'] ?? $data ?? []; @endphp
<section class="py-12">
    <div class="max-w-4xl mx-auto px-6">
        <div class="prose prose-emerald max-w-none">
            {!! $data['content'] ?? '' !!}
        </div>
    </div>
</section>
