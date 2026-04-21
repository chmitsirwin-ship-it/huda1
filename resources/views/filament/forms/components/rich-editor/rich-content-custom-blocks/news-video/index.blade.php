@if(filled($video))
    <div class="my-6">
        <video controls preload="metadata" class="h-auto w-full rounded-xl">
            <source src="{{ \App\Support\AssetPath::url($video) }}">
            {{ __('Your browser does not support the video tag.') }}
        </video>
    </div>
@endif
