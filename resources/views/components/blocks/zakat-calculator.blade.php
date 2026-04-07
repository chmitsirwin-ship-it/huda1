@php
    $data = $block['data'] ?? $data ?? [];
    $title = trim((string) ($data['title'] ?? ''));
    $description = trim((string) ($data['description'] ?? ''));
    $currencySymbol = (string) ($data['currency_symbol'] ?? '$');
    $currencySymbol = $currencySymbol !== '' ? $currencySymbol : '$';
    $nisabGold = max((float) ($data['nisab_gold'] ?? 0), 0);
    $nisabSilver = max((float) ($data['nisab_silver'] ?? 0), 0);
    $zakatFitrAmount = max((float) ($data['zakat_fitr_amount'] ?? 0), 0);
    $showFitr = (bool) ($data['show_fitr'] ?? true);
    $donationUrl = trim((string) ($data['donation_url'] ?? ''));
    $donationButtonText = trim((string) ($data['donation_button_text'] ?? ''));
    $heading = $title !== '' ? $title : __('Zakat Calculator');
    $ctaLabel = $donationButtonText !== '' ? $donationButtonText : __('Donate Zakat');
@endphp

<section class="bg-white py-16">
    <div class="mx-auto max-w-3xl px-6">
        <div class="mb-10 text-center">
            <div class="mb-3 inline-flex items-center gap-2 text-sm font-medium uppercase tracking-widest text-emerald-600">
                <span class="h-px w-8 bg-emerald-600"></span>
                {{ __('Zakat Guidance') }}
                <span class="h-px w-8 bg-emerald-600"></span>
            </div>
            <h2 class="text-3xl font-bold text-neutral-900 md:text-4xl">{{ $heading }}</h2>
            @if($description !== '')
                <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-neutral-600 md:text-base">{{ $description }}</p>
            @endif
        </div>

        <div
            class="rounded-3xl border border-neutral-200 bg-white p-6 shadow-sm md:p-8"
            x-data="{
                currencySymbol: @js($currencySymbol),
                nisabGold: {{ $nisabGold }},
                nisabSilver: {{ $nisabSilver }},
                zakatFitrAmount: {{ $zakatFitrAmount }},
                cash: 0,
                bankSavings: 0,
                goldValue: 0,
                silverValue: 0,
                investments: 0,
                businessAssets: 0,
                debtsOwedToYou: 0,
                debtsYouOwe: 0,
                familyMembers: 1,
                toNumber(value) {
                    const number = parseFloat(value);
                    return Number.isFinite(number) && number > 0 ? number : 0;
                },
                formatCurrency(value) {
                    return this.currencySymbol + this.toNumber(value).toFixed(2);
                },
                get totalAssets() {
                    return [this.cash, this.bankSavings, this.goldValue, this.silverValue, this.investments, this.businessAssets, this.debtsOwedToYou]
                        .reduce((total, value) => total + this.toNumber(value), 0);
                },
                get totalLiabilities() {
                    return this.toNumber(this.debtsYouOwe);
                },
                get netZakatableAssets() {
                    return Math.max(this.totalAssets - this.totalLiabilities, 0);
                },
                get activeNisab() {
                    const values = [this.nisabGold, this.nisabSilver].filter((value) => value > 0);
                    return values.length ? Math.min(...values) : 0;
                },
                get hasNisab() {
                    return this.activeNisab > 0;
                },
                get isAboveNisab() {
                    return this.hasNisab && this.netZakatableAssets >= this.activeNisab;
                },
                get zakatDue() {
                    return this.isAboveNisab ? this.netZakatableAssets * 0.025 : 0;
                },
                get hasFitrAmount() {
                    return this.zakatFitrAmount > 0;
                },
                get fitrTotal() {
                    return this.hasFitrAmount ? this.toNumber(this.familyMembers) * this.zakatFitrAmount : 0;
                }
            }"
        >
            <div class="grid gap-8">
                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-5">
                        <div>
                            <h3 class="text-xl font-bold text-neutral-900">{{ __('Zakat al-Mal') }}</h3>
                            <p class="mt-1 text-sm text-neutral-500">{{ __('Enter the current value of your zakatable assets and liabilities for an estimate.') }}</p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="mb-1 block text-sm font-medium text-neutral-700">{{ __('Cash') }}</span>
                                <input x-model="cash" type="number" min="0" step="0.01" class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </label>
                            <label class="block">
                                <span class="mb-1 block text-sm font-medium text-neutral-700">{{ __('Bank Savings') }}</span>
                                <input x-model="bankSavings" type="number" min="0" step="0.01" class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </label>
                            <label class="block">
                                <span class="mb-1 block text-sm font-medium text-neutral-700">{{ __('Gold Value') }}</span>
                                <input x-model="goldValue" type="number" min="0" step="0.01" class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </label>
                            <label class="block">
                                <span class="mb-1 block text-sm font-medium text-neutral-700">{{ __('Silver Value') }}</span>
                                <input x-model="silverValue" type="number" min="0" step="0.01" class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </label>
                            <label class="block">
                                <span class="mb-1 block text-sm font-medium text-neutral-700">{{ __('Investments') }}</span>
                                <input x-model="investments" type="number" min="0" step="0.01" class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </label>
                            <label class="block">
                                <span class="mb-1 block text-sm font-medium text-neutral-700">{{ __('Business Assets') }}</span>
                                <input x-model="businessAssets" type="number" min="0" step="0.01" class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </label>
                            <label class="block">
                                <span class="mb-1 block text-sm font-medium text-neutral-700">{{ __('Debts Owed To You') }}</span>
                                <input x-model="debtsOwedToYou" type="number" min="0" step="0.01" class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </label>
                            <label class="block">
                                <span class="mb-1 block text-sm font-medium text-neutral-700">{{ __('Debts You Owe') }}</span>
                                <input x-model="debtsYouOwe" type="number" min="0" step="0.01" class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </label>
                        </div>

                        <div class="rounded-2xl border border-neutral-200 bg-neutral-50 p-4 text-sm text-neutral-600">
                            <div class="flex flex-wrap gap-4">
                                <div>
                                    <span class="font-medium text-neutral-800">{{ __('Gold Nisab') }}:</span>
                                    <span x-text="nisabGold > 0 ? formatCurrency(nisabGold) : @js(__('Not Set'))"></span>
                                </div>
                                <div>
                                    <span class="font-medium text-neutral-800">{{ __('Silver Nisab') }}:</span>
                                    <span x-text="nisabSilver > 0 ? formatCurrency(nisabSilver) : @js(__('Not Set'))"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">
                        <h3 class="text-xl font-bold text-emerald-900">{{ __('Your Estimate') }}</h3>
                        <div class="mt-5 space-y-4 text-sm text-emerald-950">
                            <div class="flex items-center justify-between gap-4 border-b border-emerald-100 pb-3">
                                <span>{{ __('Total Assets') }}</span>
                                <span class="font-semibold" x-text="formatCurrency(totalAssets)"></span>
                            </div>
                            <div class="flex items-center justify-between gap-4 border-b border-emerald-100 pb-3">
                                <span>{{ __('Liabilities') }}</span>
                                <span class="font-semibold" x-text="formatCurrency(totalLiabilities)"></span>
                            </div>
                            <div class="flex items-center justify-between gap-4 border-b border-emerald-100 pb-3">
                                <span>{{ __('Net Zakatable Assets') }}</span>
                                <span class="font-semibold" x-text="formatCurrency(netZakatableAssets)"></span>
                            </div>
                            <div class="flex items-center justify-between gap-4 border-b border-emerald-100 pb-3">
                                <span>{{ __('Active Nisab') }}</span>
                                <span class="font-semibold" x-text="hasNisab ? formatCurrency(activeNisab) : @js(__('Not Configured'))"></span>
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl bg-white/80 p-5 shadow-sm ring-1 ring-emerald-100">
                            <template x-if="!hasNisab">
                                <div>
                                    <p class="text-sm font-semibold text-amber-700">{{ __('Nisab threshold is not configured yet.') }}</p>
                                    <p class="mt-2 text-sm text-neutral-600">{{ __('Please check back later or contact the mosque for updated Nisab guidance.') }}</p>
                                </div>
                            </template>
                            <template x-if="hasNisab && !isAboveNisab">
                                <div>
                                    <p class="text-sm font-semibold text-neutral-700">{{ __('Zakat is not obligatory based on the values entered.') }}</p>
                                    <p class="mt-2 text-sm text-neutral-600">{{ __('Your net zakatable assets are currently below the configured Nisab threshold.') }}</p>
                                </div>
                            </template>
                            <template x-if="hasNisab && isAboveNisab">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">{{ __('Estimated Zakat Due') }}</p>
                                    <p class="mt-2 text-3xl font-bold text-emerald-900" x-text="formatCurrency(zakatDue)"></p>
                                    <p class="mt-2 text-sm text-neutral-600">{{ __('This estimate applies 2.5% to your net zakatable assets above Nisab.') }}</p>
                                </div>
                            </template>
                        </div>

                        <p class="mt-4 text-xs leading-relaxed text-neutral-600">{{ __('This calculator provides an estimate based on the values entered and should be used as general guidance.') }}</p>
                    </div>
                </div>

                @if($showFitr)
                    <div class="rounded-2xl border border-neutral-200 bg-neutral-50 p-6">
                        <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-neutral-900">{{ __('Zakat al-Fitr') }}</h3>
                                <p class="mt-1 text-sm text-neutral-500">{{ __('Calculate the total based on the number of family members and the configured amount per person.') }}</p>
                            </div>
                            <div class="text-sm text-neutral-600">
                                <span class="font-medium text-neutral-800">{{ __('Per Person') }}:</span>
                                <span x-text="hasFitrAmount ? formatCurrency(zakatFitrAmount) : @js(__('Not Set'))"></span>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-5 md:grid-cols-[minmax(0,220px)_1fr] md:items-end">
                            <label class="block">
                                <span class="mb-1 block text-sm font-medium text-neutral-700">{{ __('Family Members') }}</span>
                                <input x-model="familyMembers" type="number" min="0" step="1" class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                            </label>
                            <div class="rounded-2xl border border-emerald-200 bg-white p-5 shadow-sm">
                                <template x-if="!hasFitrAmount">
                                    <p class="text-sm text-neutral-600">{{ __('Zakat al-Fitr amount has not been configured yet.') }}</p>
                                </template>
                                <template x-if="hasFitrAmount">
                                    <div>
                                        <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">{{ __('Estimated Zakat al-Fitr') }}</p>
                                        <p class="mt-2 text-2xl font-bold text-emerald-900" x-text="formatCurrency(fitrTotal)"></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                @endif

                @if($donationUrl !== '')
                    <div class="flex justify-center">
                        <a href="{{ $donationUrl }}" class="inline-flex items-center justify-center rounded-full bg-emerald-700 px-8 py-3 text-sm font-semibold text-white transition-colors hover:bg-emerald-800">
                            {{ $ctaLabel }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
