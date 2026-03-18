<div x-data="{ submitted: false }" @contact-submitted.window="submitted = true; setTimeout(() => submitted = false, 6000)">

    <div x-show="submitted" x-cloak
         class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3">
        <svg class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="font-semibold text-emerald-800">{{ __('Message Sent!') }}</p>
            <p class="text-emerald-700 text-sm mt-0.5">{{ __('Thank you for reaching out. We will get back to you soon.') }}</p>
        </div>
    </div>

    <form wire:submit="submit">
        {{ $this->form }}

        <div class="mt-6">
            <button type="submit"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-6 py-3 text-white font-semibold hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition disabled:opacity-60 disabled:cursor-not-allowed">
                <span wire:loading.remove>{{ __('Send Message') }}</span>
                <span wire:loading class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    {{ __('Sending...') }}
                </span>
            </button>
        </div>
    </form>

    <x-filament-actions::modals />

</div>
