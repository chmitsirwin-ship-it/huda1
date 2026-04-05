<nav class="bg-white shadow-sm sticky top-0 z-50" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <a href="{{ route('home') }}" class="flex items-center shrink-0 h-16 py-2">
                @if(setting('branding.logo'))
                    <img
                            src="{{ \Illuminate\Support\Facades\Storage::url(setting('branding.logo')) }}"
                            alt="{{ setting('general.name') }}"
                            class="h-15 w-auto object-contain"
                    >
                @endif
            </a>

            <div class="hidden lg:flex items-center gap-1">
                @php
                    $navLinks = [
                        ['route' => 'home', 'label' => __('Home')],
                        ['route' => 'prayer-times.index', 'label' => __('Prayer Times')],
                        ['route' => 'events.index', 'label' => __('Events')],
                        ['route' => 'announcements.index', 'label' => __('Announcements')],
                        ['route' => 'news.index', 'label' => __('News')],
                        ['route' => 'gallery.index', 'label' => __('Gallery')],
                        ['route' => 'islamic-library.index', 'label' => __('Library')],
                        ['route' => 'khutba.index', 'label' => __('Khutba')],
                        ['route' => 'staff.index', 'label' => __('Staff')],
                        ['route' => 'contact.index', 'label' => __('Contact')],
                    ];
                @endphp

                @foreach($navLinks as $link)
                    @php
                        $isActive = request()->routeIs($link['route']);
                    @endphp
                    <a href="{{ route($link['route']) }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150 {{ $isActive ? 'text-emerald-600 bg-emerald-50' : 'text-neutral-600 hover:text-emerald-600 hover:bg-neutral-50' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="hidden lg:flex items-center gap-2">
                @php
                    $languages = \App\Models\Language::active()->get();
                @endphp
                @foreach($languages as $language)
                    <a href="{{ request()->fullUrlWithQuery(['lang' => $language->code]) }}"
                       class="px-2.5 py-1 rounded text-xs font-medium border transition-colors duration-150 {{ app()->getLocale() === $language->code ? 'bg-emerald-600 text-white border-emerald-600' : 'text-neutral-600 border-neutral-300 hover:border-emerald-500 hover:text-emerald-600' }}">
                        {{ strtoupper($language->code) }}
                    </a>
                @endforeach
            </div>

            <button @click="open = !open"
                    class="lg:hidden p-2 rounded-md text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 transition-colors"
                    :aria-expanded="open">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="lg:hidden border-t border-neutral-100 bg-white"
         @click.away="open = false">
        <div class="px-4 py-3 space-y-1">
            @foreach($navLinks as $link)
                @php
                    $isActive = request()->routeIs($link['route']);
                @endphp
                <a href="{{ route($link['route']) }}"
                   class="block px-3 py-2 rounded-md text-sm font-medium transition-colors {{ $isActive ? 'text-emerald-600 bg-emerald-50' : 'text-neutral-600 hover:text-emerald-600 hover:bg-neutral-50' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach

            @if($languages->count() > 0)
                <div class="pt-3 border-t border-neutral-100 flex items-center gap-2 flex-wrap">
                    @foreach($languages as $language)
                        <a href="{{ request()->fullUrlWithQuery(['lang' => $language->code]) }}"
                           class="px-2.5 py-1 rounded text-xs font-medium border transition-colors {{ app()->getLocale() === $language->code ? 'bg-emerald-600 text-white border-emerald-600' : 'text-neutral-600 border-neutral-300 hover:border-emerald-500 hover:text-emerald-600' }}">
                            {{ strtoupper($language->code) }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</nav>
