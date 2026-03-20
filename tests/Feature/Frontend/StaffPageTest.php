<?php

use App\Models\Staff;

it('lists only active staff members in sort order', function () {
    Staff::query()->create([
        'name' => [
            'en' => 'Early Staff',
            'ar' => 'عضو مبكر',
        ],
        'title' => [
            'en' => 'Front Desk',
            'ar' => 'الاستقبال',
        ],
        'bio' => [
            'en' => 'Available to help visitors.',
            'ar' => 'متاح لمساعدة الزوار.',
        ],
        'sort_order' => 0,
        'is_active' => true,
    ]);

    Staff::query()->create([
        'name' => [
            'en' => 'Hidden Staff',
            'ar' => 'عضو مخفي',
        ],
        'title' => [
            'en' => 'Hidden Role',
            'ar' => 'دور مخفي',
        ],
        'sort_order' => 99,
        'is_active' => false,
    ]);

    $this->get(route('staff.index'))
        ->assertOk()
        ->assertSeeTextInOrder(['Early Staff', 'Shaykh Ahmad Hassan'])
        ->assertDontSeeText('Hidden Staff');
});

it('renders seeded staff roles and biographies', function () {
    $this->get(route('staff.index'))
        ->assertOk()
        ->assertSeeText('Imam and Khateeb')
        ->assertSeeText('Leads daily prayers, Friday sermons, and weekly Quran circles for adults.');
});
