<?php

namespace App\Providers;

use AbdulmajeedJamaan\FilamentTranslatableTabs\TranslatableTabs;
use App\Filament\Admin\Blocks\AnnouncementsBlock;
use App\Filament\Admin\Blocks\ContactMapBlock;
use App\Filament\Admin\Blocks\CounterBlock;
use App\Filament\Admin\Blocks\CtaBlock;
use App\Filament\Admin\Blocks\CustomHtmlBlock;
use App\Filament\Admin\Blocks\DonationBlock;
use App\Filament\Admin\Blocks\EventsBlock;
use App\Filament\Admin\Blocks\FaqBlock;
use App\Filament\Admin\Blocks\GalleryBlock;
use App\Filament\Admin\Blocks\HadithBlock;
use App\Filament\Admin\Blocks\HeroBlock;
use App\Filament\Admin\Blocks\KhutbaArchiveBlock;
use App\Filament\Admin\Blocks\PrayerTimesBlock;
use App\Filament\Admin\Blocks\QuranVerseBlock;
use App\Filament\Admin\Blocks\RichTextBlock;
use App\Filament\Admin\Blocks\SliderBlock;
use App\Filament\Admin\Blocks\SpacerBlock;
use App\Filament\Admin\Blocks\StaffBlock;
use App\Filament\Admin\Blocks\TestimonialBlock;
use App\Filament\Admin\Blocks\VideoBlock;
use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Redberry\PageBuilderPlugin\Components\Forms\PageBuilder;
use Statikbe\FilamentTranslationManager\FilamentTranslationManager;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Model::unguard();
        PageBuilder::configureUsing(fn (PageBuilder $pageBuilder) => $pageBuilder->blocks([
            HeroBlock::class,
            SliderBlock::class,
            PrayerTimesBlock::class,
            EventsBlock::class,
            AnnouncementsBlock::class,
            QuranVerseBlock::class,
            HadithBlock::class,
            StaffBlock::class,
            GalleryBlock::class,
            RichTextBlock::class,
            ContactMapBlock::class,
            CustomHtmlBlock::class,
            SpacerBlock::class,
            KhutbaArchiveBlock::class,
            DonationBlock::class,
            TestimonialBlock::class,
            FaqBlock::class,
            CtaBlock::class,
            VideoBlock::class,
            CounterBlock::class,
        ]));
        $locales = Cache::flexible('local', [43200, 86400], fn () => Language::whereIsActive(true)->orderBy('sort_order', 'asc')->pluck('name', 'code')->toArray());
        TranslatableTabs::configureUsing(function (TranslatableTabs $component) use ($locales) {

            $component->localesLabels($locales)
                ->locales(array_keys($locales))
                ->addDirectionByLocale()
                ->addEmptyBadgeWhenAllFieldsAreEmpty(emptyLabel: __('Not Defined'))
                ->addSetActiveTabThatHasValue();
        });
        FilamentTranslationManager::setLocales(array_keys($locales));

    }
}
