<?php

use App\Filament\Admin\Blocks\QuranPlayerBlock;
use App\Models\Page;
use Redberry\PageBuilderPlugin\Models\PageBuilderBlock;

it('renders the quran player block shell on a published page', function () {
    $page = Page::query()->create([
        'title' => [
            'en' => 'Quran Player Page',
            'ar' => 'صفحة مشغل القرآن',
        ],
        'slug' => 'quran-player-page',
        'is_published' => true,
        'show_in_nav' => false,
    ]);

    PageBuilderBlock::query()->create([
        'block_type' => QuranPlayerBlock::class,
        'page_builder_blockable_type' => Page::class,
        'page_builder_blockable_id' => $page->id,
        'data' => [
            'title' => [
                'en' => 'Quran Player Test',
                'ar' => 'اختبار مشغل القرآن',
            ],
            'intro' => [
                'en' => 'A focused Quran recitation player.',
                'ar' => 'مشغل تلاوات قرآن بسيط.',
            ],
        ],
        'order' => 1,
    ]);

    $this->get(route('page.show', $page->slug))
        ->assertOk()
        ->assertSeeText('Quran Player Test')
        ->assertSee('data-quran-player=', false);
});
