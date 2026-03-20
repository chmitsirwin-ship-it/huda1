<?php

namespace Database\Seeders\Support;

final class MosqueDemoPageData
{
    public static function definitions(string $image, ?int $featuredVerseId, ?int $featuredHadithId): array
    {
        return [
            [
                'slug' => 'home',
                'attributes' => [
                    'title' => [
                        'en' => 'Home',
                        'ar' => 'الرئيسية',
                    ],
                    'meta_title' => [
                        'en' => 'Masjid Al-Huda',
                        'ar' => 'مسجد الهدى',
                    ],
                    'meta_description' => [
                        'en' => 'Welcome to Masjid Al-Huda.',
                        'ar' => 'مرحباً بكم في مسجد الهدى.',
                    ],
                    'is_published' => true,
                    'show_in_nav' => false,
                    'sort_order' => 0,
                ],
                'blocks' => [
                    [
                        'block_type' => \App\Filament\Admin\Blocks\HeroBlock::class,
                        'data' => [
                            'heading' => [
                                'en' => 'Explore the full content library',
                                'ar' => 'استكشف مكتبة المحتوى الكاملة',
                            ],
                            'subheading' => [
                                'en' => 'A showcase of the homepage blocks and public content modules.',
                                'ar' => 'عرض شامل لبلوكات الصفحة الرئيسية ووحدات المحتوى العامة.',
                            ],
                            'button_text' => [
                                'en' => 'Browse Events',
                                'ar' => 'تصفح الفعاليات',
                            ],
                            'button_url' => '/events',
                            'background_image' => $image,
                        ],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\CounterBlock::class,
                        'data' => [
                            'title' => [
                                'en' => 'Our Community in Numbers',
                                'ar' => 'مجتمعنا بالأرقام',
                            ],
                            'counters' => [
                                [
                                    'value' => '2,400+',
                                    'label' => [
                                        'en' => 'Regular worshippers',
                                        'ar' => 'مصلون منتظمون',
                                    ],
                                ],
                                [
                                    'value' => '32',
                                    'label' => [
                                        'en' => 'Weekly classes',
                                        'ar' => 'دروس أسبوعية',
                                    ],
                                ],
                                [
                                    'value' => '180',
                                    'label' => [
                                        'en' => 'Volunteer hours each month',
                                        'ar' => 'ساعة تطوع شهرياً',
                                    ],
                                ],
                                [
                                    'value' => '360',
                                    'label' => [
                                        'en' => 'Years',
                                        'ar' => 'سنة',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\SliderBlock::class,
                        'data' => ['limit' => 3, 'autoplay' => '1'],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\PrayerTimesBlock::class,
                        'data' => ['style' => 'card'],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\EventsBlock::class,
                        'data' => ['style' => 'list', 'limit' => 3],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\AnnouncementsBlock::class,
                        'data' => ['limit' => 3],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\RichTextBlock::class,
                        'data' => [
                            'content' => [
                                'en' => '<h2>About the mosque</h2><p>We welcome worshippers, neighbours, and visitors with a focus on clarity, compassion, and service.</p>',
                                'ar' => '<h2>عن المسجد</h2><p>نرحب بالمصلين والجيران والزوار بروح من الوضوح والرحمة والخدمة.</p>',
                            ],
                        ],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\QuranVerseBlock::class,
                        'data' => ['verse_id' => $featuredVerseId],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\HadithBlock::class,
                        'data' => ['hadith_id' => $featuredHadithId],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\TestimonialBlock::class,
                        'data' => [
                            'title' => [
                                'en' => 'What our community says',
                                'ar' => 'ماذا يقول مجتمعنا',
                            ],
                            'testimonials' => [
                                [
                                    'name' => [
                                        'en' => 'Amina Yusuf',
                                        'ar' => 'أمينة يوسف',
                                    ],
                                    'role' => [
                                        'en' => 'Parent',
                                        'ar' => 'أم',
                                    ],
                                    'quote' => [
                                        'en' => 'The mosque feels organised, welcoming, and deeply rooted in service.',
                                        'ar' => 'المسجد منظم ومرحّب ويعمل بروح خدمة واضحة ومؤثرة.',
                                    ],
                                ],
                                [
                                    'name' => [
                                        'en' => 'Mahmoud Ali',
                                        'ar' => 'محمود علي',
                                    ],
                                    'role' => [
                                        'en' => 'Volunteer',
                                        'ar' => 'متطوع',
                                    ],
                                    'quote' => [
                                        'en' => 'There is always a meaningful way to help, learn, and stay connected.',
                                        'ar' => 'هناك دائماً فرصة نافعة للعطاء والتعلّم والبقاء على صلة بالمجتمع.',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\DonationBlock::class,
                        'data' => [
                            'title' => [
                                'en' => 'Support our mosque',
                                'ar' => 'ادعم مسجدنا',
                            ],
                            'description' => [
                                'en' => 'Your contribution supports prayer spaces, classes, outreach, and family care.',
                                'ar' => 'يساهم تبرعك في دعم مرافق الصلاة والدروس والمبادرات المجتمعية وخدمة الأسر.',
                            ],
                            'button_text' => [
                                'en' => 'Contact us to contribute',
                                'ar' => 'تواصل معنا للمساهمة',
                            ],
                            'button_url' => '/contact',
                        ],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\GalleryBlock::class,
                        'data' => ['limit' => 8],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\VideoBlock::class,
                        'data' => [
                            'video_url' => 'https://www.youtube.com/watch?v=vTIYqaj0xGk',
                            'caption' => [
                                'en' => 'A sample community update video.',
                                'ar' => 'فيديو تجريبي لتحديثات المجتمع.',
                            ],
                        ],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\StaffBlock::class,
                        'data' => ['limit' => 4, 'style' => 'grid'],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\KhutbaArchiveBlock::class,
                        'data' => ['limit' => 2, 'style' => 'list'],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\FaqBlock::class,
                        'data' => [
                            'title' => [
                                'en' => 'Frequently asked questions',
                                'ar' => 'الأسئلة الشائعة',
                            ],
                            'items' => [
                                [
                                    'question' => [
                                        'en' => 'When is the mosque open?',
                                        'ar' => 'متى يكون المسجد مفتوحاً؟',
                                    ],
                                    'answer' => [
                                        'en' => 'The mosque is open daily from Fajr until after Isha, with office hours in the morning and late afternoon.',
                                        'ar' => 'المسجد مفتوح يومياً من الفجر حتى بعد العشاء، مع ساعات عمل إدارية صباحاً وقبل المغرب.',
                                    ],
                                ],
                                [
                                    'question' => [
                                        'en' => 'How do I join a class?',
                                        'ar' => 'كيف أنضم إلى أحد الدروس؟',
                                    ],
                                    'answer' => [
                                        'en' => 'Use the contact form or speak to the education coordinator after the weekly classes.',
                                        'ar' => 'يمكنك استخدام نموذج التواصل أو التحدث إلى منسقة التعليم بعد الدروس الأسبوعية.',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\CtaBlock::class,
                        'data' => [
                            'title' => [
                                'en' => 'Join our community',
                                'ar' => 'انضم إلى مجتمعنا',
                            ],
                            'description' => [
                                'en' => 'Pray with us, volunteer with us, and grow with us.',
                                'ar' => 'صل معنا، وتطوع معنا، وانمُ معنا.',
                            ],
                            'button_text' => [
                                'en' => 'Get in touch',
                                'ar' => 'تواصل معنا',
                            ],
                            'button_url' => '/contact',
                            'style' => 'dark',
                        ],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\ContactMapBlock::class,
                        'data' => [
                            'latitude' => 30.0596185,
                            'longitude' => 31.3260284,
                            'zoom' => 15,
                        ],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\CustomHtmlBlock::class,
                        'data' => [
                            'html' => '<div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-center"><strong>Community note:</strong> This demo page is seeded to exercise every public block.</div>',
                        ],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\ContactFormBlock::class,
                        'data' => [
                            'title' => [
                                'en' => 'Reach the mosque team',
                                'ar' => 'تواصل مع فريق المسجد',
                            ],
                            'description' => [
                                'en' => 'Questions, volunteering, and class registration all start here.',
                                'ar' => 'هنا تبدأ الاستفسارات والتطوع والتسجيل في البرامج.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'slug' => 'community-services',
                'attributes' => [
                    'title' => [
                        'en' => 'Community Services',
                        'ar' => 'خدمات المجتمع',
                    ],
                    'meta_title' => [
                        'en' => 'Community Services | Masjid Al-Huda',
                        'ar' => 'خدمات المجتمع | مسجد الهدى',
                    ],
                    'meta_description' => [
                        'en' => 'Support, referrals, and practical care offered through Masjid Al-Huda.',
                        'ar' => 'الدعم والإحالات والرعاية العملية التي يقدمها مسجد الهدى.',
                    ],
                    'is_published' => true,
                    'show_in_nav' => true,
                    'sort_order' => 1,
                ],
                'blocks' => [
                    [
                        'block_type' => \App\Filament\Admin\Blocks\HeroBlock::class,
                        'data' => [
                            'heading' => [
                                'en' => 'Serving people with dignity',
                                'ar' => 'نخدم الناس بكرامة',
                            ],
                            'subheading' => [
                                'en' => 'Food relief, family referrals, and volunteer-led support built around local needs.',
                                'ar' => 'إغاثة غذائية وإحالات أسرية ودعم يقوده المتطوعون وفق احتياجات المجتمع المحلي.',
                            ],
                            'button_text' => [
                                'en' => 'Contact the team',
                                'ar' => 'تواصل مع الفريق',
                            ],
                            'button_url' => '/contact',
                            'background_image' => $image,
                        ],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\RichTextBlock::class,
                        'data' => [
                            'content' => [
                                'en' => '<h2>Practical support for families</h2><p>Masjid Al-Huda coordinates food support, zakat referrals, visitor guidance, and crisis signposting with confidentiality and care.</p><p>Requests are reviewed by the mosque team and routed to the right volunteer or staff contact.</p>',
                                'ar' => '<h2>دعم عملي للأسر</h2><p>ينسق مسجد الهدى المساعدات الغذائية وإحالات الزكاة وإرشاد الزوار والدعم الأولي للحالات الطارئة بسرية وعناية.</p><p>تُراجع الطلبات من قبل فريق المسجد وتحوّل إلى الجهة المناسبة من الموظفين أو المتطوعين.</p>',
                            ],
                        ],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\StaffBlock::class,
                        'data' => ['limit' => 3, 'style' => 'grid'],
                    ],
                    [
                        'block_type' => \App\Filament\Admin\Blocks\ContactFormBlock::class,
                        'data' => [
                            'title' => [
                                'en' => 'Request support or ask a question',
                                'ar' => 'اطلب المساعدة أو اطرح سؤالك',
                            ],
                            'description' => [
                                'en' => 'Use this form for welfare support, referrals, volunteering, or service coordination.',
                                'ar' => 'استخدم هذا النموذج لطلبات الدعم والإحالات والتطوع وتنسيق الخدمات.',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
