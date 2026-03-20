@extends('layouts.public')
@section('title', __('Announcements'))
@section('content')

    <div class="bg-emerald-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-4xl font-bold tracking-tight mb-3">{{ __('Announcements') }}</h1>
            <p class="text-emerald-200 text-lg">{{ __('Important notices and updates from the mosque') }}</p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 py-12">

        @if($announcements->isEmpty())
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 mb-6">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-neutral-700 mb-2">{{ __('No announcements') }}</h3>
                <p class="text-neutral-500">{{ __('There are no announcements at this time.') }}</p>
            </div>
        @else
            <div class="space-y-5">
                @foreach($announcements as $announcement)
                    @php
                        $typeValue = $announcement->type?->value ?? $announcement->type ?? 'general';
                        $typeLabel = $announcement->type?->getLabel() ?? ucfirst($typeValue);
                        $badgeClasses = match($typeValue) {
                            'urgent'      => 'bg-red-100 text-red-700 border border-red-200',
                            'maintenance' => 'bg-amber-100 text-amber-700 border border-amber-200',
                            default       => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                        };
                        $borderClasses = match($typeValue) {
                            'urgent'      => 'border-l-red-500',
                            'maintenance' => 'border-l-amber-500',
                            default       => 'border-l-emerald-500',
                        };
                    @endphp

                    <div class="bg-white rounded-2xl shadow-sm border border-neutral-100 border-l-4 {{ $borderClasses }} p-6 hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full {{ $badgeClasses }} capitalize">
                                    {{ $typeLabel }}
                                </span>
                                <h3 class="text-lg font-bold text-neutral-900">
                                    {{ $announcement->title }}
                                </h3>
                            </div>
                            <span class="text-xs text-neutral-400 whitespace-nowrap shrink-0">
                                {{ $announcement->created_at->format('M d, Y') }}
                            </span>
                        </div>
                        <p class="text-neutral-600 leading-relaxed">
                            {{ \Illuminate\Support\Str::limit(strip_tags($announcement->content), 200) }}
                        </p>
                    </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $announcements->links() }}
            </div>
        @endif

    </div>

@endsection
