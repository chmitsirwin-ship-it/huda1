@php
$data = $block['data'] ?? $data ?? [];
$announcements = \App\Models\Announcement::active()->limit($data['limit'] ?? 5)->get();
$typeBadgeClasses = [
    'urgent'      => 'bg-red-100 text-red-700 border-red-200',
    'general'     => 'bg-blue-100 text-blue-700 border-blue-200',
    'maintenance' => 'bg-amber-100 text-amber-700 border-amber-200',
];
$typeIconPaths = [
    'urgent'      => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
    'general'     => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    'maintenance' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
];
@endphp

@if(\App\Support\PublicNavigation::isEnabled('announcements'))
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 text-emerald-600 font-medium text-sm uppercase tracking-widest mb-3">
                <span class="w-8 h-px bg-emerald-600"></span>
                {{ __('Updates') }}
                <span class="w-8 h-px bg-emerald-600"></span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-neutral-900">{{ __('Announcements') }}</h2>
        </div>

        @if($announcements->isEmpty())
            <div class="text-center py-16 text-neutral-400">
                <svg class="w-14 h-14 mx-auto mb-4 text-neutral-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <p class="text-lg">{{ __('No announcements at this time.') }}</p>
            </div>
        @else
            <div class="max-w-3xl mx-auto space-y-4">
                @foreach($announcements as $announcement)
                    @php
                        $typeKey = $announcement->type?->value ?? 'general';
                        $badgeClass = $typeBadgeClasses[$typeKey] ?? $typeBadgeClasses['general'];
                        $iconPath = $typeIconPaths[$typeKey] ?? $typeIconPaths['general'];
                    @endphp
                    <div class="bg-white rounded-xl border border-neutral-100 p-6 shadow-sm hover:shadow-md hover:border-emerald-100 transition-all duration-200 group">
                        <div class="flex items-start gap-4">
                            <div class="shrink-0 w-10 h-10 rounded-lg flex items-center justify-center {{ $typeKey === 'urgent' ? 'bg-red-50' : ($typeKey === 'maintenance' ? 'bg-amber-50' : 'bg-blue-50') }}">
                                <svg class="w-5 h-5 {{ $typeKey === 'urgent' ? 'text-red-500' : ($typeKey === 'maintenance' ? 'text-amber-500' : 'text-blue-500') }}"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full border {{ $badgeClass }}">
                                        {{ $announcement->type?->getLabel() ?? __('General') }}
                                    </span>
                                    @if($announcement->published_at)
                                        <span class="text-xs text-neutral-400 leading-relaxed">
                                            {{ \App\Support\LocalizedDate::date($announcement->published_at) }}
                                            <span class="block text-[10px] opacity-70">{{ \App\Support\LocalizedDate::hijri($announcement->published_at) }}</span>
                                        </span>
                                    @endif
                                </div>
                                <h3 class="font-bold text-neutral-900 text-lg mb-2 group-hover:text-emerald-600 transition-colors">
                                    {{ $announcement->title }}
                                </h3>
                                @if($announcement->content)
                                    <p class="text-neutral-600 text-sm leading-relaxed line-clamp-3">
                                        {{ strip_tags($announcement->content) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endif
