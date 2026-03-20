<?php

use App\Filament\Admin\Resources\Sliders\SliderResource;
use App\Models\Slider;

it('protects the slider resource pages', function () {
    $slider = Slider::query()->firstOrFail();

    assertGuestIsRedirectedFrom(adminPageUrls(SliderResource::class, $slider));
});

it('lets admins open slider resource pages and see seeded slides', function () {
    $slider = Slider::query()->orderBy('sort_order')->firstOrFail();

    assertAdminCanOpen(adminPageUrls(SliderResource::class, $slider));

    $this->followingRedirects()
        ->get(SliderResource::getUrl('index'))
        ->assertOk()
        ->assertSeeText($slider->title);
});
