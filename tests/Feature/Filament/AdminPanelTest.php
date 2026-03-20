<?php

use App\Enums\ContactSubmissionStatus;
use App\Filament\Admin\Pages\MosqueSettings;
use App\Filament\Admin\Resources\Announcements\AnnouncementResource;
use App\Filament\Admin\Resources\ContactSubmissions\ContactSubmissionResource;
use App\Filament\Admin\Resources\Events\EventResource;
use App\Filament\Admin\Resources\Hadiths\HadithResource;
use App\Filament\Admin\Resources\Khutbas\KhutbaResource;
use App\Filament\Admin\Resources\Languages\LanguageResource;
use App\Filament\Admin\Resources\MediaItems\MediaItemResource;
use App\Filament\Admin\Resources\Pages\PageResource;
use App\Filament\Admin\Resources\PrayerTimes\PrayerTimeResource;
use App\Filament\Admin\Resources\QuranVerses\QuranVerseResource;
use App\Filament\Admin\Resources\Sliders\SliderResource;
use App\Filament\Admin\Resources\Staff\StaffResource;
use App\Models\Announcement;
use App\Models\ContactSubmission;
use App\Models\Event;
use App\Models\Hadith;
use App\Models\Khutba;
use App\Models\Language;
use App\Models\MediaItem;
use App\Models\Page;
use App\Models\PrayerTime;
use App\Models\QuranVerse;
use App\Models\Slider;
use App\Models\Staff;

beforeEach(function () {
    seedDemoData();
});

it('redirects guests away from the admin panel', function () {
    $urls = [
        '/admin',
        MosqueSettings::getUrl(),
        AnnouncementResource::getUrl('index'),
        EventResource::getUrl('create'),
        ContactSubmissionResource::getUrl('index'),
    ];

    foreach ($urls as $url) {
        $this->get($url)->assertRedirect('/admin/login');
    }
});

it('lets the seeded admin open the dashboard, settings, and all resource pages', function () {
    loginAsAdmin();

    $urls = [
        '/admin',
        MosqueSettings::getUrl(),
        LanguageResource::getUrl('index'),
        LanguageResource::getUrl('create'),
        LanguageResource::getUrl('edit', ['record' => Language::query()->where('code', 'ar')->firstOrFail()]),
        PageResource::getUrl('index'),
        PageResource::getUrl('create'),
        PageResource::getUrl('edit', ['record' => Page::query()->where('slug', 'community-services')->firstOrFail()]),
        AnnouncementResource::getUrl('index'),
        AnnouncementResource::getUrl('create'),
        AnnouncementResource::getUrl('edit', ['record' => Announcement::query()->firstOrFail()]),
        EventResource::getUrl('index'),
        EventResource::getUrl('create'),
        EventResource::getUrl('edit', ['record' => Event::query()->firstOrFail()]),
        MediaItemResource::getUrl('index'),
        MediaItemResource::getUrl('create'),
        MediaItemResource::getUrl('edit', ['record' => MediaItem::query()->firstOrFail()]),
        SliderResource::getUrl('index'),
        SliderResource::getUrl('create'),
        SliderResource::getUrl('edit', ['record' => Slider::query()->firstOrFail()]),
        StaffResource::getUrl('index'),
        StaffResource::getUrl('create'),
        StaffResource::getUrl('edit', ['record' => Staff::query()->firstOrFail()]),
        PrayerTimeResource::getUrl('index'),
        PrayerTimeResource::getUrl('create'),
        PrayerTimeResource::getUrl('edit', ['record' => PrayerTime::query()->firstOrFail()]),
        QuranVerseResource::getUrl('index'),
        QuranVerseResource::getUrl('create'),
        QuranVerseResource::getUrl('edit', ['record' => QuranVerse::query()->firstOrFail()]),
        HadithResource::getUrl('index'),
        HadithResource::getUrl('create'),
        HadithResource::getUrl('edit', ['record' => Hadith::query()->firstOrFail()]),
        KhutbaResource::getUrl('index'),
        KhutbaResource::getUrl('create'),
        KhutbaResource::getUrl('edit', ['record' => Khutba::query()->firstOrFail()]),
        ContactSubmissionResource::getUrl('index'),
        ContactSubmissionResource::getUrl('edit', ['record' => ContactSubmission::query()->firstOrFail()]),
    ];

    foreach ($urls as $url) {
        $this->followingRedirects()->get($url)->assertOk();
    }
});


it('marks a new contact submission as read when an admin opens it', function () {
    loginAsAdmin();

    $submission = ContactSubmission::query()
        ->where('status', ContactSubmissionStatus::New)
        ->firstOrFail();

    $this->followingRedirects()
        ->get(ContactSubmissionResource::getUrl('edit', ['record' => $submission]))
        ->assertOk();

    $submission->refresh();

    expect($submission->status)->toBe(ContactSubmissionStatus::Read)
        ->and($submission->read_at)->not()->toBeNull();
});
