<?php

it('renders the seeded home page blocks and featured content', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeText('Welcome to Masjid Al-Huda')
        ->assertSeeText('Explore the full content library')
        ->assertSeeText('Ramadan food drive is now open')
        ->assertSeeText('Indeed, with hardship comes ease.')
        ->assertSeeText('Shaykh Ahmad Hassan');
});

it('renders the home page in arabic when the locale is switched', function () {
    $this->get(route('home', ['lang' => 'ar']))
        ->assertOk()
        ->assertSessionHas('locale', 'ar');

    $this->withSession(['locale' => 'ar'])
        ->get(route('home'))
        ->assertOk()
        ->assertSee('lang="ar"', false)
        ->assertSee('dir="rtl"', false)
        ->assertSeeText('مرحباً بكم في مسجد الهدى')
        ->assertSeeText('استكشف مكتبة المحتوى الكاملة');
});
