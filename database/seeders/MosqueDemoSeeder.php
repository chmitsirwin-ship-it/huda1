<?php

namespace Database\Seeders;

use App\Enums\AnnouncementType;
use App\Enums\ContactSubmissionStatus;
use App\Enums\EventStatus;
use App\Enums\MediaType;
use App\Enums\TextDirection;
use App\Models\Announcement;
use App\Models\ContactSubmission;
use App\Models\Event;
use App\Models\Hadith;
use App\Models\Khutba;
use App\Models\Language;
use App\Models\MediaItem;
use App\Models\MosqueSettings;
use App\Models\Page;
use App\Models\PrayerTime;
use App\Models\QuranVerse;
use App\Models\Slider;
use App\Models\Staff;
use App\Models\User;
use Database\Seeders\Support\MosqueDemoPageData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Redberry\PageBuilderPlugin\Models\PageBuilderBlock;

class MosqueDemoSeeder extends Seeder
{
    public function run(): void
    {
        $image = $this->prepareSeedImage();

        $this->seedLanguages();
        $this->seedAdminUser();
        $this->seedSettings($image);
        $this->seedMosqueSettingsRecord($image);
        $this->seedPrayerTimes();
        $this->seedAnnouncements();
        $this->seedEvents($image);
        $this->seedQuranVerses();
        $this->seedHadiths();
        $this->seedMediaItems($image);
        $this->seedSliders($image);
        $this->seedStaff($image);
        $this->seedKhutbas();
        $this->seedContactSubmissions();
        $this->seedPages($image);
    }

    private function prepareSeedImage(): string
    {
        $source = public_path('images/ph.jpg');
        $destination = storage_path('app/public/ph.jpg');

        if (! File::exists($source)) {
            throw new \RuntimeException('The placeholder image public/images/ph.jpg is required for demo seeding.');
        }

        if (! File::exists($destination)) {
            File::ensureDirectoryExists(dirname($destination));
            File::copy($source, $destination);
        }

        return 'ph.jpg';
    }

    private function seedLanguages(): void
    {
        Language::updateOrCreate(
            ['code' => 'en'],
            [
                'name' => 'English',
                'direction' => TextDirection::Ltr,
                'is_active' => true,
                'is_default' => true,
                'sort_order' => 1,
            ]
        );

        Language::updateOrCreate(
            ['code' => 'ar'],
            [
                'name' => 'العربية',
                'direction' => TextDirection::Rtl,
                'is_active' => true,
                'is_default' => false,
                'sort_order' => 2,
            ]
        );
    }

    private function seedAdminUser(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mosque.test'],
            [
                'name' => 'Mosque Admin',
                'password' => Hash::make('password'),
            ]
        );
    }

    private function seedSettings(string $image): void
    {
        setting([
            'general' => [
                'name' => 'Masjid Al-Huda | مسجد الهدى',
                'description' => 'A welcoming mosque for prayer, learning, and community service. | مسجد جامع للعبادة والتعلم وخدمة المجتمع.',
                'address' => '15 Mercy Street, Nasr City, Cairo | ١٥ شارع الرحمة، مدينة نصر، القاهرة',
                'phone' => '+20 100 555 7788',
                'email' => 'info@masjidalhuda.test',
            ],
            'location' => [
                'latitude' => 30.0596185,
                'longitude' => 31.3260284,
            ],
            'social' => [
                'facebook' => 'https://facebook.com/masjidalhuda',
                'twitter' => 'https://x.com/masjidalhuda',
                'instagram' => 'https://instagram.com/masjidalhuda',
                'youtube' => 'https://youtube.com/@masjidalhuda',
            ],
            'prayer' => [
                'method' => 'manual',
                'calculation_method' => 5,
                'timezone' => 'Africa/Cairo',
            ],
            'seo' => [
                'meta_title' => 'Masjid Al-Huda',
                'meta_description' => 'Prayer times, events, khutbas, and community updates from Masjid Al-Huda.',
            ],
        ]);
    }

    private function seedMosqueSettingsRecord(string $image): void
    {
        MosqueSettings::query()->updateOrCreate(
            ['id' => 1],
            [
                'name' => [
                    'en' => 'Masjid Al-Huda',
                    'ar' => 'مسجد الهدى',
                ],
                'description' => [
                    'en' => 'A welcoming mosque for prayer, learning, and service.',
                    'ar' => 'مسجد جامع للعبادة والتعلم وخدمة المجتمع.',
                ],
                'address' => [
                    'en' => '15 Mercy Street, Nasr City, Cairo',
                    'ar' => '١٥ شارع الرحمة، مدينة نصر، القاهرة',
                ],
                'meta_title' => [
                    'en' => 'Masjid Al-Huda',
                    'ar' => 'مسجد الهدى',
                ],
                'meta_description' => [
                    'en' => 'Prayer times, events, khutbas, and community updates from Masjid Al-Huda.',
                    'ar' => 'مواقيت الصلاة والفعاليات والخطب وآخر أخبار مسجد الهدى.',
                ],
                'latitude' => 30.0596185,
                'longitude' => 31.3260284,
                'logo' => $image,
                'favicon' => $image,
                'phone' => '+20 100 555 7788',
                'email' => 'info@masjidalhuda.test',
                'facebook' => 'https://facebook.com/masjidalhuda',
                'twitter' => 'https://x.com/masjidalhuda',
                'instagram' => 'https://instagram.com/masjidalhuda',
                'youtube' => 'https://youtube.com/@masjidalhuda',
                'prayer_method' => 'manual',
                'calculation_method' => 5,
                'timezone' => 'Africa/Cairo',
            ]
        );
    }

    private function seedPrayerTimes(): void
    {
        $start = now()->startOfMonth();

        for ($offset = 0; $offset < $start->daysInMonth; $offset++) {
            $date = $start->copy()->addDays($offset);
            $base = 5 * 60 + 2 + ($offset % 5);

            PrayerTime::updateOrCreate(
                ['date' => $date->toDateString()],
                [
                    'fajr_adhan' => $this->minutesToTime($base),
                    'fajr_iqamah' => $this->minutesToTime($base + 20),
                    'sunrise' => $this->minutesToTime($base + 88),
                    'dhuhr_adhan' => $this->minutesToTime(12 * 60 + 8 + ($offset % 4)),
                    'dhuhr_iqamah' => $this->minutesToTime(12 * 60 + 28 + ($offset % 4)),
                    'asr_adhan' => $this->minutesToTime(15 * 60 + 34 + ($offset % 4)),
                    'asr_iqamah' => $this->minutesToTime(15 * 60 + 49 + ($offset % 4)),
                    'maghrib_adhan' => $this->minutesToTime(18 * 60 + 4 - ($offset % 3)),
                    'maghrib_iqamah' => $this->minutesToTime(18 * 60 + 11 - ($offset % 3)),
                    'isha_adhan' => $this->minutesToTime(19 * 60 + 31 - ($offset % 3)),
                    'isha_iqamah' => $this->minutesToTime(19 * 60 + 51 - ($offset % 3)),
                    'jummah_time' => $date->isFriday() ? '13:15:00' : null,
                    'jummah_khutba_time' => $date->isFriday() ? '12:45:00' : null,
                ]
            );
        }
    }

    private function seedAnnouncements(): void
    {
        $items = [
            [
                'published_at' => now()->subDays(2),
                'expires_at' => now()->addDays(10),
                'type' => AnnouncementType::General,
                'is_active' => true,
                'title' => [
                    'en' => 'Ramadan food drive is now open',
                    'ar' => 'بدأت حملة السلة الرمضانية',
                ],
                'content' => [
                    'en' => 'Volunteers can register after Maghrib to help pack and distribute food baskets for families in need.',
                    'ar' => 'يمكن للمتطوعين التسجيل بعد صلاة المغرب للمساعدة في تجهيز وتوزيع السلال الغذائية للأسر المحتاجة.',
                ],
            ],
            [
                'published_at' => now()->subDay(),
                'expires_at' => now()->addDays(5),
                'type' => AnnouncementType::Urgent,
                'is_active' => true,
                'title' => [
                    'en' => 'Parking update for Friday prayer',
                    'ar' => 'تحديث مهم بخصوص مواقف الجمعة',
                ],
                'content' => [
                    'en' => 'Please use the east entrance lot first. Additional volunteers will guide traffic before Jumu\'ah.',
                    'ar' => 'يرجى استخدام موقف البوابة الشرقية أولاً، وسيقوم المتطوعون بتنظيم حركة الدخول قبل صلاة الجمعة.',
                ],
            ],
            [
                'published_at' => now()->subDays(4),
                'expires_at' => now()->subDay(),
                'type' => AnnouncementType::Maintenance,
                'is_active' => true,
                'title' => [
                    'en' => 'Library hall maintenance notice',
                    'ar' => 'تنبيه صيانة قاعة المكتبة',
                ],
                'content' => [
                    'en' => 'The library hall is temporarily closed for carpet cleaning.',
                    'ar' => 'تم إغلاق قاعة المكتبة مؤقتاً لأعمال تنظيف السجاد.',
                ],
            ],
            [
                'published_at' => now()->subDays(3),
                'expires_at' => now()->addDays(14),
                'type' => AnnouncementType::General,
                'is_active' => false,
                'title' => [
                    'en' => 'Archived volunteer orientation',
                    'ar' => 'تعريف المتطوعين المؤرشف',
                ],
                'content' => [
                    'en' => 'This announcement should stay hidden from the public listing.',
                    'ar' => 'يجب أن يظل هذا الإعلان مخفياً عن صفحة الإعلانات العامة.',
                ],
            ],
        ];

        foreach ($items as $item) {
            Announcement::updateOrCreate(
                ['published_at' => $item['published_at']],
                $item
            );
        }
    }

    private function seedEvents(string $image): void
    {
        $items = [
            [
                'starts_at' => now()->addDays(3)->setTime(18, 30),
                'ends_at' => now()->addDays(3)->setTime(21, 0),
                'status' => EventStatus::Published,
                'is_featured' => true,
                'image' => $image,
                'title' => [
                    'en' => 'Community Iftar Night',
                    'ar' => 'ليلة الإفطار المجتمعي',
                ],
                'description' => [
                    'en' => '<p>Families are invited to gather for a shared iftar followed by a short reminder and community dua.</p>',
                    'ar' => '<p>ندعو العائلات لحضور إفطار جماعي يتبعه تذكير قصير ودعاء للمجتمع.</p>',
                ],
                'location' => [
                    'en' => 'Main Prayer Hall',
                    'ar' => 'قاعة الصلاة الرئيسية',
                ],
            ],
            [
                'starts_at' => now()->addWeek()->setTime(19, 0),
                'ends_at' => now()->addWeek()->setTime(20, 30),
                'status' => EventStatus::Published,
                'is_featured' => false,
                'image' => $image,
                'title' => [
                    'en' => 'Youth Quran Circle',
                    'ar' => 'حلقة القرآن للشباب',
                ],
                'description' => [
                    'en' => '<p>A weekly gathering focused on tajwid, memorisation, and discussion.</p>',
                    'ar' => '<p>لقاء أسبوعي يركّز على التجويد والحفظ والمناقشة.</p>',
                ],
                'location' => [
                    'en' => 'Education Room A',
                    'ar' => 'قاعة التعليم أ',
                ],
            ],
            [
                'starts_at' => now()->addDays(12)->setTime(10, 0),
                'ends_at' => now()->addDays(12)->setTime(13, 0),
                'status' => EventStatus::Published,
                'is_featured' => false,
                'image' => $image,
                'title' => [
                    'en' => 'Family Service Day',
                    'ar' => 'يوم الخدمة الأسرية',
                ],
                'description' => [
                    'en' => '<p>Join us to clean, organise, and refresh shared mosque spaces before the weekend.</p>',
                    'ar' => '<p>شاركونا في تنظيف وتجهيز مرافق المسجد المشتركة قبل عطلة نهاية الأسبوع.</p>',
                ],
                'location' => [
                    'en' => 'Courtyard and classrooms',
                    'ar' => 'الساحة والفصول الدراسية',
                ],
            ],
            [
                'starts_at' => now()->addDays(15)->setTime(20, 0),
                'ends_at' => now()->addDays(15)->setTime(21, 0),
                'status' => EventStatus::Draft,
                'is_featured' => false,
                'image' => $image,
                'title' => [
                    'en' => 'Draft admin planning session',
                    'ar' => 'جلسة التخطيط الإدارية المسودة',
                ],
                'description' => [
                    'en' => '<p>This draft event is seeded for admin and public visibility checks.</p>',
                    'ar' => '<p>تمت إضافة هذه الفعالية المسودة لاختبار الواجهة العامة ولوحة التحكم.</p>',
                ],
                'location' => [
                    'en' => 'Office meeting room',
                    'ar' => 'قاعة اجتماعات الإدارة',
                ],
            ],
        ];

        foreach ($items as $item) {
            Event::updateOrCreate(
                ['starts_at' => $item['starts_at']],
                $item
            );
        }
    }

    private function seedQuranVerses(): void
    {
        $items = [
            [
                'surah_number' => 94,
                'verse_number' => 5,
                'surah_name' => 'Ash-Sharh',
                'arabic_text' => 'فَإِنَّ مَعَ الْعُسْرِ يُسْرًا',
                'translation' => [
                    'en' => 'Indeed, with hardship comes ease.',
                    'ar' => 'فإن مع العسر يسراً.',
                ],
                'tafsir' => [
                    'en' => 'Allah reminds the believer that relief accompanies every sincere trial.',
                    'ar' => 'يذكّر الله المؤمن بأن الفرج ملازم لكل ابتلاء يواجهه بإيمان وصبر.',
                ],
                'is_featured' => true,
            ],
            [
                'surah_number' => 49,
                'verse_number' => 13,
                'surah_name' => 'Al-Hujurat',
                'arabic_text' => 'يَا أَيُّهَا النَّاسُ إِنَّا خَلَقْنَاكُم مِّن ذَكَرٍ وَأُنثَىٰ وَجَعَلْنَاكُمْ شُعُوبًا وَقَبَائِلَ لِتَعَارَفُوا',
                'translation' => [
                    'en' => 'O mankind, We created you from a male and a female and made you peoples and tribes so that you may know one another.',
                    'ar' => 'يا أيها الناس إنا خلقناكم من ذكر وأنثى وجعلناكم شعوباً وقبائل لتعارفوا.',
                ],
                'tafsir' => [
                    'en' => 'Human diversity is a sign of Allah and a basis for mutual respect, not superiority.',
                    'ar' => 'اختلاف الناس آية من آيات الله ووسيلة للتعارف والتكامل لا للتفاخر والاستعلاء.',
                ],
                'is_featured' => false,
            ],
            [
                'surah_number' => 2,
                'verse_number' => 286,
                'surah_name' => 'Al-Baqarah',
                'arabic_text' => 'لَا يُكَلِّفُ اللَّهُ نَفْسًا إِلَّا وُسْعَهَا',
                'translation' => [
                    'en' => 'Allah does not burden a soul beyond what it can bear.',
                    'ar' => 'لا يكلّف الله نفساً إلا وسعها.',
                ],
                'tafsir' => [
                    'en' => 'This verse anchors trust in Allah’s wisdom and mercy when responsibilities feel heavy.',
                    'ar' => 'تغرس هذه الآية الثقة بحكمة الله ورحمته حين تبدو التكاليف ثقيلة على النفس.',
                ],
                'is_featured' => false,
            ],
        ];

        foreach ($items as $item) {
            QuranVerse::updateOrCreate(
                [
                    'surah_number' => $item['surah_number'],
                    'verse_number' => $item['verse_number'],
                ],
                $item
            );
        }
    }

    private function seedHadiths(): void
    {
        $items = [
            [
                'collection' => 'Sahih al-Bukhari',
                'is_featured' => true,
                'narrator' => [
                    'en' => 'Umar ibn Al-Khattab',
                    'ar' => 'عمر بن الخطاب رضي الله عنه',
                ],
                'text' => [
                    'en' => 'Actions are judged by intentions, and every person will have only what they intended.',
                    'ar' => 'إنما الأعمال بالنيات، وإنما لكل امرئ ما نوى.',
                ],
                'source' => [
                    'en' => 'Sahih al-Bukhari and Sahih Muslim',
                    'ar' => 'صحيح البخاري وصحيح مسلم',
                ],
                'grade' => [
                    'en' => 'Sahih',
                    'ar' => 'صحيح',
                ],
            ],
            [
                'collection' => 'Sunan al-Tirmidhi',
                'is_featured' => false,
                'narrator' => [
                    'en' => 'Abdullah ibn Amr',
                    'ar' => 'عبد الله بن عمرو رضي الله عنهما',
                ],
                'text' => [
                    'en' => 'The merciful are shown mercy by the Most Merciful. Show mercy to those on earth and the One above the heavens will show mercy to you.',
                    'ar' => 'الراحمون يرحمهم الرحمن، ارحموا من في الأرض يرحمكم من في السماء.',
                ],
                'source' => [
                    'en' => 'Sunan al-Tirmidhi',
                    'ar' => 'سنن الترمذي',
                ],
                'grade' => [
                    'en' => 'Hasan Sahih',
                    'ar' => 'حسن صحيح',
                ],
            ],
            [
                'collection' => 'Sahih al-Bukhari',
                'is_featured' => false,
                'narrator' => [
                    'en' => 'Uthman ibn Affan',
                    'ar' => 'عثمان بن عفان رضي الله عنه',
                ],
                'text' => [
                    'en' => 'The best of you are those who learn the Quran and teach it.',
                    'ar' => 'خيركم من تعلم القرآن وعلمه.',
                ],
                'source' => [
                    'en' => 'Sahih al-Bukhari',
                    'ar' => 'صحيح البخاري',
                ],
                'grade' => [
                    'en' => 'Sahih',
                    'ar' => 'صحيح',
                ],
            ],
        ];

        foreach ($items as $item) {
            Hadith::updateOrCreate(
                [
                    'collection' => $item['collection'],
                    'source->en' => $item['source']['en'],
                ],
                $item
            );
        }
    }

    private function seedMediaItems(string $image): void
    {
        $items = [
            [
                'collection' => 'Ramadan 2026',
                'sort_order' => 1,
                'type' => MediaType::Image,
                'file_path' => $image,
                'title' => [
                    'en' => 'Community iftar setup',
                    'ar' => 'تجهيز إفطار المجتمع',
                ],
                'alt_text' => [
                    'en' => 'Volunteers preparing tables for iftar',
                    'ar' => 'متطوعون يجهزون الطاولات للإفطار',
                ],
            ],
            [
                'collection' => 'Ramadan 2026',
                'sort_order' => 2,
                'type' => MediaType::Image,
                'file_path' => $image,
                'title' => [
                    'en' => 'Families arriving before Maghrib',
                    'ar' => 'وصول العائلات قبل المغرب',
                ],
                'alt_text' => [
                    'en' => 'Families entering the mosque courtyard',
                    'ar' => 'عائلات تدخل ساحة المسجد',
                ],
            ],
            [
                'collection' => 'Youth Programs',
                'sort_order' => 3,
                'type' => MediaType::Image,
                'file_path' => $image,
                'title' => [
                    'en' => 'Youth Quran circle',
                    'ar' => 'حلقة القرآن للشباب',
                ],
                'alt_text' => [
                    'en' => 'Students seated in a Quran study circle',
                    'ar' => 'طلاب يجلسون في حلقة قرآن',
                ],
            ],
            [
                'collection' => 'Volunteer Day',
                'sort_order' => 4,
                'type' => MediaType::Image,
                'file_path' => $image,
                'title' => [
                    'en' => 'Volunteer clean-up team',
                    'ar' => 'فريق التنظيف التطوعي',
                ],
                'alt_text' => [
                    'en' => 'Volunteers cleaning shared mosque spaces',
                    'ar' => 'متطوعون ينظفون مرافق المسجد المشتركة',
                ],
            ],
            [
                'collection' => 'Volunteer Day',
                'sort_order' => 5,
                'type' => MediaType::Image,
                'file_path' => $image,
                'title' => [
                    'en' => 'Courtyard refresh',
                    'ar' => 'تجديد ساحة المسجد',
                ],
                'alt_text' => [
                    'en' => 'A refreshed courtyard prepared for visitors',
                    'ar' => 'ساحة مجهزة ومهيأة لاستقبال الزوار',
                ],
            ],
            [
                'collection' => 'Ramadan 2026',
                'sort_order' => 6,
                'type' => MediaType::Image,
                'file_path' => $image,
                'title' => [
                    'en' => 'Evening reminder after prayer',
                    'ar' => 'خاطرة إيمانية بعد الصلاة',
                ],
                'alt_text' => [
                    'en' => 'Congregation listening to a short evening reminder',
                    'ar' => 'المصلون يستمعون إلى خاطرة مسائية قصيرة',
                ],
            ],
            [
                'collection' => 'Ramadan 2026',
                'sort_order' => 7,
                'type' => MediaType::Image,
                'file_path' => $image,
                'title' => [
                    'en' => 'Evening reminder after prayer',
                    'ar' => 'خاطرة إيمانية بعد الصلاة',
                ],
                'alt_text' => [
                    'en' => 'Congregation listening to a short evening reminder',
                    'ar' => 'المصلون يستمعون إلى خاطرة مسائية قصيرة',
                ],
            ],
            [
                'collection' => 'Ramadan 2026',
                'sort_order' => 8,
                'type' => MediaType::Image,
                'file_path' => $image,
                'title' => [
                    'en' => 'Evening reminder after prayer',
                    'ar' => 'خاطرة إيمانية بعد الصلاة',
                ],
                'alt_text' => [
                    'en' => 'Congregation listening to a short evening reminder',
                    'ar' => 'المصلون يستمعون إلى خاطرة مسائية قصيرة',
                ],
            ],
        ];

        foreach ($items as $item) {
            MediaItem::updateOrCreate(
                [
                    'collection' => $item['collection'],
                    'sort_order' => $item['sort_order'],
                ],
                $item
            );
        }
    }

    private function seedSliders(string $image): void
    {
        $items = [
            [
                'sort_order' => 1,
                'image' => $image,
                'is_active' => true,
                'button_text' => 'Learn More',
                'button_url' => '/contact',
                'title' => [
                    'en' => 'Welcome to Masjid Al-Huda',
                    'ar' => 'مرحباً بكم في مسجد الهدى',
                ],
                'subtitle' => [
                    'en' => 'Prayer, knowledge, and service rooted in mercy.',
                    'ar' => 'عبادة وعلم وخدمة تنطلق من الرحمة.',
                ],
            ],
            [
                'sort_order' => 2,
                'image' => $image,
                'is_active' => true,
                'button_text' => 'View Programs',
                'button_url' => '/events',
                'title' => [
                    'en' => 'Programs for every generation',
                    'ar' => 'برامج تناسب كل جيل',
                ],
                'subtitle' => [
                    'en' => 'From children’s classes to family workshops and community outreach.',
                    'ar' => 'من حلقات الأطفال إلى ورش الأسرة ومبادرات خدمة المجتمع.',
                ],
            ],
            [
                'sort_order' => 3,
                'image' => $image,
                'is_active' => true,
                'button_text' => 'Support the Mosque',
                'button_url' => '/contact',
                'title' => [
                    'en' => 'Built by a caring community',
                    'ar' => 'بُني بعطاء المجتمع',
                ],
                'subtitle' => [
                    'en' => 'Your time, presence, and generosity keep this space alive.',
                    'ar' => 'وقتكم وحضوركم وكرمكم هو ما يبقي هذا المكان حيّاً.',
                ],
            ],
        ];

        foreach ($items as $item) {
            Slider::updateOrCreate(
                ['sort_order' => $item['sort_order']],
                $item
            );
        }
    }

    private function seedStaff(string $image): void
    {
        $items = [
            [
                'email' => 'imam@masjidalhuda.test',
                'photo' => $image,
                'phone' => '+20 100 111 2200',
                'sort_order' => 1,
                'is_active' => true,
                'name' => [
                    'en' => 'Shaykh Ahmad Hassan',
                    'ar' => 'الشيخ أحمد حسن',
                ],
                'title' => [
                    'en' => 'Imam and Khateeb',
                    'ar' => 'إمام وخطيب',
                ],
                'bio' => [
                    'en' => 'Leads daily prayers, Friday sermons, and weekly Quran circles for adults.',
                    'ar' => 'يقود الصلوات اليومية وخطب الجمعة وحلقات القرآن الأسبوعية للكبار.',
                ],
            ],
            [
                'email' => 'education@masjidalhuda.test',
                'photo' => $image,
                'phone' => '+20 100 111 3300',
                'sort_order' => 2,
                'is_active' => true,
                'name' => [
                    'en' => 'Ustadhah Maryam Ali',
                    'ar' => 'الأستاذة مريم علي',
                ],
                'title' => [
                    'en' => 'Education Coordinator',
                    'ar' => 'منسقة البرامج التعليمية',
                ],
                'bio' => [
                    'en' => 'Oversees children’s classes, women’s study circles, and volunteer teachers.',
                    'ar' => 'تشرف على حلقات الأطفال والدروس النسائية وبرامج المعلمات المتطوعات.',
                ],
            ],
            [
                'email' => 'community@masjidalhuda.test',
                'photo' => $image,
                'phone' => '+20 100 111 4400',
                'sort_order' => 3,
                'is_active' => true,
                'name' => [
                    'en' => 'Omar Abdelrahman',
                    'ar' => 'عمر عبد الرحمن',
                ],
                'title' => [
                    'en' => 'Community Services Lead',
                    'ar' => 'مسؤول خدمة المجتمع',
                ],
                'bio' => [
                    'en' => 'Coordinates food relief, family support referrals, and youth service projects.',
                    'ar' => 'ينسق برامج الإغاثة الغذائية ودعم الأسر ومبادرات الخدمة الشبابية.',
                ],
            ],
            [
                'email' => 'office@masjidalhuda.test',
                'photo' => $image,
                'phone' => '+20 100 111 5500',
                'sort_order' => 4,
                'is_active' => true,
                'name' => [
                    'en' => 'Fatimah Samir',
                    'ar' => 'فاطمة سمير',
                ],
                'title' => [
                    'en' => 'Operations Administrator',
                    'ar' => 'إدارية التشغيل',
                ],
                'bio' => [
                    'en' => 'Supports daily administration, front desk communication, and event logistics.',
                    'ar' => 'تدعم الأعمال الإدارية اليومية والتواصل مع الزوار وتجهيزات الفعاليات.',
                ],
            ],
        ];

        foreach ($items as $item) {
            Staff::updateOrCreate(
                ['email' => $item['email']],
                $item
            );
        }
    }

    private function seedKhutbas(): void
    {
        $items = [
            [
                'date' => now()->subWeeks(1)->toDateString(),
                'is_published' => true,
                'audio_url' => 'https://dl.espressif.com/dl/audio/ff-16b-2c-44100hz.mp3',
                'video_url' => 'https://www.youtube.com/watch?v=vTIYqaj0xGk',
                'title' => [
                    'en' => 'Steadfastness in Worship',
                    'ar' => 'الثبات على العبادة',
                ],
                'speaker' => [
                    'en' => 'Shaykh Ahmad Hassan',
                    'ar' => 'الشيخ أحمد حسن',
                ],
                'topic' => [
                    'en' => 'Consistency and sincerity',
                    'ar' => 'الاستمرار والإخلاص',
                ],
                'summary' => [
                    'en' => 'A reminder that small, consistent deeds done sincerely are beloved to Allah.',
                    'ar' => 'تذكير بأن الأعمال القليلة الدائمة إذا صاحَبَها الإخلاص فهي من أحب الأعمال إلى الله.',
                ],
            ],
            [
                'date' => now()->subWeeks(2)->toDateString(),
                'is_published' => true,
                'audio_url' => 'https://dl.espressif.com/dl/audio/ff-16b-2c-44100hz.mp3',
                'video_url' => null,
                'title' => [
                    'en' => 'Honouring the Neighbour',
                    'ar' => 'إكرام الجار',
                ],
                'speaker' => [
                    'en' => 'Shaykh Ahmad Hassan',
                    'ar' => 'الشيخ أحمد حسن',
                ],
                'topic' => [
                    'en' => 'Mercy in daily life',
                    'ar' => 'الرحمة في الحياة اليومية',
                ],
                'summary' => [
                    'en' => 'The khutba highlighted prophetic guidance on kindness, trust, and social responsibility.',
                    'ar' => 'سلطت الخطبة الضوء على الهدي النبوي في الإحسان والأمانة والمسؤولية المجتمعية.',
                ],
            ],
            [
                'date' => now()->subWeeks(3)->toDateString(),
                'is_published' => false,
                'audio_url' => null,
                'video_url' => null,
                'title' => [
                    'en' => 'Draft khutba for admin review',
                    'ar' => 'خطبة مسودة للمراجعة الإدارية',
                ],
                'speaker' => [
                    'en' => 'Shaykh Ahmad Hassan',
                    'ar' => 'الشيخ أحمد حسن',
                ],
                'topic' => [
                    'en' => 'Internal review only',
                    'ar' => 'للمراجعة الداخلية فقط',
                ],
                'summary' => [
                    'en' => 'This unpublished khutba supports public filtering and admin edit checks.',
                    'ar' => 'هذه الخطبة غير المنشورة مخصصة لاختبارات التصفية العامة ولوحة التحكم.',
                ],
            ],
        ];

        foreach ($items as $item) {
            Khutba::updateOrCreate(
                ['date' => $item['date']],
                $item
            );
        }
    }

    private function seedContactSubmissions(): void
    {
        $items = [
            [
                'email' => 'visitor.one@example.com',
                'name' => 'Amina Yusuf',
                'phone' => '+20 101 111 1111',
                'subject' => 'School visit inquiry',
                'message' => 'We would like to arrange a guided mosque visit for our students next month.',
                'status' => ContactSubmissionStatus::New,
                'read_at' => null,
            ],
            [
                'email' => 'family@example.com',
                'name' => 'Mahmoud Ali',
                'phone' => '+20 102 222 2222',
                'subject' => 'Weekend class registration',
                'message' => 'Please share the details for the weekend Quran class registration for teenagers.',
                'status' => ContactSubmissionStatus::Read,
                'read_at' => now()->subDay(),
            ],
        ];

        foreach ($items as $item) {
            ContactSubmission::updateOrCreate(
                ['email' => $item['email'], 'subject' => $item['subject']],
                $item
            );
        }
    }

    private function seedPages(string $image): void
    {
        $featuredVerse = QuranVerse::query()->where('is_featured', true)->first();
        $featuredHadith = Hadith::query()->where('is_featured', true)->first();

        foreach (MosqueDemoPageData::definitions($image, $featuredVerse?->id, $featuredHadith?->id) as $pageDefinition) {
            $page = Page::updateOrCreate(
                ['slug' => $pageDefinition['slug']],
                $pageDefinition['attributes']
            );

            $this->replacePageBlocks($page, $pageDefinition['blocks']);
        }
    }

    private function replacePageBlocks(Page $page, array $blocks): void
    {
        $page->pageBuilderBlocks()->delete();

        foreach ($blocks as $index => $block) {
            PageBuilderBlock::create([
                'block_type' => $block['block_type'],
                'page_builder_blockable_type' => Page::class,
                'page_builder_blockable_id' => $page->id,
                'data' => $block['data'],
                'order' => $index + 1,
            ]);
        }
    }

    private function minutesToTime(int $minutes): string
    {
        return Carbon::createFromTime(0, 0)->addMinutes($minutes)->format('H:i:s');
    }
}
