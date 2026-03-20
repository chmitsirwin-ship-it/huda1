<?php

use App\Models\Khutba;

it('only shows published khutbas on the archive page', function () {
    $publishedKhutbas = Khutba::published()->get();
    $draftKhutba = Khutba::query()->where('is_published', false)->firstOrFail();

    $response = $this->get(route('khutba.index'))
        ->assertOk();

    foreach ($publishedKhutbas as $khutba) {
        $response->assertSeeText($khutba->title);
    }

    $response->assertDontSeeText($draftKhutba->title);
});

it('filters khutbas by title search query', function () {
    $this->get(route('khutba.index', ['search' => 'Steadfastness']))
        ->assertOk()
        ->assertSeeText('Steadfastness in Worship')
        ->assertDontSeeText('Honouring the Neighbour');
});

it('filters khutbas by speaker search query', function () {
    Khutba::query()->create([
        'title' => [
            'en' => 'Serving With Excellence',
            'ar' => 'الخدمة بإتقان',
        ],
        'speaker' => [
            'en' => 'Ustadh Kareem Nabil',
            'ar' => 'الأستاذ كريم نبيل',
        ],
        'topic' => [
            'en' => 'Community leadership',
            'ar' => 'القيادة المجتمعية',
        ],
        'summary' => [
            'en' => 'A khutba focused on service and leadership.',
            'ar' => 'خطبة عن الخدمة والقيادة.',
        ],
        'date' => now()->subDays(5)->toDateString(),
        'is_published' => true,
    ]);

    $this->get(route('khutba.index', ['search' => 'Kareem Nabil']))
        ->assertOk()
        ->assertSeeText('Serving With Excellence')
        ->assertDontSeeText('Steadfastness in Worship');
});
