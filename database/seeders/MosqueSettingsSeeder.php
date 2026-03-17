<?php

namespace Database\Seeders;

use App\Models\MosqueSettings;
use Illuminate\Database\Seeder;

class MosqueSettingsSeeder extends Seeder
{
    public function run(): void
    {
        if (MosqueSettings::count() === 0) {
            MosqueSettings::create([
                'name' => ['en' => 'Al-Noor Mosque'],
                'description' => ['en' => 'Welcome to Al-Noor Mosque, a place of worship, learning, and community for Muslims.'],
                'address' => ['en' => '123 Main Street, City, Country'],
                'meta_title' => ['en' => 'Al-Noor Mosque'],
                'meta_description' => ['en' => 'Al-Noor Mosque — a welcoming community for Muslims.'],
                'latitude' => 40.7128,
                'longitude' => -74.0060,
                'phone' => '+1 (555) 000-0000',
                'email' => 'info@alnoor-mosque.com',
                'prayer_method' => 'auto',
                'calculation_method' => 2,
                'timezone' => 'America/New_York',
            ]);
        }
    }
}
