<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

it('returns normalized quran player bootstrap data', function () {
    Http::fake([
        'mp3quran.net/api/v3/languages*' => Http::response([
            'language' => [
                ['id' => 1, 'language' => 'English', 'native' => 'English'],
            ],
        ]),
        'mp3quran.net/api/v3/suwar*' => Http::response([
            'suwar' => [
                ['id' => 1, 'name' => 'Al-Fatihah', 'start_page' => 1, 'end_page' => 1, 'makkia' => 1, 'type' => 0],
            ],
        ]),
        'mp3quran.net/api/v3/riwayat*' => Http::response([
            'riwayat' => [
                ['id' => 116, 'name' => 'Hafs'],
            ],
        ]),
        'mp3quran.net/api/v3/moshaf*' => Http::response([
            'moshaf' => [
                ['id' => 116, 'name' => 'Murattal'],
            ],
        ]),
        'mp3quran.net/api/v3/reciters*' => Http::response([
            'reciters' => [
                [
                    'id' => 1,
                    'name' => 'Test Reciter',
                    'letter' => 'T',
                    'moshaf' => [
                        [
                            'id' => 1,
                            'name' => 'Test Murattal',
                            'server' => 'https://server6.mp3quran.net/test/',
                            'surah_total' => 1,
                            'moshaf_type' => 116,
                            'surah_list' => '1',
                        ],
                    ],
                ],
            ],
        ]),
        'mp3quran.net/api/v3/ayat_timing/reads*' => Http::response([
            [
                'id' => 5,
                'name' => 'Timing Reader',
                'rewaya' => 'Hafs',
                'folder_url' => 'https://server6.mp3quran.net/test/',
                'soar_count' => 1,
                'soar_link' => 'https://mp3quran.net/api/v3/ayat_timing/soar?read=5',
            ],
        ]),
    ]);

    $this->getJson(route('quran-player.bootstrap', [
        'language' => 'en',
        'default_reciter_id' => 1,
        'default_surah_id' => 1,
    ]))
        ->assertOk()
        ->assertJsonPath('meta.language', 'eng')
        ->assertJsonPath('catalogs.suwar.0.name', 'Al-Fatihah')
        ->assertJsonPath('data.reciters.0.moshaf.0.surahs.0.audio_url', 'https://server6.mp3quran.net/test/001.mp3')
        ->assertJsonPath('data.reciters.0.moshaf.0.timing_read_id', 5);
});

it('falls back to stale cached quran player data when the upstream request fails', function () {
    Http::fake([
        'mp3quran.net/api/v3/reciters*' => Http::response([
            'reciters' => [
                [
                    'id' => 1,
                    'name' => 'Cached Reciter',
                    'letter' => 'C',
                    'moshaf' => [
                        [
                            'id' => 1,
                            'name' => 'Cached Murattal',
                            'server' => 'https://server6.mp3quran.net/cached/',
                            'surah_total' => 1,
                            'moshaf_type' => 116,
                            'surah_list' => '1',
                        ],
                    ],
                ],
            ],
        ]),
        'mp3quran.net/api/v3/suwar*' => Http::response([
            'suwar' => [
                ['id' => 1, 'name' => 'Al-Fatihah', 'start_page' => 1, 'end_page' => 1, 'makkia' => 1, 'type' => 0],
            ],
        ]),
        'mp3quran.net/api/v3/riwayat*' => Http::response([
            'riwayat' => [
                ['id' => 116, 'name' => 'Hafs'],
            ],
        ]),
        'mp3quran.net/api/v3/moshaf*' => Http::response([
            'moshaf' => [
                ['id' => 116, 'name' => 'Murattal'],
            ],
        ]),
        'mp3quran.net/api/v3/ayat_timing/reads*' => Http::response([]),
    ]);

    $this->getJson(route('quran-player.bootstrap', ['language' => 'en']))
        ->assertOk()
        ->assertJsonPath('data.reciters.0.name', 'Cached Reciter');

    Http::fake([
        'mp3quran.net/api/v3/reciters*' => Http::response(['message' => 'Server error'], 500),
        'mp3quran.net/api/v3/suwar*' => Http::response(['message' => 'Server error'], 500),
        'mp3quran.net/api/v3/riwayat*' => Http::response(['message' => 'Server error'], 500),
        'mp3quran.net/api/v3/moshaf*' => Http::response(['message' => 'Server error'], 500),
        'mp3quran.net/api/v3/ayat_timing/reads*' => Http::response(['message' => 'Server error'], 500),
    ]);

    $this->getJson(route('quran-player.bootstrap', ['language' => 'en']))
        ->assertOk()
        ->assertJsonPath('data.reciters.0.name', 'Cached Reciter');
});
