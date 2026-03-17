<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MediaType: string implements HasLabel
{
    case Image = 'image';
    case Video = 'video';

    public function getLabel(): string
    {
        return match ($this) {
            self::Image => __('Image'),
            self::Video => __('Video'),
        };
    }
}
