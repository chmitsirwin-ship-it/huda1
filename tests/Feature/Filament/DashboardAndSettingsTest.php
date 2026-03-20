<?php

use App\Filament\Admin\Pages\MosqueSettings;

it('redirects guests away from the dashboard and settings pages', function () {
    assertGuestIsRedirectedFrom([
        '/admin',
        MosqueSettings::getUrl(),
    ]);
});

it('lets admins open the dashboard and mosque settings page', function () {
    assertAdminCanOpen([
        '/admin',
        MosqueSettings::getUrl(),
    ]);

    $this->followingRedirects()
        ->get(MosqueSettings::getUrl())
        ->assertOk()
        ->assertSeeText('Mosque Settings')
        ->assertSeeText('General')
        ->assertSeeText('Prayer')
        ->assertSeeText('SEO');
});
