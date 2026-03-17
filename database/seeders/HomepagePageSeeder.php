<?php

namespace Database\Seeders;

use App\Filament\Admin\Blocks\AnnouncementsBlock;
use App\Filament\Admin\Blocks\EventsBlock;
use App\Filament\Admin\Blocks\HeroBlock;
use App\Filament\Admin\Blocks\PrayerTimesBlock;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Redberry\PageBuilderPlugin\Models\PageBuilderBlock;

class HomepagePageSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::firstOrCreate(
            ['slug' => 'home'],
            [
                'title' => ['en' => 'Home', 'ar' => 'الرئيسية'],
                'meta_title' => ['en' => 'Home — Al-Noor Mosque', 'ar' => 'الرئيسية — مسجد النور'],
                'meta_description' => ['en' => 'Welcome to Al-Noor Mosque.', 'ar' => 'مرحباً بكم في مسجد النور.'],
                'is_published' => true,
                'show_in_nav' => false,
                'sort_order' => 0,
            ]
        );

        if ($page->pageBuilderBlocks()->count() === 0) {
            PageBuilderBlock::create([
                'block_type' => HeroBlock::class,
                'page_builder_blockable_type' => Page::class,
                'page_builder_blockable_id' => $page->id,
                'data' => [
                    'heading' => ['en' => 'Welcome to Al-Noor Mosque', 'ar' => 'مرحباً بكم في مسجد النور'],
                    'subheading' => ['en' => 'A place of worship, learning, and community', 'ar' => 'مكان للعبادة والتعلم والمجتمع'],
                    'button_text' => ['en' => 'Learn More', 'ar' => 'اعرف المزيد'],
                    'button_url' => '#',
                ],
                'order' => 1,
            ]);

            PageBuilderBlock::create([
                'block_type' => PrayerTimesBlock::class,
                'page_builder_blockable_type' => Page::class,
                'page_builder_blockable_id' => $page->id,
                'data' => ['style' => 'compact'],
                'order' => 2,
            ]);

            PageBuilderBlock::create([
                'block_type' => EventsBlock::class,
                'page_builder_blockable_type' => Page::class,
                'page_builder_blockable_id' => $page->id,
                'data' => ['style' => 'grid', 'limit' => 3],
                'order' => 3,
            ]);

            PageBuilderBlock::create([
                'block_type' => AnnouncementsBlock::class,
                'page_builder_blockable_type' => Page::class,
                'page_builder_blockable_id' => $page->id,
                'data' => ['limit' => 5],
                'order' => 4,
            ]);
        }
    }
}
