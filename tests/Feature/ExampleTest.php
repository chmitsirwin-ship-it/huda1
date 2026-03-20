<?php

test('the application returns a successful response', function () {
    seedDemoData();

    $response = $this->get('/');

    $response
        ->assertOk()
        ->assertSeeText('Welcome to Masjid Al-Huda');
});
