@extends('layouts.public')
@section('title', __('Islamic Library'))
@section('content')

    <div class="bg-emerald-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/10 mb-6">
                <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h1 class="text-4xl font-bold tracking-tight mb-3">{{ __('Islamic Library') }}</h1>
            <p class="text-emerald-200 text-lg">{{ __('Quranic verses and prophetic traditions') }}</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 py-12"
         x-data="{ tab: '{{ request()->has('hadiths_page') ? 'hadith' : 'quran' }}' }">

        <div class="flex gap-1 bg-neutral-100 p-1 rounded-xl mb-10 w-fit mx-auto">
            <button @click="tab = 'quran'"
                    :class="tab === 'quran' ? 'bg-white shadow text-emerald-700 font-semibold' : 'text-neutral-500 hover:text-neutral-700'"
                    class="px-6 py-2.5 rounded-lg text-sm transition-all duration-200">
                {{ __('Quran Verses') }}
            </button>
            <button @click="tab = 'hadith'"
                    :class="tab === 'hadith' ? 'bg-white shadow text-emerald-700 font-semibold' : 'text-neutral-500 hover:text-neutral-700'"
                    class="px-6 py-2.5 rounded-lg text-sm transition-all duration-200">
                {{ __('Hadiths') }}
            </button>
        </div>

        <div x-show="tab === 'quran'" x-cloak>
            @if($verses->isEmpty())
                <div class="text-center py-16">
                    <p class="text-neutral-500">{{ __('No verses added yet.') }}</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($verses as $verse)
                        <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 overflow-hidden">
                            <div class="bg-emerald-50 border-b border-emerald-100 px-6 py-3 flex items-center justify-between">
                                <span class="text-sm font-semibold text-emerald-700">
                                    {{ __('Surah') }} {{ $verse->surah_name ?? $verse->surah }}
                                    @if($verse->verse_number)
                                        : {{ $verse->verse_number }}
                                    @endif
                                </span>
                                <span class="text-xs text-emerald-500">{{ __('Quran') }}</span>
                            </div>
                            <div class="p-6">
                                @if($verse->arabic_text)
                                    <p class="text-right text-2xl font-arabic leading-loose text-neutral-900 mb-5" dir="rtl">
                                        {{ $verse->arabic_text }}
                                    </p>
                                @endif
                                @if($verse->translation)
                                    <p class="text-neutral-700 leading-relaxed border-t border-neutral-100 pt-4">
                                        {{ $verse->translation }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $verses->appends(['hadiths_page' => request('hadiths_page')])->links() }}
                </div>
            @endif
        </div>

        <div x-show="tab === 'hadith'" x-cloak>
            @if($hadiths->isEmpty())
                <div class="text-center py-16">
                    <p class="text-neutral-500">{{ __('No hadiths added yet.') }}</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($hadiths as $hadith)
                        <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 overflow-hidden">
                            <div class="bg-amber-50 border-b border-amber-100 px-6 py-3 flex items-center justify-between">
                                <span class="text-sm font-semibold text-amber-700">
                                    {{ $hadith->source ?? __('Hadith') }}
                                </span>
                                @if($hadith->grade)
                                    @php
                                        $gradeClasses = match(strtolower($hadith->grade)) {
                                            'sahih'   => 'bg-emerald-100 text-emerald-700',
                                            'hasan'   => 'bg-blue-100 text-blue-700',
                                            'daif'    => 'bg-red-100 text-red-600',
                                            default   => 'bg-neutral-100 text-neutral-600',
                                        };
                                    @endphp
                                    <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $gradeClasses }} capitalize">
                                        {{ $hadith->grade }}
                                    </span>
                                @endif
                            </div>
                            <div class="p-6">
                                <p class="text-neutral-800 leading-relaxed text-base mb-4">
                                    {{ $hadith->text }}
                                </p>
                                @if($hadith->narrator)
                                    <p class="text-sm text-neutral-500 italic">
                                        {{ __('Narrated by') }}: {{ $hadith->narrator }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $hadiths->appends(['verses_page' => request('verses_page')])->links() }}
                </div>
            @endif
        </div>

    </div>

@endsection
