<?php

namespace Database\Seeders;

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
use App\Models\Page;
use Illuminate\Database\Seeder;
use Redberry\PageBuilderPlugin\Models\PageBuilderBlock;

class FullExamplePageSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::firstOrCreate(
            ['slug' => 'full-example'],
            [
                'title' => ['en' => 'Full Example Page', 'ar' => 'صفحة المثال الكامل'],
                'meta_title' => ['en' => 'Full Example — All Blocks', 'ar' => ''],
                'meta_description' => ['en' => 'A full example page showcasing all available content blocks.', 'ar' => ''],
                'is_published' => true,
                'show_in_nav' => false,
                'sort_order' => 99,
            ]
        );

        if ($page->pageBuilderBlocks()->count() > 0) {
            return;
        }

        $blocks = [
            [
                'block_type' => HeroBlock::class,
                'data' => [
                    'heading' => ['en' => 'Welcome to Al-Noor Mosque', 'ar' => 'مرحباً بكم في مسجد النور'],
                    'subheading' => ['en' => 'A place of worship, learning, and community for all Muslims', 'ar' => 'مكان للعبادة والتعلم والمجتمع لجميع المسلمين'],
                    'button_text' => ['en' => 'Learn More', 'ar' => 'اعرف المزيد'],
                    'button_url' => '/about',
                ],
            ],
            [
                'block_type' => CounterBlock::class,
                'data' => [
                    'title' => ['en' => 'Our Community in Numbers', 'ar' => 'مجتمعنا بالأرقام'],
                    'counters' => [
                        ['value' => '2,500+', 'label' => ['en' => 'Community Members', 'ar' => 'أعضاء المجتمع']],
                        ['value' => '15+', 'label' => ['en' => 'Years of Service', 'ar' => 'سنوات من الخدمة']],
                        ['value' => '50+', 'label' => ['en' => 'Weekly Classes', 'ar' => 'دروس أسبوعية']],
                        ['value' => '12', 'label' => ['en' => 'Volunteer Programs', 'ar' => 'برامج تطوعية']],
                    ],
                ],
            ],
            [
                'block_type' => SliderBlock::class,
                'data' => [],
            ],
            [
                'block_type' => PrayerTimesBlock::class,
                'data' => ['style' => 'compact'],
            ],
            [
                'block_type' => SpacerBlock::class,
                'data' => ['size' => 'md'],
            ],
            [
                'block_type' => EventsBlock::class,
                'data' => ['style' => 'grid', 'limit' => 3],
            ],
            [
                'block_type' => AnnouncementsBlock::class,
                'data' => ['limit' => 5],
            ],
            [
                'block_type' => RichTextBlock::class,
                'data' => [
                    'content' => [
                        'en' => '<h2>About Our Mosque</h2><p>Al-Noor Mosque has been serving the Muslim community since 2009. We provide a welcoming space for prayer, education, and community events. Our facilities include a main prayer hall, classrooms for Islamic education, a library, and a community center.</p><p>We are committed to fostering unity, knowledge, and spiritual growth within our community and beyond.</p>',
                        'ar' => '<h2>عن مسجدنا</h2><p>يخدم مسجد النور المجتمع الإسلامي منذ عام 2009. نوفر مساحة ترحيبية للصلاة والتعليم والفعاليات المجتمعية.</p>',
                    ],
                ],
            ],
            [
                'block_type' => QuranVerseBlock::class,
                'data' => ['verse_id' => 1],
            ],
            [
                'block_type' => HadithBlock::class,
                'data' => [],
            ],
            [
                'block_type' => TestimonialBlock::class,
                'data' => [
                    'title' => ['en' => 'What Our Community Says', 'ar' => 'ما يقوله مجتمعنا'],
                    'testimonials' => [
                        [
                            'name' => ['en' => 'Ahmed Al-Rashid', 'ar' => 'أحمد الرشيد'],
                            'role' => ['en' => 'Community Member', 'ar' => 'عضو في المجتمع'],
                            'quote' => ['en' => 'Al-Noor Mosque has been a second home for my family. The welcoming atmosphere and quality programs have greatly enriched our faith and community connections.', 'ar' => 'كان مسجد النور بيتاً ثانياً لعائلتي. أثرت الأجواء الترحيبية والبرامج الجيدة إيماننا وروابطنا المجتمعية بشكل كبير.'],
                        ],
                        [
                            'name' => ['en' => 'Fatima Hassan', 'ar' => 'فاطمة حسن'],
                            'role' => ['en' => 'Youth Program Participant', 'ar' => 'مشاركة في برنامج الشباب'],
                            'quote' => ['en' => 'The youth programs here are exceptional. I have learned so much about Islam and made lifelong friends through the various activities and classes.', 'ar' => 'البرامج الشبابية هنا استثنائية. تعلمت الكثير عن الإسلام وأكسبتني صداقات مدى الحياة.'],
                        ],
                        [
                            'name' => ['en' => 'Omar Khalil', 'ar' => 'عمر خليل'],
                            'role' => ['en' => 'Volunteer', 'ar' => 'متطوع'],
                            'quote' => ['en' => 'Volunteering at Al-Noor has been one of the most rewarding experiences of my life. The community spirit here is truly inspiring.', 'ar' => 'كان التطوع في النور من أكثر تجاربي فائدة. روح المجتمع هنا ملهمة حقاً.'],
                        ],
                    ],
                ],
            ],
            [
                'block_type' => DonationBlock::class,
                'data' => [
                    'title' => ['en' => 'Support Our Mosque', 'ar' => 'ادعم مسجدنا'],
                    'description' => ['en' => 'Your generous donations help us maintain our facilities, support educational programs, and serve those in need within our community. Every contribution, no matter how small, makes a difference.', 'ar' => 'تساعدنا تبرعاتكم الكريمة في الحفاظ على施设نا ودعم البرامج التعليمية وخدمة المحتاجين في مجتمعنا.'],
                    'button_text' => ['en' => 'Donate Now', 'ar' => 'تبرع الآن'],
                    'button_url' => '/donate',
                ],
            ],
            [
                'block_type' => GalleryBlock::class,
                'data' => ['limit' => 8],
            ],
            [
                'block_type' => VideoBlock::class,
                'data' => [
                    'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'caption' => ['en' => 'Friday Khutba — Weekly sermon from our Imam', 'ar' => 'خطبة الجمعة — الخطبة الأسبوعية من إمامنا'],
                ],
            ],
            [
                'block_type' => StaffBlock::class,
                'data' => ['limit' => 6],
            ],
            [
                'block_type' => KhutbaArchiveBlock::class,
                'data' => ['limit' => 5],
            ],
            [
                'block_type' => FaqBlock::class,
                'data' => [
                    'title' => ['en' => 'Frequently Asked Questions', 'ar' => 'الأسئلة الشائعة'],
                    'items' => [
                        [
                            'question' => ['en' => 'What are the mosque visiting hours?', 'ar' => 'ما هي ساعات زيارة المسجد؟'],
                            'answer' => ['en' => 'The mosque is open daily from Fajr prayer until Isha prayer. Administrative offices are open Monday through Friday from 9 AM to 5 PM.', 'ar' => 'المسجد مفتوح يومياً من صلاة الفجر حتى صلاة العشاء. المكاتب الإدارية مفتوحة من الاثنين إلى الجمعة من 9 صباحاً حتى 5 مساءً.'],
                        ],
                        [
                            'question' => ['en' => 'How can I register for Islamic classes?', 'ar' => 'كيف يمكنني التسجيل في الدروس الإسلامية؟'],
                            'answer' => ['en' => 'You can register for classes online through our website or in person at the mosque office. Classes are available for all age groups and knowledge levels.', 'ar' => 'يمكنك التسجيل في الدروس عبر الإنترنت من خلال موقعنا أو شخصياً في مكتب المسجد. الدروس متاحة لجميع الفئات العمرية ومستويات المعرفة.'],
                        ],
                        [
                            'question' => ['en' => 'Is there parking available?', 'ar' => 'هل يوجد موقف للسيارات؟'],
                            'answer' => ['en' => 'Yes, we have a dedicated parking lot with spaces for over 200 vehicles. Additional street parking is also available nearby.', 'ar' => 'نعم، لدينا موقف مخصص يتسع لأكثر من 200 سيارة. كما يتوفر موقف في الشارع بالقرب منا.'],
                        ],
                        [
                            'question' => ['en' => 'How can I become a volunteer?', 'ar' => 'كيف يمكنني أن أصبح متطوعاً؟'],
                            'answer' => ['en' => 'We welcome volunteers for various programs. Please contact our volunteer coordinator through the contact form or visit the mosque office to learn about current opportunities.', 'ar' => 'نرحب بالمتطوعين في برامج متنوعة. يرجى التواصل مع منسق المتطوعين عبر نموذج الاتصال أو زيارة مكتب المسجد للاستفسار عن الفرص المتاحة.'],
                        ],
                        [
                            'question' => ['en' => 'Do you offer services in languages other than English?', 'ar' => 'هل تقدمون خدمات بلغات أخرى غير الإنجليزية؟'],
                            'answer' => ['en' => 'Yes, we offer services and programs in Arabic, Urdu, and several other languages to serve our diverse community. Contact us for details.', 'ar' => 'نعم، نقدم خدمات وبرامج باللغة العربية والأردية وعدة لغات أخرى لخدمة مجتمعنا المتنوع. تواصل معنا للمزيد من التفاصيل.'],
                        ],
                    ],
                ],
            ],
            [
                'block_type' => CtaBlock::class,
                'data' => [
                    'title' => ['en' => 'Join Our Community', 'ar' => 'انضم إلى مجتمعنا'],
                    'description' => ['en' => 'Become part of our vibrant Muslim community. Attend our events, join our programs, and help us build a stronger, more connected ummah.', 'ar' => 'كن جزءاً من مجتمعنا الإسلامي المفعم بالحيوية. احضر فعالياتنا وانضم إلى برامجنا وساعدنا في بناء أمة أقوى وأكثر ترابطاً.'],
                    'button_text' => ['en' => 'Get Involved', 'ar' => 'شارك معنا'],
                    'button_url' => '/contact',
                    'style' => 'dark',
                ],
            ],
            [
                'block_type' => ContactMapBlock::class,
                'data' => [],
            ],
            [
                'block_type' => CustomHtmlBlock::class,
                'data' => [
                    'html' => '<div style="background: #f0fdf4; border-left: 4px solid #059669; padding: 1rem 1.5rem; border-radius: 0.5rem;"><p style="color: #065f46; font-weight: 600; margin: 0;">Custom HTML Block</p><p style="color: #047857; margin: 0.5rem 0 0;">This block allows embedding custom HTML content directly into the page.</p></div>',
                ],
            ],
        ];

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
}
