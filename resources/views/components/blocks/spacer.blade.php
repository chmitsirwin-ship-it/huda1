@php
$data = $block['data'] ?? $data ?? [];
$sizes = ['sm' => 'py-4', 'md' => 'py-8', 'lg' => 'py-16', 'xl' => 'py-24'];
$class = $sizes[$data['size'] ?? 'md'] ?? 'py-8';
@endphp
<div class="{{ $class }}"></div>
