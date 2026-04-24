import Plyr from 'plyr';
import 'plyr/dist/plyr.css';

class QuranPlayer {
    constructor(root) {
        this.root = root;
        this.config = JSON.parse(root.dataset.quranPlayer || '{}');
        this.storageKey = `quran-player:${this.config.blockId}`;
        this.mobileMediaQuery = window.matchMedia('(max-width: 767px)');
        this.handlePlayerModeChange = () => this.updatePlayerMode();
        if (this.config.dir) {
            this.root.setAttribute('dir', this.config.dir);
        }
        if (this.config.locale) {
            this.root.setAttribute('lang', this.config.locale);
        }
        this.state = {
            bootstrap: null,
            queueItems: [],
            queueSignature: null,
            queueIndex: 0,
            shuffle: false,
            loop: false,
            openPanel: null,
            selectedReciterId: null,
            selectedRiwayahId: null,
            selectedSurahId: null,
            reciterQuery: '',
            surahQuery: '',
        };

        this.audioElement = root.querySelector('[data-audio-element]');
        this.contentEl = root.querySelector('[data-player-content]');
        this.currentReciterEl = root.querySelector('[data-current-reciter]');
        this.currentSurahEl = root.querySelector('[data-current-surah]');
        this.actionButtons = {
            previous: root.querySelector('[data-action="previous"]'),
            next: root.querySelector('[data-action="next"]'),
            shuffle: root.querySelector('[data-action="shuffle"]'),
            loop: root.querySelector('[data-action="loop"]'),
        };

        this.player = this.createPlayer();

        this.root.addEventListener('click', (event) => this.handleClick(event));
        this.root.addEventListener('change', (event) => this.handleChange(event));
        this.root.addEventListener('input', (event) => this.handleInput(event));
        this.root.addEventListener('keydown', (event) => this.handleKeydown(event));
        this.audioElement.addEventListener('ended', () => this.playNext());
        this.mobileMediaQuery.addEventListener('change', this.handlePlayerModeChange);

        this.restoreState();
        this.bootstrap();
    }

    async bootstrap() {
        try {
            this.state.bootstrap = await this.fetchJson(this.config.routes.bootstrap, {
                language: this.config.language,
            });

            this.resolveDefaults();
            this.syncQueueFromSelection();
            this.render();
        } catch (error) {
            if (this.currentReciterEl) {
                this.currentReciterEl.textContent = this.t('failed');
            }

            if (this.currentSurahEl) {
                this.currentSurahEl.textContent = '';
            }

            this.contentEl.innerHTML = `<div class="rounded-[1rem] border border-dashed border-white/10 bg-white/5 px-4 py-6 text-center text-sm text-slate-400">${this.t('failed')}</div>`;
        }
    }

    createPlayer() {
        return new Plyr(this.audioElement, this.getPlayerOptions());
    }

    getPlayerOptions() {
        return {
            controls: [ 'play','current-time', 'progress', 'duration', 'volume'],
            clickToPlay: true,
            resetOnEnd: false,
        };
    }

    updatePlayerMode() {
        const currentTime = this.player?.currentTime ?? 0;
        const wasPaused = this.player?.paused ?? true;
        const source = this.player?.source ?? null;

        this.player?.destroy();
        this.player = this.createPlayer();

        if (source?.sources?.length) {
            this.player.source = source;
            this.player.currentTime = currentTime;
            if (! wasPaused) {
                this.player.play().catch(() => {});
            }
        }
    }

    resolveDefaults() {
        const reciter = this.getSelectedReciter() ?? this.getReciters()[0] ?? null;

        if (reciter && ! this.state.selectedReciterId) {
            this.state.selectedReciterId = reciter.id;
        }

        const moshaf = this.getSelectedMoshaf();
        if (moshaf) {
            this.state.selectedRiwayahId ??= moshaf.moshaf_type;
            this.state.selectedSurahId ??= moshaf.surah_list[0] ?? null;
        }
    }

    syncQueueFromSelection({ surahId = null, autoplay = false } = {}) {
        const reciter = this.getSelectedReciter();
        const moshaf = this.getSelectedMoshaf();

        if (! reciter || ! moshaf?.surahs?.length) {
            this.state.queueItems = [];
            this.state.queueIndex = 0;
            this.state.queueSignature = null;
            return;
        }

        const queueItems = moshaf.surahs.map((surah) => ({
            title: surah.name,
            subtitle: `${reciter.name} • ${moshaf.name}`,
            url: surah.audio_url,
            surahId: surah.id,
            timingReadId: moshaf.timing_read_id ?? null,
        }));

        const nextSurahId = surahId ?? this.state.selectedSurahId ?? moshaf.surah_list?.[0] ?? queueItems[0]?.surahId ?? null;
        const nextIndex = Math.max(queueItems.findIndex((item) => item.surahId === nextSurahId), 0);
        const nextItem = queueItems[nextIndex] ?? null;
        const nextSignature = `${reciter.id}:${moshaf.moshaf_type}`;
        const shouldReloadSource = this.state.queueSignature !== nextSignature || ! this.player?.source?.sources?.length;

        this.state.queueItems = queueItems;
        this.state.queueIndex = nextIndex;
        this.state.queueSignature = nextSignature;
        this.state.selectedSurahId = nextItem?.surahId ?? null;

        if (! shouldReloadSource || ! nextItem) {
            return;
        }

        this.player.source = {
            type: 'audio',
            title: nextItem.title,
            sources: [{ src: nextItem.url, type: 'audio/mp3' }],
        };

        if (autoplay) {
            this.player.play().catch(() => {});
        }
    }

    render() {
        this.renderContent();
        this.syncActionButtons();
        this.persistState();
    }

    renderContent() {
        const reciter = this.getSelectedReciter();
        const moshaf = this.getSelectedMoshaf();
        const selectedSurah = this.getSelectedSurah();
        const surahs = this.getFilteredSurahs();
        const reciters = this.getFilteredReciters();

        if (this.currentReciterEl) {
            this.currentReciterEl.textContent = reciter?.name ?? this.t('chooseReciter');
        }

        if (this.currentSurahEl) {
            this.currentSurahEl.textContent = selectedSurah?.name ?? this.t('chooseSurah');
        }

        if (! moshaf || ! surahs.length) {
            this.contentEl.innerHTML = `<div class="rounded-[1rem] border border-dashed border-white/10 bg-white/5 px-4 py-6 text-center text-sm text-slate-400">${this.t('empty')}</div>`;
            return;
        }

        this.contentEl.innerHTML = `
            <div class="space-y-4">
                <div class="grid gap-3 lg:hidden">
                    <button
                        type="button"
                        data-action="open-panel"
                        data-panel="reciters"
                        class="rounded-[1.05rem] border border-white/10 bg-[#07171a]/70 p-3.5 text-start transition hover:border-emerald-300/30 hover:bg-white/10"
                    >
                        <div class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">${this.t('pickReciter')}</div>
                        <div class="mt-2 text-base font-semibold text-white">${this.escape(reciter?.name ?? this.t('chooseReciter'))}</div>
                        <div class="mt-1 text-sm text-slate-300">${this.escape(moshaf?.name ?? this.t('chooseRiwayah'))}</div>
                    </button>

                    <button
                        type="button"
                        data-action="open-panel"
                        data-panel="surahs"
                        class="rounded-[1.05rem] border border-white/10 bg-[#07171a]/70 p-3.5 text-start transition hover:border-amber-300/30 hover:bg-white/10"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">${this.t('surahLibrary')}</div>
                                <div class="mt-2 text-base font-semibold text-white">${this.escape(selectedSurah?.name ?? this.t('chooseSurah'))}</div>
                                <div class="mt-1 text-sm text-slate-300">${this.t('showingResults')}: ${surahs.length}</div>
                            </div>
                            <div class="inline-flex h-11 min-w-11 items-center justify-center rounded-full border border-white/10 bg-white/5 px-3 text-sm text-slate-200">
                                ${selectedSurah ? this.escape(String(selectedSurah.id)) : surahs.length}
                            </div>
                        </div>
                    </button>
                </div>

                <div class="hidden gap-4 xl:grid-cols-[minmax(0,0.9fr)_minmax(0,1.2fr)] lg:grid">
                    ${this.renderReciterPanel(reciters, { mobile: false })}
                    ${this.renderSurahPanel(surahs, selectedSurah, { mobile: false })}
                </div>

                <div class="${this.state.openPanel ? 'pointer-events-auto opacity-100' : 'pointer-events-none opacity-0'} fixed inset-0 z-50 bg-[#02090b]/75 p-3 backdrop-blur-sm transition lg:hidden" data-mobile-overlay>
                    <div
                        class="absolute inset-0"
                        data-action="close-panel"
                        aria-hidden="true"
                    ></div>
                    <div class="absolute inset-x-3 bottom-3 top-auto max-h-[88vh] overflow-hidden rounded-[1.35rem] border border-white/10 bg-[#051518] shadow-[0_30px_90px_rgba(0,0,0,0.45)]">
                        <div class="flex items-center justify-between border-b border-white/10 px-4 py-4">
                            <div>
                                <div class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">${this.state.openPanel === 'reciters' ? this.t('filters') : this.t('library')}</div>
                                <div class="mt-1 text-base font-semibold text-white">${this.state.openPanel === 'reciters' ? this.t('browseReciters') : this.t('browseSurahs')}</div>
                            </div>
                            <button
                                type="button"
                                data-action="close-panel"
                                class="inline-flex min-h-11 items-center justify-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-slate-100 transition hover:border-white/20 hover:bg-white/10"
                            >
                                ${this.t('close')}
                            </button>
                        </div>
                        <div class="max-h-[calc(88vh-5rem)] overflow-y-auto p-4">
                            ${this.state.openPanel === 'reciters'
                                ? this.renderReciterPanel(reciters, { mobile: true })
                                : this.state.openPanel === 'surahs'
                                    ? this.renderSurahPanel(surahs, selectedSurah, { mobile: true })
                                    : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.classList.toggle('overflow-hidden', Boolean(this.state.openPanel));
    }

    renderReciterPanel(reciters = this.getFilteredReciters(), { mobile = false } = {}) {
        return `
            <div class="h-full">
                <div class="flex h-full flex-col rounded-[1.15rem] border border-white/10 bg-[#07171a]/70 p-4 sm:rounded-[1.3rem] sm:p-5">
                    <div class="flex flex-col gap-3 border-b border-white/10 pb-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">${this.t('pickReciter')}</div>
                        <div class="w-full sm:max-w-xs">
                            ${this.renderSelect('riwayah', this.t('chooseRiwayah'), this.getAvailableMoshaf().map((item) => ({ value: item.moshaf_type, label: item.name })), this.state.selectedRiwayahId, true)}
                        </div>
                    </div>
                    <div class="mt-3 flex min-h-12 items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                        <input
                            type="text"
                            value="${this.escapeAttribute(this.state.reciterQuery)}"
                            data-control="reciter-search"
                            placeholder="${this.t('searchReciters')}"
                            class="w-full bg-transparent text-sm text-white outline-none placeholder:text-slate-400"
                        />
                        <div data-reciter-search-clear>
                            ${this.renderReciterSearchClear()}
                        </div>
                    </div>
                    <div class="mt-3 min-h-0 ${mobile ? '' : 'max-h-[36rem]'} flex-1 overflow-y-auto pe-1" data-reciter-results>
                        ${this.renderReciterResults(reciters)}
                    </div>
                </div>
            </div>
        `;
    }

    renderSurahPanel(surahs = this.getFilteredSurahs(), selectedSurah = this.getSelectedSurah(), { mobile = false } = {}) {
        return `
            <div class="flex h-full flex-col rounded-[1.15rem] border border-white/10 bg-[#07171a]/70 p-4 sm:rounded-[1.3rem] sm:p-5">
                <div class="flex flex-col gap-3 border-b border-white/10 pb-3">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">${this.t('surahLibrary')}</div>
                            <div class="mt-1 text-sm text-slate-300" data-surah-results-count>${this.t('showingResults')}: ${surahs.length}</div>
                        </div>
                        ${selectedSurah ? `
                            <button
                                type="button"
                                data-play-surah="${selectedSurah.id}"
                                class="inline-flex min-h-11 items-center justify-center rounded-full border border-emerald-300/30 bg-emerald-300/15 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-emerald-300/25"
                            >
                                ${this.t('playThis')}
                            </button>
                        ` : ''}
                    </div>
                    <div class="flex min-h-12 items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                        <input
                            type="text"
                            value="${this.escapeAttribute(this.state.surahQuery)}"
                            data-control="surah-search"
                            placeholder="${this.t('searchSurahs')}"
                            class="w-full bg-transparent text-sm text-white outline-none placeholder:text-slate-400"
                        />
                        <div data-surah-search-clear>
                            ${this.renderSurahSearchClear()}
                        </div>
                    </div>
                </div>
                <div class="mt-4 min-h-0 ${mobile ? '' : 'max-h-[36rem]'} flex-1 overflow-y-auto pe-2" data-surah-results>
                    ${this.renderSurahResults(surahs)}
                </div>
            </div>
        `;
    }

    handleClick(event) {
        const button = event.target.closest('button');
        if (! button) {
            return;
        }

        const { action, playSurah, selectReciter, panel } = button.dataset;

        if (action === 'previous') {
            this.playPrevious();
            return;
        }

        if (action === 'next') {
            this.playNext();
            return;
        }

        if (action === 'clear-search') {
            this.state.surahQuery = '';
            this.updateSurahSearchUI();
            return;
        }

        if (action === 'clear-reciter-search') {
            this.state.reciterQuery = '';
            this.render();
            return;
        }

        if (action === 'open-panel') {
            this.state.openPanel = panel ?? null;
            this.render();
            return;
        }

        if (action === 'close-panel') {
            this.state.openPanel = null;
            this.render();
            return;
        }

        if (selectReciter) {
            this.state.selectedReciterId = Number(selectReciter) || null;
            this.state.selectedRiwayahId = null;
            this.state.selectedSurahId = null;
            this.state.openPanel = null;
            this.resolveDefaults();
            this.syncQueueFromSelection();
            this.render();
            return;
        }

        if (playSurah) {
            this.playRecitationQueue(Number(playSurah));
            this.state.openPanel = null;
        }
    }

    handleChange(event) {
        const target = event.target;
        if (!(target instanceof HTMLSelectElement)) {
            return;
        }

        switch (target.dataset.control) {
            case 'reciter':
                this.state.selectedReciterId = Number(target.value) || null;
                this.state.selectedRiwayahId = null;
                this.state.selectedSurahId = null;
                this.state.surahQuery = '';
                this.resolveDefaults();
                this.syncQueueFromSelection();
                this.render();
                break;
            case 'riwayah':
                this.state.selectedRiwayahId = Number(target.value) || null;
                this.state.selectedSurahId = this.getSelectedMoshaf()?.surah_list?.[0] ?? null;
                this.state.surahQuery = '';
                this.syncQueueFromSelection();
                this.render();
                break;
            default:
                break;
        }
    }

    handleKeydown(event) {
        if (event.key === 'Escape' && this.state.openPanel) {
            this.state.openPanel = null;
            this.render();
        }
    }

    handleInput(event) {
        const target = event.target;
        if (!(target instanceof HTMLInputElement)) {
            return;
        }

        if (target.dataset.control === 'reciter-search') {
            this.state.reciterQuery = target.value;
            this.updateReciterSearchUI();
            return;
        }

        if (target.dataset.control === 'surah-search') {
            this.state.surahQuery = target.value;
            this.updateSurahSearchUI();
        }
    }

    playRecitationQueue(surahId) {
        this.syncQueueFromSelection({ surahId });
        this.loadQueueItem();
        this.renderContent();
    }

    playPrevious() {
        if (! this.state.queueItems.length) {
            return;
        }

        if (this.state.shuffle) {
            this.state.queueIndex = Math.floor(Math.random() * this.state.queueItems.length);
        } else if (this.state.queueIndex > 0) {
            this.state.queueIndex -= 1;
        } else if (this.state.loop) {
            this.state.queueIndex = this.state.queueItems.length - 1;
        }

        this.loadQueueItem();
    }

    playNext() {
        if (! this.state.queueItems.length) {
            return;
        }

        if (this.state.shuffle) {
            this.state.queueIndex = Math.floor(Math.random() * this.state.queueItems.length);
        } else if (this.state.queueIndex < this.state.queueItems.length - 1) {
            this.state.queueIndex += 1;
        } else if (this.state.loop) {
            this.state.queueIndex = 0;
        } else {
            return;
        }

        this.loadQueueItem();
    }

    async loadQueueItem() {
        const item = this.state.queueItems[this.state.queueIndex];
        if (! item) {
            return;
        }

        this.state.selectedSurahId = item.surahId;
        if (this.currentSurahEl) {
            this.currentSurahEl.textContent = item.title;
        }
        this.player.source = {
            type: 'audio',
            title: item.title,
            sources: [{ src: item.url, type: 'audio/mp3' }],
        };

        await this.player.play().catch(() => {});
        this.renderContent();
    }

    getReciters() {
        return this.state.bootstrap?.data?.reciters ?? [];
    }

    getSelectedReciter() {
        return this.getReciters().find((item) => item.id === this.state.selectedReciterId) ?? null;
    }

    getFilteredReciters() {
        const query = this.state.reciterQuery.trim().toLowerCase();

        if (! query) {
            return this.getReciters();
        }

        return this.getReciters().filter((item) => item.name.toLowerCase().includes(query));
    }

    getAvailableMoshaf() {
        return this.getSelectedReciter()?.moshaf ?? [];
    }

    getSelectedMoshaf() {
        const items = this.getAvailableMoshaf();
        return items.find((item) => item.moshaf_type === this.state.selectedRiwayahId) ?? items[0] ?? null;
    }

    getSelectedSurah() {
        return this.getSelectedMoshaf()?.surahs.find((item) => item.id === this.state.selectedSurahId) ?? null;
    }

    getFilteredSurahs() {
        const surahs = this.getSelectedMoshaf()?.surahs ?? [];
        const query = this.state.surahQuery.trim().toLowerCase();

        if (! query) {
            return surahs;
        }

        return surahs.filter((surah) =>
            String(surah.id).includes(query) || surah.name.toLowerCase().includes(query)
        );
    }

    renderSelect(control, placeholder, options, value, compact = false) {
        return `
            <label class="flex flex-col gap-2">
                ${compact ? '' : `<span class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">${placeholder}</span>`}
                <select class="min-h-11 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-emerald-300/50" data-control="${control}" aria-label="${this.escapeAttribute(placeholder)}">
                    <option value="">${placeholder}</option>
                    ${options.map((option) => `
                        <option value="${this.escapeAttribute(option.value)}" ${String(option.value) === String(value ?? '') ? 'selected' : ''}>
                            ${this.escape(option.label)}
                        </option>
                    `).join('')}
                </select>
            </label>
        `;
    }

    renderReciterSearchClear() {
        return this.state.reciterQuery
            ? `<button type="button" data-action="clear-reciter-search" class="shrink-0 rounded-full border border-white/10 px-3 py-1 text-xs text-slate-300 transition hover:border-emerald-300/40 hover:text-white">${this.t('clear')}</button>`
            : '';
    }

    renderSurahSearchClear() {
        return this.state.surahQuery
            ? `<button type="button" data-action="clear-search" class="shrink-0 rounded-full border border-white/10 px-3 py-1 text-xs text-slate-300 transition hover:border-emerald-300/40 hover:text-white">${this.t('clear')}</button>`
            : '';
    }

    renderReciterResults(reciters = this.getFilteredReciters()) {
        if (! reciters.length) {
            return `<div class="rounded-xl border border-dashed border-white/10 px-3 py-4 text-sm text-slate-400">${this.t('empty')}</div>`;
        }

        return `
            <div class="grid gap-2 sm:grid-cols-2 xl:grid-cols-1">
                ${reciters.map((item) => `
                    <button
                        type="button"
                        data-select-reciter="${item.id}"
                        class="min-h-12 rounded-[1rem] border px-4 py-3 text-start text-sm transition ${
                            item.id === this.state.selectedReciterId
                                ? 'border-emerald-300/40 bg-emerald-300/15 text-white'
                                : 'border-white/10 bg-white/5 text-slate-200 hover:border-emerald-300/30 hover:bg-white/10'
                        }"
                    >
                        <span class="block font-medium">${this.escape(item.name)}</span>
                        <span class="mt-1 block text-xs text-slate-400">${this.escape(item.moshaf?.[0]?.name ?? '')}</span>
                    </button>
                `).join('')}
            </div>
        `;
    }

    renderSurahResults(surahs = this.getFilteredSurahs()) {
        if (! surahs.length) {
            return `<div class="rounded-xl border border-dashed border-white/10 px-3 py-4 text-sm text-slate-400">${this.t('empty')}</div>`;
        }

        return `
            <div class="grid gap-3 md:grid-cols-2">
                ${surahs.map((surah) => `
                    <button
                        type="button"
                        class="min-h-16 rounded-[1rem] border px-4 py-3 text-start transition ${
                            surah.id === this.state.selectedSurahId
                                ? 'border-amber-300/40 bg-amber-300/10'
                                : 'border-white/10 bg-white/5 hover:border-emerald-300/40 hover:bg-white/10'
                        }"
                        data-play-surah="${surah.id}"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm font-medium text-white">${this.escape(surah.name)}</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/10 bg-white/5 text-xs text-slate-300">${surah.id}</span>
                        </div>
                    </button>
                `).join('')}
            </div>
        `;
    }

    updateReciterSearchUI() {
        this.contentEl.querySelectorAll('[data-reciter-results]').forEach((element) => {
            element.innerHTML = this.renderReciterResults();
        });

        this.contentEl.querySelectorAll('[data-reciter-search-clear]').forEach((element) => {
            element.innerHTML = this.renderReciterSearchClear();
        });
    }

    updateSurahSearchUI() {
        const surahs = this.getFilteredSurahs();
        this.contentEl.querySelectorAll('[data-surah-results]').forEach((element) => {
            element.innerHTML = this.renderSurahResults(surahs);
        });

        this.contentEl.querySelectorAll('[data-surah-search-clear]').forEach((element) => {
            element.innerHTML = this.renderSurahSearchClear();
        });

        this.contentEl.querySelectorAll('[data-surah-results-count]').forEach((element) => {
            element.textContent = `${this.t('showingResults')}: ${surahs.length}`;
        });
    }

    syncActionButtons() {
        const hasQueue = this.state.queueItems.length > 0;

        this.toggleActionState(this.actionButtons.previous, {
            active: false,
            disabled: ! hasQueue,
        });

        this.toggleActionState(this.actionButtons.next, {
            active: false,
            disabled: ! hasQueue,
        });

        this.toggleActionState(this.actionButtons.shuffle, {
            active: this.state.shuffle,
            disabled: false,
        });

        this.toggleActionState(this.actionButtons.loop, {
            active: this.state.loop,
            disabled: false,
        });
    }

    toggleActionState(button, { active = false, disabled = false } = {}) {
        if (! button) {
            return;
        }

        button.disabled = disabled;
        button.classList.toggle('border-emerald-300/40', active);
        button.classList.toggle('bg-emerald-400/15', active);
        button.classList.toggle('text-white', active);
    }

    persistState() {
        if (! this.config.features?.rememberState) {
            return;
        }

        try {
            localStorage.setItem(this.storageKey, JSON.stringify({
                selectedReciterId: this.state.selectedReciterId,
                selectedRiwayahId: this.state.selectedRiwayahId,
                selectedSurahId: this.state.selectedSurahId,
                loop: this.state.loop,
                shuffle: this.state.shuffle,
                reciterQuery: this.state.reciterQuery,
                surahQuery: this.state.surahQuery,
            }));
        } catch {
            // Ignore storage failures.
        }
    }

    restoreState() {
        if (! this.config.features?.rememberState) {
            return;
        }

        try {
            const raw = localStorage.getItem(this.storageKey);
            if (! raw) {
                return;
            }

            Object.assign(this.state, JSON.parse(raw));
        } catch {
            // Ignore invalid local state.
        }
    }

    destroy() {
        document.body.classList.remove('overflow-hidden');
        this.mobileMediaQuery.removeEventListener('change', this.handlePlayerModeChange);
    }

    async fetchJson(url, params = {}) {
        const response = await fetch(this.appendParams(url, params), {
            headers: { Accept: 'application/json' },
        });

        if (! response.ok) {
            throw new Error(`Request failed: ${response.status}`);
        }

        return response.json();
    }

    async fetchText(url, params = {}) {
        const response = await fetch(this.appendParams(url, params));

        if (! response.ok) {
            throw new Error(`Request failed: ${response.status}`);
        }

        return response.text();
    }

    appendParams(url, params = {}) {
        const next = new URL(url, window.location.origin);

        Object.entries(params).forEach(([key, value]) => {
            if (value !== null && value !== undefined && value !== '') {
                next.searchParams.set(key, value);
            }
        });

        return next.toString();
    }

    t(key) {
        return this.config.labels?.[key] ?? key;
    }

    escape(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    escapeAttribute(value) {
        return this.escape(value);
    }
}

const quranPlayerInstances = new WeakMap();

function initQuranPlayers(scope = document) {
    const roots = scope instanceof Element && scope.matches('[data-quran-player]')
        ? [scope]
        : Array.from(scope.querySelectorAll?.('[data-quran-player]') ?? []);

    roots.forEach((root) => {
        if (quranPlayerInstances.has(root)) {
            return;
        }

        quranPlayerInstances.set(root, new QuranPlayer(root));
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initQuranPlayers();

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node instanceof Element) {
                    initQuranPlayers(node);
                }
            });
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true,
    });
});

document.addEventListener('livewire:initialized', () => initQuranPlayers());
document.addEventListener('livewire:navigated', () => initQuranPlayers());
