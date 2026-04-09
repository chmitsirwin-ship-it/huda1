<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PrayerTime;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrayerTimePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PrayerTime');
    }

    public function view(AuthUser $authUser, PrayerTime $prayerTime): bool
    {
        return $authUser->can('View:PrayerTime');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PrayerTime');
    }

    public function update(AuthUser $authUser, PrayerTime $prayerTime): bool
    {
        return $authUser->can('Update:PrayerTime');
    }

    public function delete(AuthUser $authUser, PrayerTime $prayerTime): bool
    {
        return $authUser->can('Delete:PrayerTime');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PrayerTime');
    }

}