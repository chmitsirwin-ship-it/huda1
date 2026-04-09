<?php

namespace App\Support;

class PublicNavigation
{
    public static function sections(): array
    {
        return [
            'prayer_times' => __('Prayer Times'),
            'events' => __('Events'),
            'announcements' => __('Announcements'),
            'news' => __('News'),
            'gallery' => __('Gallery'),
            'library' => __('Library'),
            'khutba' => __('Khutba'),
            'staff' => __('Staff'),
            'contact' => __('Contact'),
        ];
    }

    public static function isEnabled(string $section): bool
    {
        return (bool) setting(self::settingKey($section), true);
    }

    public static function settingKey(string $section): string
    {
        return "navigation.{$section}";
    }
}
