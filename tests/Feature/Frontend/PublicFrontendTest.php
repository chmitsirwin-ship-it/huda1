<?php

use App\Livewire\ContactForm;
use App\Models\ContactSubmission;
use App\Models\Event;
use App\Models\Khutba;
use Carbon\Carbon;
use Livewire\Livewire;

beforeEach(function () {
    seedDemoData();
});

it('renders the main public pages with seeded english content', function () {
    $publishedEvent = Event::query()->where('status', \App\Enums\EventStatus::Published)->orderBy('starts_at')->firstOrFail();

    $pages = [
        [route('home'), 'Welcome to Masjid Al-Huda'],
        [route('page.show', 'community-services'), 'Serving people with dignity'],
        [route('prayer-times.index'), "Today's Prayer Times"],
        [route('events.index'), 'Community Iftar Night'],
        [route('events.show', $publishedEvent), 'Community Iftar Night'],
        [route('announcements.index'), 'Ramadan food drive is now open'],
        [route('gallery.index'), 'Photos and memories from our community'],
        [route('islamic-library.index'), 'Indeed, with hardship comes ease.'],
        [route('khutba.index'), 'Steadfastness in Worship'],
        [route('staff.index'), 'Shaykh Ahmad Hassan'],
        [route('contact.index'), 'Send Us a Message'],
    ];

    foreach ($pages as [$url, $text]) {
        $this->get($url)
            ->assertOk()
            ->assertSeeText($text);
    }
});

it('switches the locale to arabic and renders arabic content', function () {
    $this->get(route('home', ['lang' => 'ar']))
        ->assertOk()
        ->assertSessionHas('locale', 'ar');

    $this->withSession(['locale' => 'ar'])
        ->get(route('home'))
        ->assertOk()
        ->assertSee('lang="ar"', false)
        ->assertSee('dir="rtl"', false)
        ->assertSeeText('مرحباً بكم في مسجد الهدى');

    $this->withSession(['locale' => 'ar'])
        ->get(route('events.index'))
        ->assertOk()
        ->assertSeeText('ليلة الإفطار المجتمعي');
});

it('only shows active announcements on the public page', function () {
    $this->get(route('announcements.index'))
        ->assertOk()
        ->assertSeeText('Ramadan food drive is now open')
        ->assertSeeText('Parking update for Friday prayer')
        ->assertDontSeeText('Library hall maintenance notice')
        ->assertDontSeeText('Archived volunteer orientation');
});

it('shows only published events to the public', function () {
    $publishedEvent = Event::query()->where('status', \App\Enums\EventStatus::Published)->firstOrFail();
    $draftEvent = Event::query()->where('status', \App\Enums\EventStatus::Draft)->firstOrFail();

    $this->get(route('events.index'))
        ->assertOk()
        ->assertSeeText($publishedEvent->title)
        ->assertDontSeeText($draftEvent->title);

    $this->get(route('events.show', $publishedEvent))
        ->assertOk()
        ->assertSeeText($publishedEvent->title);

    $this->get(route('events.show', $draftEvent))
        ->assertNotFound();
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
        });
});

it('filters khutbas by search query', function () {
    $this->get(route('khutba.index', ['search' => 'Steadfastness']))
        ->assertOk()
        ->assertSeeText('Steadfastness in Worship')
        ->assertDontSeeText('Honouring the Neighbour');
});

it('stores contact submissions from the livewire contact form', function () {
    Livewire::test(ContactForm::class)
        ->set('data.name', 'Test Visitor')
        ->set('data.email', 'test.visitor@example.com')
        ->set('data.phone', '+20 100 999 0000')
        ->set('data.subject', 'Volunteer request')
        ->set('data.message', 'I would like to volunteer during the upcoming community iftar event.')
        ->call('submit')
        ->assertDispatched('contact-submitted');

    $this->assertDatabaseHas('contact_submissions', [
        'email' => 'test.visitor@example.com',
        'subject' => 'Volunteer request',
    ]);
});

it('loads today prayer times from the seeded current month data', function () {
    $today = Carbon::today();

    $this->get(route('prayer-times.index'))
        ->assertOk()
        ->assertSeeText("Today's Prayer Times")
        ->assertSeeText($today->translatedFormat('l, F j, Y'));
});
