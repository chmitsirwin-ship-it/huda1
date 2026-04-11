<div>
    @php
        $data = $block['data'] ?? $data ?? [];
        $showDegree = $data['show_degree'] ?? true;
        $showDistance = $data['show_distance'] ?? true;
        $class = rescue(fn() => $this);
    @endphp

    @if(!($class instanceof App\Filament\Admin\Resources\Pages\Pages\EditPage or $class instanceof App\Filament\Admin\Resources\Pages\Pages\CreatePage))
        <section
            class="relative overflow-hidden bg-[#06111f] py-8 sm:py-10"
            x-data="{
                state: 'loading',
                qiblaBearing: 0,
                distance: 0,
                cardinal: '',
                heading: null,
                headingLabel: '{{ __('Compass inactive') }}',
                alignmentLabel: '{{ __('Waiting for compass') }}',
                alignmentTone: 'slate',
                errorMsg: '',
                compassSupported: false,
                compassEnabled: false,
                needsCompassPermission: false,
                locationWatchId: null,
                orientationHandler: null,
                angleDelta: 0,
                latitude: null,
                longitude: null,
                kaabaLat: 21.422487,
                kaabaLng: 39.826206,

                init() {
                    this.setupCompassAvailability();
                    this.startLocationWatch();
                    this.requestCompass();
                },

                destroy() {
                    if (this.locationWatchId !== null && navigator.geolocation) {
                        navigator.geolocation.clearWatch(this.locationWatchId);
                    }

                    if (this.orientationHandler) {
                        window.removeEventListener('deviceorientationabsolute', this.orientationHandler, true);
                        window.removeEventListener('deviceorientation', this.orientationHandler, true);
                    }
                },

                setupCompassAvailability() {
                    this.compassSupported = typeof window !== 'undefined' && 'DeviceOrientationEvent' in window;

                    if (!this.compassSupported) {
                        this.headingLabel = '{{ __('Live compass is not supported on this device') }}';
                        this.alignmentLabel = '{{ __('Compass unavailable') }}';
                        return;
                    }

                    this.needsCompassPermission = typeof DeviceOrientationEvent.requestPermission === 'function';

                    if (!this.needsCompassPermission) {
                        this.enableCompass();
                    } else {
                        this.headingLabel = '{{ __('Tap to enable compass') }}';
                    }
                },

                startLocationWatch() {
                    if (!navigator.geolocation) {
                        this.state = 'error';
                        this.errorMsg = '{{ __('Geolocation is not supported by your browser') }}';
                        return;
                    }

                    this.state = 'loading';

                    this.locationWatchId = navigator.geolocation.watchPosition(
                        (pos) => {
                            this.latitude = pos.coords.latitude;
                            this.longitude = pos.coords.longitude;
                            this.calculateQibla(pos.coords.latitude, pos.coords.longitude);
                            this.state = 'ready';
                        },
                        () => {
                            this.state = 'error';
                            this.errorMsg = '{{ __('Please allow location access to find Qibla direction') }}';
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 1000 }
                    );
                },

                async requestCompass() {
                    if (!this.compassSupported) {
                        return;
                    }

                    if (!this.needsCompassPermission) {
                        this.enableCompass();
                        return;
                    }

                    try {
                        const permission = await DeviceOrientationEvent.requestPermission();

                        if (permission === 'granted') {
                            this.enableCompass();
                            return;
                        }

                        this.headingLabel = '{{ __('Compass permission was denied') }}';
                        this.alignmentLabel = '{{ __('Compass access needed') }}';
                    } catch (error) {
                        this.headingLabel = '{{ __('Unable to access compass data') }}';
                        this.alignmentLabel = '{{ __('Compass access needed') }}';
                    }
                },

                enableCompass() {
                    if (this.compassEnabled) {
                        return;
                    }

                    this.compassEnabled = true;
                    this.headingLabel = '{{ __('Calibrating compass') }}';

                    this.orientationHandler = (event) => {
                        let nextHeading = null;

                        if (typeof event.webkitCompassHeading === 'number') {
                            nextHeading = event.webkitCompassHeading;
                        } else if (event.absolute === true && typeof event.alpha === 'number') {
                            nextHeading = (360 - event.alpha + 360) % 360;
                        } else if (typeof event.alpha === 'number') {
                            nextHeading = (360 - event.alpha + 360) % 360;
                        }

                        if (nextHeading === null || Number.isNaN(nextHeading)) {
                            return;
                        }

                        this.heading = nextHeading;
                        this.headingLabel = '{{ __('Compass active') }}';
                        this.updateAlignment();
                    };

                    window.addEventListener('deviceorientationabsolute', this.orientationHandler, true);
                    window.addEventListener('deviceorientation', this.orientationHandler, true);
                },

                calculateQibla(lat, lng) {
                    const toRad = (degrees) => degrees * Math.PI / 180;
                    const toDeg = (radians) => radians * 180 / Math.PI;
                    const latRad = toRad(lat);
                    const lngRad = toRad(lng);
                    const kaabaLatRad = toRad(this.kaabaLat);
                    const kaabaLngRad = toRad(this.kaabaLng);
                    const deltaLng = kaabaLngRad - lngRad;

                    const x = Math.sin(deltaLng) * Math.cos(kaabaLatRad);
                    const y = Math.cos(latRad) * Math.sin(kaabaLatRad) - Math.sin(latRad) * Math.cos(kaabaLatRad) * Math.cos(deltaLng);
                    this.qiblaBearing = ((toDeg(Math.atan2(x, y)) % 360) + 360) % 360;

                    const earthRadius = 6371;
                    const deltaLat = kaabaLatRad - latRad;
                    const a = Math.sin(deltaLat / 2) ** 2 + Math.cos(latRad) * Math.cos(kaabaLatRad) * Math.sin(deltaLng / 2) ** 2;
                    this.distance = Math.round(earthRadius * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));

                    const directions = ['N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW'];
                    this.cardinal = directions[Math.round(this.qiblaBearing / 45) % 8];
                    this.updateAlignment();
                },

                updateAlignment() {
                    if (this.heading === null) {
                        this.angleDelta = this.qiblaBearing;
                        this.alignmentLabel = this.needsCompassPermission ? '{{ __('Tap to enable compass') }}' : '{{ __('Move phone to calibrate') }}';
                        this.alignmentTone = 'slate';
                        return;
                    }

                    const delta = ((this.qiblaBearing - this.heading + 540) % 360) - 180;
                    this.angleDelta = delta;

                    if (Math.abs(delta) <= 8) {
                        this.alignmentLabel = '{{ __('Aligned with the Qibla') }}';
                        this.alignmentTone = 'emerald';
                        return;
                    }

                    if (delta > 0) {
                        this.alignmentLabel = '{{ __('Turn right') }}' + ' ' + Math.round(Math.abs(delta)) + '°';
                        this.alignmentTone = 'amber';
                        return;
                    }

                    this.alignmentLabel = '{{ __('Turn left') }}' + ' ' + Math.round(Math.abs(delta)) + '°';
                    this.alignmentTone = 'amber';
                },

                needleRotation() {
                    return this.heading === null ? this.qiblaBearing : this.angleDelta;
                },

                toneClasses() {
                    return this.alignmentTone === 'emerald'
                        ? 'border-emerald-400/30 bg-emerald-400/15 text-emerald-100'
                        : (this.alignmentTone === 'amber'
                            ? 'border-amber-300/30 bg-amber-300/15 text-amber-100'
                            : 'border-white/10 bg-white/5 text-slate-200');
                }
            }"
        >
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(16,185,129,0.22),transparent_34%),radial-gradient(circle_at_bottom_left,rgba(14,165,233,0.12),transparent_28%),linear-gradient(180deg,rgba(6,17,31,0.5),rgba(6,17,31,0.95))]"></div>
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-emerald-400/70 to-transparent"></div>
            <div class="absolute left-1/2 top-16 h-48 w-48 -translate-x-1/2 rounded-full bg-emerald-400/15 blur-3xl"></div>

            <div class="relative mx-auto max-w-6xl px-4 sm:px-6">
                <div class="mx-auto max-w-3xl text-center">
                    @if(!empty($data['title']) || !empty($data['description']))
                        <div class="mb-6 sm:mb-7">

                            @if(!empty($data['title']))
                                <h2 class="text-2xl font-semibold tracking-tight text-white sm:text-3xl">{{ $data['title'] }}</h2>
                            @endif

                            @if(!empty($data['description']))
                                <p class="mx-auto mt-2 max-w-xl text-sm leading-6 text-slate-300">{{ $data['description'] }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="mx-auto max-w-2xl">
                    <div class="relative overflow-hidden rounded-[1.75rem] border border-white/10 bg-white/5 p-4 shadow-[0_24px_64px_rgba(0,0,0,0.42)] backdrop-blur-xl sm:p-5">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(52,211,153,0.08),transparent_42%)]"></div>

                        <div x-show="state === 'loading'" class="relative flex min-h-[22rem] flex-col items-center justify-center text-center">
                            <div class="h-14 w-14 rounded-full border border-emerald-300/20 bg-emerald-400/10 p-2">
                                <div class="h-full w-full rounded-full border-4 border-emerald-200/15 border-t-emerald-300 animate-spin"></div>
                            </div>
                            <p class="mt-4 text-sm font-medium text-white">{{ __('Detecting your location...') }}</p>
                        </div>

                        <div x-show="state === 'error'" x-cloak class="relative flex min-h-[22rem] flex-col items-center justify-center text-center">
                            <div class="flex h-14 w-14 items-center justify-center rounded-full border border-red-400/20 bg-red-400/10 text-red-200">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008ZM10.34 3.94 1.82 18a1.75 1.75 0 0 0 1.5 2.63h17.36A1.75 1.75 0 0 0 22.18 18L13.66 3.94a1.75 1.75 0 0 0-3.32 0Z" />
                                </svg>
                            </div>
                            <p class="mt-4 text-sm font-semibold text-white" x-text="errorMsg"></p>
                            <button @click="startLocationWatch()" class="mt-4 inline-flex items-center rounded-full border border-emerald-300/20 bg-emerald-400/10 px-4 py-2 text-sm font-semibold text-emerald-100 transition hover:bg-emerald-400/20">
                                {{ __('Try Again') }}
                            </button>
                        </div>

                        <div x-show="state === 'ready'" x-cloak class="relative">
                            <div class="mb-4 flex justify-center">
                                <div class="inline-flex items-center gap-2 rounded-full border px-3.5 py-2 text-xs font-medium backdrop-blur-sm sm:text-sm" :class="toneClasses()">
                                    <span class="h-2.5 w-2.5 rounded-full" :class="alignmentTone === 'emerald' ? 'bg-emerald-300 shadow-[0_0_16px_rgba(110,231,183,0.9)]' : (alignmentTone === 'amber' ? 'bg-amber-200 shadow-[0_0_16px_rgba(253,230,138,0.8)]' : 'bg-slate-300/80')"></span>
                                    <span x-text="alignmentLabel"></span>
                                </div>
                            </div>

                            <div class="relative mx-auto flex aspect-square w-full max-w-[19rem] items-center justify-center sm:max-w-[23rem]">
                                <div class="absolute inset-6 rounded-full border border-white/8 bg-[radial-gradient(circle,rgba(255,255,255,0.08),rgba(255,255,255,0.02)_45%,transparent_68%)] shadow-[inset_0_0_80px_rgba(15,23,42,0.9)]"></div>
                                <div class="absolute inset-0 rounded-full border border-white/10 bg-white/[0.03] shadow-[0_0_0_1px_rgba(255,255,255,0.02),0_18px_60px_rgba(15,23,42,0.5)]"></div>
                                <div class="absolute inset-3 rounded-full border border-emerald-300/15"></div>

                                <template x-for="tick in Array.from({ length: 72 }, (_, index) => index * 5)" :key="tick">
                                    <div
                                        class="absolute left-1/2 top-1/2 origin-center"
                                        :style="`transform: translate(-50%, -50%) rotate(${tick}deg);`"
                                    >
                                        <div
                                            class="rounded-full"
                                            :class="tick % 90 === 0 ? 'h-8 w-1 bg-emerald-200/70' : (tick % 30 === 0 ? 'h-5 w-0.5 bg-white/45' : 'h-3 w-px bg-white/20')"
                                            :style="`transform: translateY(-9.1rem);`"
                                        ></div>
                                    </div>
                                </template>

                                <div class="absolute inset-0">
                                    <span class="absolute left-1/2 top-6 -translate-x-1/2 text-xs font-semibold tracking-[0.3em] text-emerald-200">N</span>
                                    <span class="absolute bottom-6 left-1/2 -translate-x-1/2 text-[11px] font-semibold tracking-[0.3em] text-white/45">S</span>
                                    <span class="absolute right-6 top-1/2 -translate-y-1/2 text-[11px] font-semibold tracking-[0.3em] text-white/45">E</span>
                                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-[11px] font-semibold tracking-[0.3em] text-white/45">W</span>
                                </div>

                                <div class="absolute left-1/2 top-4 z-20 -translate-x-1/2">
                                    <div class="h-0 w-0 border-x-[8px] border-b-[14px] border-x-transparent border-b-emerald-300 drop-shadow-[0_0_16px_rgba(110,231,183,0.8)]"></div>
                                </div>

                                <div
                                    class="absolute inset-0 transition-transform duration-300 ease-out"
                                    :style="`transform: rotate(${needleRotation()}deg);`"
                                >
                                    <div class="absolute left-1/2 top-[14%] z-20 h-[34%] w-1.5 -translate-x-1/2 rounded-full bg-gradient-to-b from-emerald-200 via-emerald-400 to-emerald-700 shadow-[0_0_22px_rgba(52,211,153,0.65)]"></div>
                                    <div class="absolute bottom-[17%] left-1/2 h-[26%] w-1 -translate-x-1/2 rounded-full bg-gradient-to-t from-white/10 via-white/15 to-white/40"></div>
                                    <div class="absolute left-1/2 top-[11%] -translate-x-1/2 rounded-full border border-emerald-200/30 bg-emerald-300/15 px-2 py-0.5 text-sm shadow-[0_0_24px_rgba(16,185,129,0.35)] backdrop-blur-sm">🕋</div>
                                </div>

                                <div class="absolute inset-[28%] rounded-full border border-dashed border-emerald-300/20"></div>
                                <div class="absolute flex h-20 w-20 items-center justify-center rounded-full border border-white/10 bg-slate-950/85 shadow-[inset_0_0_24px_rgba(255,255,255,0.04)]">
                                    <div class="absolute inset-3 rounded-full border border-emerald-300/15"></div>
                                    <div class="text-center">
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.28em] text-slate-400">{{ __('Qibla') }}</p>
                                        <p class="mt-1 text-xl font-semibold text-white tabular-nums" x-text="Math.round(qiblaBearing) + '°'"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 grid gap-3 sm:grid-cols-{{ $showDistance ? '3' : '2' }}">
                                @if($showDegree)
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.24em] text-slate-400">{{ __('Qibla') }}</p>
                                        <div class="mt-2 flex items-end justify-between gap-3">
                                            <span class="text-xl font-semibold text-white tabular-nums" x-text="qiblaBearing.toFixed(1) + '°'"></span>
                                            <span class="rounded-full bg-emerald-400/10 px-2.5 py-1 text-xs font-medium text-emerald-100" x-text="cardinal"></span>
                                        </div>
                                    </div>
                                @endif

                                @if($showDistance)
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                                        <p class="text-[10px] font-semibold uppercase tracking-[0.24em] text-slate-400">{{ __('Distance') }}</p>
                                        <div class="mt-2 flex items-end justify-between gap-3">
                                            <span class="text-xl font-semibold text-white tabular-nums" x-text="distance.toLocaleString() + ' {{ __('km') }}'"></span>
                                            <span class="text-xs text-slate-400">{{ __('to Kaaba') }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.24em] text-slate-400">{{ __('Heading') }}</p>
                                    <div class="mt-2 flex items-end justify-between gap-3">
                                        <span class="text-xl font-semibold text-white tabular-nums" x-text="heading === null ? '--' : heading.toFixed(1) + '°'"></span>
                                        <span class="text-xs text-slate-400">{{ __('North') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap items-center justify-center gap-3">
                    <button
                        x-show="compassSupported && needsCompassPermission"
                        x-cloak
                        @click="requestCompass()"
                        class="inline-flex items-center rounded-full border border-emerald-300/20 bg-emerald-400/10 px-4 py-2.5 text-sm font-semibold text-emerald-100 transition hover:bg-emerald-400/20"
                    >
                        {{ __('Enable Compass') }}
                    </button>

                    <div class="rounded-full border border-white/10 bg-black/20 px-3.5 py-2 text-[11px] text-slate-300 backdrop-blur-sm" x-text="headingLabel"></div>
                </div>

                @if(!empty($data['note']))
                    <p class="mx-auto mt-3 max-w-xl text-center text-[11px] leading-5 text-slate-400">{{ $data['note'] }}</p>
                @endif
            </div>
        </section>
    @else
        <div class="rounded-lg border border-dashed p-4 text-center text-sm text-gray-500">
            {{ __('Qibla Direction Compass will be displayed here on the live site.') }}
        </div>
    @endif
</div>
