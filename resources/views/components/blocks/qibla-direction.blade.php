<div>
    @php
        $data = $block['data'] ?? $data ?? [];
        $showDegree = $data['show_degree'] ?? true;
        $showDistance = $data['show_distance'] ?? true;
        $class = rescue(fn() => $this);
    @endphp
    @if(!($class instanceof App\Filament\Admin\Resources\Pages\Pages\EditPage or $class instanceof  App\Filament\Admin\Resources\Pages\Pages\CreatePage))
        <section class="relative py-8 sm:py-12 overflow-hidden bg-[#0d2137]"
                 x-data="{
             state: 'loading',
             bearing: 0,
             distance: 0,
             cardinal: '',
             errorMsg: '',
             kaabaLat: 21.422487,
             kaabaLng: 39.826206,

             init() { this.detect(); },

             detect() {
                 if (!navigator.geolocation) {
                     this.state = 'error';
                     this.errorMsg = '{{ __('Geolocation is not supported by your browser') }}';
                     return;
                 }
                 this.state = 'loading';
                 navigator.geolocation.getCurrentPosition(
                     (pos) => this.calculate(pos.coords.latitude, pos.coords.longitude),
                     () => {
                         this.state = 'error';
                         this.errorMsg = '{{ __('Please allow location access to find Qibla direction') }}';
                     },
                     { enableHighAccuracy: true, timeout: 10000 }
                 );
             },

             calculate(lat, lng) {
                 const toRad = (d) => d * Math.PI / 180;
                 const toDeg = (r) => r * 180 / Math.PI;
                 const latR = toRad(lat), lngR = toRad(lng);
                 const kLatR = toRad(this.kaabaLat), kLngR = toRad(this.kaabaLng);
                 const dLng = kLngR - lngR;

                 const x = Math.sin(dLng) * Math.cos(kLatR);
                 const y = Math.cos(latR) * Math.sin(kLatR) - Math.sin(latR) * Math.cos(kLatR) * Math.cos(dLng);
                 this.bearing = ((toDeg(Math.atan2(x, y)) % 360) + 360) % 360;

                 const R = 6371, dLat = kLatR - latR;
                 const a = Math.sin(dLat/2)**2 + Math.cos(latR) * Math.cos(kLatR) * Math.sin(dLng/2)**2;
                 this.distance = Math.round(R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));

                 const dirs = ['N','NE','E','SE','S','SW','W','NW'];
                 this.cardinal = dirs[Math.round(this.bearing / 45) % 8];
                 this.state = 'ready';
             }
         }">

            <div class="absolute top-0 left-0 right-0 h-0.5 bg-gradient-to-r from-emerald-600 via-emerald-400 to-emerald-600"></div>

            <div class="relative max-w-2xl mx-auto px-4 sm:px-6 text-center">

                @if(!empty($data['title']) || !empty($data['description']))
                    <div class="mb-4 sm:mb-6">
                        <div class="flex items-center justify-center gap-2 mb-2">
                            <div class="h-px w-8 bg-gradient-to-r from-transparent to-emerald-400"></div>
                            <svg class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                                <path d="M12 6l-1.5 5.5L6 13l5.5 1.5L13 20l1.5-5.5L20 13l-5.5-1.5z"/>
                            </svg>
                            <div class="h-px w-8 bg-gradient-to-l from-transparent to-emerald-400"></div>
                        </div>
                        @if(!empty($data['title']))
                            <h2 class="text-xl sm:text-2xl font-bold text-white tracking-wide">{{ $data['title'] }}</h2>
                        @endif
                        @if(!empty($data['description']))
                            <p class="text-slate-400 text-xs sm:text-sm mt-1 max-w-md mx-auto leading-relaxed">{{ $data['description'] }}</p>
                        @endif
                    </div>
                @endif

                {{-- Loading --}}
                <div x-show="state === 'loading'" x-transition class="py-6">
                    <div class="w-10 h-10 mx-auto mb-3 border-3 border-emerald-400/30 border-t-emerald-400 rounded-full animate-spin"></div>
                    <p class="text-slate-400 text-xs">{{ __('Detecting your location...') }}</p>
                </div>

                {{-- Error --}}
                <div x-show="state === 'error'" x-transition x-cloak class="py-6">
                    <div class="bg-red-500/10 border border-red-500/20 rounded-xl px-4 py-3 max-w-xs mx-auto mb-3">
                        <p class="text-red-300 text-xs" x-text="errorMsg"></p>
                    </div>
                    <button @click="detect()" class="text-emerald-400 hover:text-emerald-300 text-xs font-medium transition-colors">
                        {{ __('Try Again') }}
                    </button>
                </div>

                {{-- Compass --}}
                <div x-show="state === 'ready'" x-transition x-cloak>
                    <div class="relative w-48 h-48 sm:w-64 sm:h-64 mx-auto mb-4 sm:mb-6">
                        <svg viewBox="0 0 300 300" class="w-full h-full drop-shadow-2xl">
                            <circle cx="150" cy="150" r="145" fill="none" stroke="rgba(255,255,255,0.08)" stroke-width="1.5"/>
                            <circle cx="150" cy="150" r="140" fill="rgba(255,255,255,0.02)"/>

                            <template x-for="tick in [0,15,30,45,60,75,90,105,120,135,150,165,180,195,210,225,240,255,270,285,300,315,330,345]">
                                <line x1="150" y1="12" x2="150"
                                      :y2="[0,90,180,270].includes(tick) ? '30' : ([45,135,225,315].includes(tick) ? '25' : '20')"
                                      :stroke="[0,90,180,270].includes(tick) ? 'rgba(255,255,255,0.35)' : 'rgba(255,255,255,0.1)'"
                                      :stroke-width="[0,90,180,270].includes(tick) ? '2' : '1'"
                                      :transform="'rotate(' + tick + ' 150 150)'"/>
                            </template>

                            <text x="150" y="46" text-anchor="middle" fill="#34d399" font-size="15" font-weight="bold">N</text>
                            <text x="150" y="266" text-anchor="middle" fill="rgba(255,255,255,0.3)" font-size="13" font-weight="600">S</text>
                            <text x="264" y="155" text-anchor="middle" fill="rgba(255,255,255,0.3)" font-size="13" font-weight="600">E</text>
                            <text x="36" y="155" text-anchor="middle" fill="rgba(255,255,255,0.3)" font-size="13" font-weight="600">W</text>

                            <g :style="'transform: rotate(' + bearing + 'deg)'" style="transform-origin: 150px 150px; transition: transform 1s cubic-bezier(0.4, 0, 0.2, 1)">
                                <polygon points="150,25 141,85 150,72 159,85" fill="#10b981"/>
                                <polygon points="150,275 141,215 150,228 159,215" fill="rgba(255,255,255,0.08)"/>
                                <circle cx="150" cy="25" r="10" fill="#10b981" opacity="0.25"/>
                                <circle cx="150" cy="25" r="5" fill="#10b981"/>
                                <text x="150" y="22" text-anchor="middle" fill="white" font-size="7" font-weight="bold">🕋</text>
                            </g>

                            <circle cx="150" cy="150" r="8" fill="#0f766e"/>
                            <circle cx="150" cy="150" r="5" fill="#10b981"/>
                            <circle cx="150" cy="150" r="2" fill="white"/>
                        </svg>

                        <div class="absolute -top-1 left-1/2 -translate-x-1/2">
                            <div class="w-0 h-0 border-l-[6px] border-l-transparent border-r-[6px] border-r-transparent border-t-[8px] border-t-emerald-400"></div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3">
                        @if($showDegree)
                            <div class="inline-flex items-center gap-1.5 bg-white/5 rounded-full px-3 py-1.5 sm:px-4 sm:py-2">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v4m0 12v4m10-10h-4M6 12H2m15.07-7.07l-2.83 2.83M9.76 14.24l-2.83 2.83m12.14 0l-2.83-2.83M9.76 9.76L6.93 6.93"/>
                                </svg>
                                <span class="text-white font-bold text-sm sm:text-base tabular-nums" x-text="bearing.toFixed(1) + '°'"></span>
                                <span class="text-slate-400 text-xs" x-text="cardinal"></span>
                            </div>
                        @endif

                        @if($showDistance)
                            <div class="inline-flex items-center gap-1.5 bg-white/5 rounded-full px-3 py-1.5 sm:px-4 sm:py-2">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                                </svg>
                                <span class="text-white font-bold text-sm sm:text-base tabular-nums" x-text="distance.toLocaleString() + ' {{ __('km') }}'"></span>
                                <span class="text-slate-400 text-xs">{{ __('to Kaaba') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                @if(!empty($data['note']))
                    <p class="text-slate-500 text-[10px] sm:text-xs max-w-sm mx-auto leading-relaxed mt-4">{{ $data['note'] }}</p>
                @endif
            </div>
        </section>
    @else
        <div class="p-4 border border-dashed rounded-lg text-center text-sm text-gray-500">
            {{ __('Qibla Direction Compass will be displayed here on the live site.') }}
        </div>
    @endif

</div>

