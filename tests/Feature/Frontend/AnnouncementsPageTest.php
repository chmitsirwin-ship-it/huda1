<?php

use App\Enums\AnnouncementType;
use App\Models\Announcement;
use App\Support\LocalizedDate;

it('only shows active announcements within their visibility window', function () {
    Announcement::query()->create([
        'title' => [
            'en' => 'Future notice',
            'ar' => 'إعلان مستقبلي',
        ],
        'content' => [
            'en' => 'This should stay hidden until its publish time.',
            'ar' => 'يجب أن يبقى هذا الإعلان مخفياً حتى وقت نشره.',
        ],
        'type' => AnnouncementType::General,
        'published_at' => now()->addDay(),
        'expires_at' => now()->addDays(2),
        'is_active' => true,
    ]);

    $this->get(route('announcements.index'))
        ->assertOk()
        ->assertSeeText('Ramadan food drive is now open')
        ->assertSeeText('Parking update for Friday prayer')
        ->assertDontSeeText('Library hall maintenance notice')
        ->assertDontSeeText('Archived volunteer orientation')
        ->assertDontSeeText('Future notice');
});

it('renders announcement publication dates with the localized format', function () {
    $announcement = Announcement::active()->firstOrFail();

    $this->get(route('announcements.index'))
        ->assertOk()
        ->assertSeeText($announcement->title)
        ->assertSeeText(LocalizedDate::date($announcement->published_at));
});
