@extends('layouts.public')
@section('title', __('Our Team'))
@section('content')

    <div class="bg-emerald-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h1 class="text-4xl font-bold tracking-tight mb-3">{{ __('Our Team') }}</h1>
            <p class="text-emerald-200 text-lg">{{ __('Meet the dedicated staff and scholars of our mosque') }}</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 py-16">

        @if($staff->isEmpty())
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 mb-6">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-neutral-700 mb-2">{{ __('No staff profiles yet') }}</h3>
                <p class="text-neutral-500">{{ __('Staff profiles will be listed here.') }}</p>
            </div>
        @else
            <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach($staff as $member)
                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 p-6 text-center hover:shadow-lg hover:-translate-y-1 transition-all duration-200">

                        @if($member->photo)
                            <div class="mb-5 flex justify-center">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($member->photo) }}"
                                     alt="{{ $member->getTranslation('name', app()->getLocale(), false) }}"
                                     class="w-24 h-24 rounded-full object-cover ring-4 ring-emerald-100">
                            </div>
                        @else
                            @php
                                $name = $member->getTranslation('name', app()->getLocale(), false);
                                $initials = collect(explode(' ', $name))->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
                            @endphp
                            <div class="mb-5 flex justify-center">
                                <div class="w-24 h-24 rounded-full bg-emerald-100 flex items-center justify-center ring-4 ring-emerald-50">
                                    <span class="text-2xl font-bold text-emerald-700">{{ $initials }}</span>
                                </div>
                            </div>
                        @endif

                        <h3 class="text-lg font-bold text-neutral-900 mb-1">
                            {{ $member->getTranslation('name', app()->getLocale(), false) }}
                        </h3>

                        @if($member->title)
                            <p class="text-sm font-medium text-emerald-700 mb-3">
                                {{ $member->getTranslation('title', app()->getLocale(), false) }}
                            </p>
                        @endif

                        @if($member->bio)
                            <p class="text-sm text-neutral-500 leading-relaxed">
                                {{ \Illuminate\Support\Str::limit($member->getTranslation('bio', app()->getLocale(), false), 120) }}
                            </p>
                        @endif

                    </div>
                @endforeach
            </div>
        @endif

    </div>

@endsection
