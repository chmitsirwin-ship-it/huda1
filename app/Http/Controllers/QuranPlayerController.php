<?php

namespace App\Http\Controllers;

use App\Services\Mp3Quran\Mp3QuranClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuranPlayerController extends Controller
{
    public function __construct(private readonly Mp3QuranClient $client) {}

    public function bootstrap(Request $request): JsonResponse
    {
        return response()->json($this->client->bootstrap($this->configFromRequest($request)));
    }

    public function reciters(Request $request): JsonResponse
    {
        return response()->json([
            'reciters' => $this->client->reciters([
                'language' => $request->string('language')->toString() ?: null,
                'reciter' => $request->integer('reciter') ?: null,
                'rewaya' => $request->integer('rewaya') ?: null,
                'sura' => $request->integer('sura') ?: null,
                'last_updated_date' => $request->string('last_updated_date')->toString() ?: null,
            ]),
        ]);
    }

    public function recentReads(Request $request): JsonResponse
    {
        return response()->json([
            'reads' => $this->client->recentReads(
                $request->string('language')->toString() ?: null,
                $request->integer('limit') ?: null,
            ),
        ]);
    }

    public function radios(Request $request): JsonResponse
    {
        return response()->json([
            'radios' => $this->client->radios(
                $request->string('language')->toString() ?: null,
                $request->integer('limit') ?: null,
            ),
        ]);
    }

    public function tafasir(Request $request): JsonResponse
    {
        return response()->json([
            'tafasir' => $this->client->tafasir($request->string('language')->toString() ?: null),
        ]);
    }

    public function tafsir(Request $request): JsonResponse
    {
        return response()->json([
            'tafsir' => $this->client->tafsir(
                $request->integer('tafsir'),
                $request->string('language')->toString() ?: null,
                $request->integer('sura') ?: null,
            ),
        ]);
    }

    public function tadabor(Request $request): JsonResponse
    {
        return response()->json([
            'tadabor' => $this->client->tadabor(
                $request->string('language')->toString() ?: null,
                $request->integer('sura') ?: null,
                $request->integer('limit') ?: null,
            ),
        ]);
    }

    public function videos(Request $request): JsonResponse
    {
        return response()->json([
            'videos' => $this->client->videos(
                $request->string('language')->toString() ?: null,
                $request->integer('limit') ?: null,
            ),
        ]);
    }

    public function liveTv(Request $request): JsonResponse
    {
        return response()->json([
            'livetv' => $this->client->liveTv($request->string('language')->toString() ?: null),
        ]);
    }

    public function timingReads(): JsonResponse
    {
        return response()->json($this->client->timingReads());
    }

    public function timingSoar(Request $request): JsonResponse
    {
        return response()->json($this->client->timingSoar($request->integer('read')));
    }

    public function ayatTiming(Request $request): JsonResponse
    {
        return response()->json(
            $this->client->ayatTiming($request->integer('read'), $request->integer('surah'))
        );
    }

    public function pageSvg(Request $request): Response
    {
        return response($this->client->fetchPageSvg($request->string('url')->toString()), 200, [
            'Content-Type' => 'image/svg+xml',
        ]);
    }

    private function configFromRequest(Request $request): array
    {
        return [
            'language' => $request->string('language')->toString() ?: null,
            'default_reciter_id' => $request->integer('default_reciter_id') ?: null,
            'default_riwayah_id' => $request->integer('default_riwayah_id') ?: null,
            'default_surah_id' => $request->integer('default_surah_id') ?: null,
        ];
    }
}
