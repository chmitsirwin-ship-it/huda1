@if(filled($audio))
    <div class="my-6">
        <audio controls preload="metadata" class="w-full">
            <source src="{{ \App\Support\AssetPath::url($audio) }}">
            {{ __('Your browser does not support the audio element.') }}
        </audio>
    </div>
@endif
