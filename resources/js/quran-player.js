import Plyr from 'plyr';
import 'plyr/dist/plyr.css';

class QuranPlayer {
    constructor(root) {
        this.root = root;
        this.config = JSON.parse(root.dataset.quranPlayer || '{}');
        this.storageKey = `quran-player:${this.config.blockId}`;
        if (this.config.dir) {
            this.root.setAttribute('dir', this.config.dir);
        }
        if (this.config.locale) {
            this.root.setAttribute('lang', this.config.locale);
        }
        this.state = {
            bootstrap: null,
            queueItems: [],
            queueIndex: 0,
            shuffle: false,
            loop: false,
            selectedReciterId: null,
            selectedRiwayahId: null,
            selectedSurahId: null,
            reciterQuery: '',
            surahQuery: '',
        };

        this.audioElement = root.querySelector('[data-audio-element]');
        this.statusEl = root.querySelector('[data-player-status]');
        this.toolbarEl = root.querySelector('[data-player-toolbar]');
        this.contentEl = root.querySelector('[data-player-content]');
        this.activeTypeEl = root.querySelector('[data-active-type]');
        this.activeTitleEl = root.querySelector('[data-active-title]');
        this.activeSubtitleEl = root.querySelector('[data-active-subtitle]');
        this.actionButtons = {
            previous: root.querySelector('[data-action="previous"]'),
            next: root.querySelector('[data-action="next"]'),
            shuffle: root.querySelector('[data-action="shuffle"]'),
            loop: root.querySelector('[data-action="loop"]'),
        };

        this.player = new Plyr(this.audioElement, {
            controls: ['play-large', 'rewind', 'play', 'fast-forward', 'progress', 'current-time', 'duration', 'mute', 'volume', 'settings'],
            settings: ['speed'],
            autoplay: Boolean(this.config.features?.autoplay),
        });

        this.root.addEventListener('click', (event) => this.handleClick(event));
        this.root.addEventListener('change', (event) => this.handleChange(event));
        this.root.addEventListener('input', (event) => this.handleInput(event));
        this.audioElement.addEventListener('ended', () => this.playNext());

        this.restoreState();
        this.bootstrap();
    }

    async bootstrap() {
        this.setStatus(this.t('loading'));

        try {
            this.state.bootstrap = await this.fetchJson(this.config.routes.bootstrap, {
                language: this.config.language,
            });

            this.resolveDefaults();
            this.render();
            this.setStatus(this.t('ready'));
        } catch (error) {
            this.setStatus(this.t('failed'), true);
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

    render() {
        this.renderToolbar();
        this.renderContent();
        this.syncActionButtons();
        this.persistState();
    }

    renderToolbar() {
        this.toolbarEl.innerHTML = '';
    }

    renderContent() {
        const reciter = this.getSelectedReciter();
        const moshaf = this.getSelectedMoshaf();
        const selectedSurah = this.getSelectedSurah();
        const surahs = this.getFilteredSurahs();
        const reciters = this.getFilteredReciters();

        this.activeTypeEl.textContent = this.t('recitations');
        this.activeTitleEl.textContent = reciter?.name ?? this.t('chooseReciter');
        this.activeSubtitleEl.textContent = moshaf?.name ?? '';

        if (! moshaf || ! surahs.length) {
            this.contentEl.innerHTML = `<div class="rounded-[1rem] border border-dashed border-white/10 bg-white/5 px-4 py-6 text-center text-sm text-slate-400">${this.t('empty')}</div>`;
            return;
        }

        this.contentEl.innerHTML = `
            <div class="grid gap-4 xl:grid-cols-[minmax(0,0.9fr)_minmax(0,1.2fr)]">
                <div class="h-full">
                    <div class="flex h-full flex-col rounded-[1.3rem] border border-white/10 bg-[#07171a]/70 p-4">
                        <div class="flex flex-col gap-3 border-b border-white/10 pb-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="text-xs font-semibold uppercase tracking-[0.28em] text-slate-400">${this.t('pickReciter')}</div>
                            <div class="w-full sm:max-w-xs">
                                ${this.renderSelect('riwayah', this.t('chooseRiwayah'), this.getAvailableMoshaf().map((item) => ({ value: item.moshaf_type, label: item.name })), this.state.selectedRiwayahId, true)}
                            </div>
                        </div>
                        <div class="mt-3 flex min-h-11 items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
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
                        <div class="mt-3 min-h-0 max-h-[36rem] flex-1 overflow-y-auto pe-1" data-reciter-results>
                            ${this.renderReciterResults(reciters)}
                        </div>
                    </div>
                </div>

                <div class="flex h-full flex-col rounded-[1.3rem] border border-white/10 bg-[#07171a]/70 p-4">
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
                        <div class="flex min-h-11 items-center gap-2 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
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
                    <div class="mt-4 min-h-0 max-h-[36rem] flex-1 overflow-y-auto pe-2" data-surah-results>
                        ${this.renderSurahResults(surahs)}
                    </div>
                </div>
            </div>
        `;
    }

    handleClick(event) {
        const button = event.target.closest('button');
        if (! button) {
            return;
        }

        const { action, playSurah, selectReciter } = button.dataset;

        if (action === 'previous') {
            this.playPrevious();
            return;
        }

        if (action === 'next') {
            this.playNext();
            return;
        }

        if (action === 'shuffle') {
            this.state.shuffle = ! this.state.shuffle;
            this.syncActionButtons();
            this.persistState();
            return;
        }

        if (action === 'loop') {
            this.state.loop = ! this.state.loop;
            this.syncActionButtons();
            this.persistState();
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

        if (selectReciter) {
            this.state.selectedReciterId = Number(selectReciter) || null;
            this.state.selectedRiwayahId = null;
            this.state.selectedSurahId = null;
            this.resolveDefaults();
            this.render();
            return;
        }

        if (playSurah) {
            this.playRecitationQueue(Number(playSurah));
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
                this.render();
                break;
            case 'riwayah':
                this.state.selectedRiwayahId = Number(target.value) || null;
                this.state.selectedSurahId = this.getSelectedMoshaf()?.surah_list?.[0] ?? null;
                this.state.surahQuery = '';
                this.render();
                break;
            default:
                break;
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
        const reciter = this.getSelectedReciter();
        const moshaf = this.getSelectedMoshaf();

        if (! reciter || ! moshaf) {
            return;
        }

        this.state.selectedSurahId = surahId;
        this.state.queueItems = moshaf.surahs.map((surah) => ({
            title: surah.name,
            subtitle: `${reciter.name} • ${moshaf.name}`,
            url: surah.audio_url,
            surahId: surah.id,
            timingReadId: moshaf.timing_read_id ?? null,
        }));
        this.state.queueIndex = Math.max(this.state.queueItems.findIndex((item) => item.surahId === surahId), 0);

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
        this.activeTitleEl.textContent = item.title;
        this.activeSubtitleEl.textContent = item.subtitle ?? '';
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
        const resultsEl = this.contentEl.querySelector('[data-reciter-results]');
        const clearEl = this.contentEl.querySelector('[data-reciter-search-clear]');

        if (resultsEl) {
            resultsEl.innerHTML = this.renderReciterResults();
        }

        if (clearEl) {
            clearEl.innerHTML = this.renderReciterSearchClear();
        }
    }

    updateSurahSearchUI() {
        const surahs = this.getFilteredSurahs();
        const resultsEl = this.contentEl.querySelector('[data-surah-results]');
        const clearEl = this.contentEl.querySelector('[data-surah-search-clear]');
        const countEl = this.contentEl.querySelector('[data-surah-results-count]');

        if (resultsEl) {
            resultsEl.innerHTML = this.renderSurahResults(surahs);
        }

        if (clearEl) {
            clearEl.innerHTML = this.renderSurahSearchClear();
        }

        if (countEl) {
            countEl.textContent = `${this.t('showingResults')}: ${surahs.length}`;
        }
    }

    setStatus(message, isError = false) {
        this.statusEl.textContent = message;
        this.statusEl.classList.toggle('border-rose-300/20', isError);
        this.statusEl.classList.toggle('bg-rose-300/10', isError);
        this.statusEl.classList.toggle('text-rose-100', isError);
        this.statusEl.classList.toggle('border-emerald-300/20', ! isError && Boolean(message));
        this.statusEl.classList.toggle('bg-emerald-300/10', ! isError && Boolean(message));
        this.statusEl.classList.toggle('text-emerald-100', ! isError && Boolean(message));
        this.statusEl.classList.toggle('hidden', ! message);
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
