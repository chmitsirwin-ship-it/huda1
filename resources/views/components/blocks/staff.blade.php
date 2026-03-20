@php
$data = $block['data'] ?? $data ?? [];
$members = \App\Models\Staff::active()->get();
@endphp

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 text-emerald-600 font-medium text-sm uppercase tracking-widest mb-3">
                <span class="w-8 h-px bg-emerald-600"></span>
                {{ __('Our People') }}
                <span class="w-8 h-px bg-emerald-600"></span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900">{{ __('Meet Our Staff') }}</h2>
        </div>

        @if($members->isEmpty())
            <div class="text-center py-16 text-neutral-400">
                <svg class="w-14 h-14 mx-auto mb-4 text-neutral-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p class="text-lg">{{ __('No staff members found.') }}</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($members as $member)
                    @php
                        $name = $member->name;
                        $initials = collect(explode(' ', $name))->take(2)->map(fn($w) => strtoupper(substr($w, 0, 1)))->implode('');
                    @endphp
                    <div class="group text-center">
                        <div class="relative inline-block mb-5">
                            @if($member->photo)
                                <img src="{{ Storage::url($member->photo) }}"
                                     alt="{{ $name }}"
                                     class="w-32 h-32 rounded-full object-cover mx-auto ring-4 ring-white shadow-lg group-hover:ring-emerald-200 group-hover:shadow-emerald-100 transition-all duration-300">
                            @else
                                <div class="w-32 h-32 rounded-full mx-auto bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center ring-4 ring-white shadow-lg group-hover:ring-emerald-200 transition-all duration-300">
                                    <span class="text-white text-2xl font-bold tracking-wide">{{ $initials }}</span>
                                </div>
                            @endif
                            <div class="absolute bottom-1 right-1 w-5 h-5 rounded-full bg-emerald-500 border-2 border-white opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <h3 class="font-bold text-neutral-900 text-lg mb-1 group-hover:text-emerald-600 transition-colors">
                            {{ $name }}
                        </h3>

                        @if($member->title)
                            <p class="text-emerald-600 font-medium text-sm mb-3">
                                {{ $member->title }}
                            </p>
                        @endif

                        @if($member->bio)
                            <p class="text-neutral-500 text-sm leading-relaxed line-clamp-3 max-w-xs mx-auto">
                                {{ $member->bio }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
