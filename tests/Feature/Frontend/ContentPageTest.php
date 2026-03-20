<?php

use App\Models\Page;

it('renders the seeded community services page', function () {
    $this->get(route('page.show', 'community-services'))
        ->assertOk()
        ->assertSeeText('Serving people with dignity')
        ->assertSeeText('Practical support for families')
        ->assertSeeText('Request support or ask a question');
});

it('renders translated content pages from the active locale', function () {
    $this->withSession(['locale' => 'ar'])
        ->get(route('page.show', 'community-services'))
        ->assertOk()
        ->assertSeeText('نخدم الناس بكرامة')
        ->assertSeeText('اطلب المساعدة أو اطرح سؤالك');
});

it('returns 404 for unpublished content pages', function () {
    Page::query()->create([
        'title' => [
            'en' => 'Hidden Page',
            'ar' => 'صفحة مخفية',
        ],
        'slug' => 'hidden-page',
        'is_published' => false,
        'show_in_nav' => false,
    ]);

    $this->get(route('page.show', 'hidden-page'))
        ->assertNotFound();
});
