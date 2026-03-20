<?php

it('renders the gallery collections and seeded media metadata', function () {
    $this->get(route('gallery.index'))
        ->assertOk()
        ->assertSeeText('Photos and memories from our community')
        ->assertSeeText('Ramadan 2026')
        ->assertSeeText('Youth Programs')
        ->assertSee('Volunteers preparing tables for iftar', false);
});

it('filters gallery items by collection', function () {
    $this->get(route('gallery.index', ['collection' => 'Youth Programs']))
        ->assertOk()
        ->assertViewHas('collection', 'Youth Programs')
        ->assertViewHas('items', function ($items) {
            $collectionNames = collect($items->items())
                ->pluck('collection')
                ->unique()
                ->values()
                ->all();

            return $collectionNames === ['Youth Programs'] && count($items->items()) === 1;
        })
        ->assertSee('Students seated in a Quran study circle', false)
        ->assertDontSee('Volunteers preparing tables for iftar', false);
});

it('shows an empty state for an unknown gallery collection', function () {
    $this->get(route('gallery.index', ['collection' => 'Unknown Collection']))
        ->assertOk()
        ->assertSeeText('No photos yet');
});
