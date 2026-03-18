<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ContactSubmissionStatus: string implements HasColor, HasLabel
{
    case New = 'new';
    case Read = 'read';
    case Replied = 'replied';
    case Archived = 'archived';

    public function getLabel(): string
    {
        return match ($this) {
            self::New => __('New'),
            self::Read => __('Read'),
            self::Replied => __('Replied'),
            self::Archived => __('Archived'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::New => 'info',
            self::Read => 'warning',
            self::Replied => 'success',
            self::Archived => 'gray',
        };
    }
}
