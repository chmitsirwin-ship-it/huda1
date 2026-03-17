<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AnnouncementType: string implements HasColor, HasLabel
{
    case General = 'general';
    case Urgent = 'urgent';
    case Maintenance = 'maintenance';

    public function getLabel(): string
    {
        return match ($this) {
            self::General => __('General'),
            self::Urgent => __('Urgent'),
            self::Maintenance => __('Maintenance'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::General => 'info',
            self::Urgent => 'danger',
            self::Maintenance => 'warning',
        };
    }
}
