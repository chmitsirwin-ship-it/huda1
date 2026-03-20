<?php

use App\Filament\Admin\Resources\Staff\StaffResource;
use App\Models\Staff;

it('protects the staff resource pages', function () {
    $staff = Staff::query()->firstOrFail();

    assertGuestIsRedirectedFrom(adminPageUrls(StaffResource::class, $staff));
});

it('lets admins open staff resource pages and see seeded staff profiles', function () {
    $staff = Staff::query()->orderBy('sort_order')->firstOrFail();

    assertAdminCanOpen(adminPageUrls(StaffResource::class, $staff));

    $this->followingRedirects()
        ->get(StaffResource::getUrl('index'))
        ->assertOk()
        ->assertSeeText($staff->name)
        ->assertSeeText($staff->title);
});
