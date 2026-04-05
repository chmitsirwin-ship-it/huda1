<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class AssetPath
{
    public static function normalize(?string $path): ?string
    {
        $path = trim(html_entity_decode((string) $path, ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        $sanitizedPath = iconv('UTF-8', 'UTF-8//IGNORE', $path);

        if ($sanitizedPath !== false) {
            $path = $sanitizedPath;
        }

        if ($path === '') {
            return null;
        }

        if (! str_starts_with($path, 'http://') && ! str_starts_with($path, 'https://')) {
            return ltrim($path, '/');
        }

        return ltrim((string) preg_replace('#^https?://[^/]+/#', '', $path), '/');
    }

    public static function url(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        return Storage::url($path);
    }
}
