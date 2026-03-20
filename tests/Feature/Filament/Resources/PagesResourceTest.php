<?php

use App\Filament\Admin\Resources\Pages\PageResource;
use App\Models\Page;

it('protects the page resource pages', function () {
    $page = Page::query()->where('slug', 'community-services')->firstOrFail();

    assertGuestIsRedirectedFrom(adminPageUrls(PageResource::class, $page));
});

it('lets admins open page resource pages and see seeded pages', function () {
    $page = Page::query()->where('slug', 'community-services')->firstOrFail();

    assertAdminCanOpen(adminPageUrls(PageResource::class, $page));

    $this->followingRedirects()
        ->get(PageResource::getUrl('index'))
        ->assertOk()
        ->assertSeeText('Community Services')
        ->assertSeeText('community-services');
});
