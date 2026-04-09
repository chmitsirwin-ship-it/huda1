<?php

declare(strict_types=1);
use App\Models\Event;
use App\Models\Khutba;
use App\Models\News;
use App\Models\Page;
use MuhammadNawlo\FilamentSitemapGenerator\Models\SitemapRun;

return [
    'path' => public_path('sitemap.xml'),

    'output' => [
        'mode' => 'file',
        'file_path' => public_path('sitemap.xml'),
        'disk' => 'public',
        'disk_path' => 'sitemap.xml',
        'visibility' => 'public',
    ],

    'sitemap_run_model' => SitemapRun::class,

    'sitemap_runs_table' => 'sitemap_runs',

    'sitemap_settings_table' => 'sitemap_settings',

    'chunk_size' => 500,

    'max_urls_per_file' => 50000,

    'base_url' => null,

    'static_urls' => [
        [
            'url' => '/',
            'priority' => 1.0,
            'changefreq' => 'daily',
        ],
        [
            'url' => '/prayer-times',
            'priority' => 0.9,
            'changefreq' => 'daily',
        ],
        [
            'url' => '/events',
            'priority' => 0.8,
            'changefreq' => 'weekly',
        ],
        [
            'url' => '/announcements',
            'priority' => 0.8,
            'changefreq' => 'weekly',
        ],
        [
            'url' => '/news',
            'priority' => 0.8,
            'changefreq' => 'weekly',
        ],
        [
            'url' => '/gallery',
            'priority' => 0.7,
            'changefreq' => 'weekly',
        ],
        [
            'url' => '/islamic-library',
            'priority' => 0.7,
            'changefreq' => 'weekly',
        ],
        [
            'url' => '/khutba',
            'priority' => 0.7,
            'changefreq' => 'weekly',
        ],
        [
            'url' => '/staff',
            'priority' => 0.5,
            'changefreq' => 'monthly',
        ],
        [
            'url' => '/contact',
            'priority' => 0.5,
            'changefreq' => 'monthly',
        ],
    ],

    'models' => [
        Page::class => [
            'priority' => 0.8,
            'changefreq' => 'weekly',
        ],
        News::class => [
            'priority' => 0.7,
            'changefreq' => 'weekly',
        ],
        Event::class => [
            'priority' => 0.7,
            'changefreq' => 'weekly',
        ],
        Khutba::class => [
            'priority' => 0.6,
            'changefreq' => 'weekly',
        ],
    ],

    'schedule' => [
        'enabled' => true,
        'frequency' => 'daily',
    ],

    'queue' => [
        'enabled' => false,
        'connection' => null,
        'queue' => null,
    ],

    'news' => [
        'enabled' => false,
        'publication_name' => null,
        'publication_language' => 'en',
        'models' => [],
    ],

    'ping_search_engines' => [
        'enabled' => false,
        'engines' => [
            'google',
            'bing',
        ],
    ],

    'crawl' => [
        'enabled' => false,
        'url' => null,
        'concurrency' => 10,
        'max_count' => null,
        'max_tags_per_sitemap' => 50000,
        'exclude_patterns' => [],

        'maximum_depth' => null,
        'crawl_profile' => null,
        'should_crawl' => null,
        'has_crawled' => null,

        'execute_javascript' => false,
        'chrome_binary_path' => null,
        'node_binary_path' => null,
    ],
];
