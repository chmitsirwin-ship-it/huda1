<?php

use App\Models\Hadith;
use App\Models\QuranVerse;

it('renders seeded verses and hadiths on the islamic library page', function () {
    $featuredVerse = QuranVerse::query()->where('is_featured', true)->firstOrFail();
    $featuredHadith = Hadith::query()->where('is_featured', true)->firstOrFail();

    $this->get(route('islamic-library.index'))
        ->assertOk()
        ->assertSeeText($featuredVerse->translation)
        ->assertSeeText($featuredHadith->text)
        ->assertSeeText($featuredHadith->narrator);
});

it('paginates verses and hadith independently', function () {
    foreach (range(1, 12) as $index) {
        QuranVerse::query()->create([
            'surah_number' => 100 + $index,
            'verse_number' => 1,
            'surah_name' => "Test Surah {$index}",
            'arabic_text' => "آية اختبار {$index}",
            'translation' => [
                'en' => "Test verse {$index}",
                'ar' => "آية اختبار {$index}",
            ],
            'tafsir' => [
                'en' => "Test tafsir {$index}",
                'ar' => "تفسير اختبار {$index}",
            ],
            'is_featured' => false,
        ]);

        Hadith::query()->create([
            'collection' => "Test Collection {$index}",
            'narrator' => [
                'en' => "Narrator {$index}",
                'ar' => "الراوي {$index}",
            ],
            'text' => [
                'en' => "Hadith text {$index}",
                'ar' => "نص الحديث {$index}",
            ],
            'source' => [
                'en' => "Source {$index}",
                'ar' => "المصدر {$index}",
            ],
            'grade' => [
                'en' => 'Sahih',
                'ar' => 'صحيح',
            ],
            'is_featured' => false,
        ]);
    }

    $this->get(route('islamic-library.index', ['hadiths_page' => 2]))
        ->assertOk()
        ->assertViewHas('verses', fn ($verses) => $verses->currentPage() === 1)
        ->assertViewHas('hadiths', fn ($hadiths) => $hadiths->currentPage() === 2);

    $this->get(route('islamic-library.index', ['verses_page' => 2]))
        ->assertOk()
        ->assertViewHas('verses', fn ($verses) => $verses->currentPage() === 2)
        ->assertViewHas('hadiths', fn ($hadiths) => $hadiths->currentPage() === 1);
});
