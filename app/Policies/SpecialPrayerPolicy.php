<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SpecialPrayer;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecialPrayerPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SpecialPrayer');
    }

    public function view(AuthUser $authUser, SpecialPrayer $specialPrayer): bool
    {
        return $authUser->can('View:SpecialPrayer');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SpecialPrayer');
    }

    public function update(AuthUser $authUser, SpecialPrayer $specialPrayer): bool
    {
        return $authUser->can('Update:SpecialPrayer');
    }

    public function delete(AuthUser $authUser, SpecialPrayer $specialPrayer): bool
    {
        return $authUser->can('Delete:SpecialPrayer');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SpecialPrayer');
    }

}