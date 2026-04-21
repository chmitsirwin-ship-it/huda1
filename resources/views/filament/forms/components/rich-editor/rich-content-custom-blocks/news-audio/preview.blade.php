<div class="rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-700">
    {{ filled($audio) ? __('Audio file: :name', ['name' => basename($audio)]) : __('Audio file') }}
</div>
