<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Staff;
use Illuminate\Auth\Access\HandlesAuthorization;

class StaffPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Staff');
    }

    public function view(AuthUser $authUser, Staff $staff): bool
    {
        return $authUser->can('View:Staff');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Staff');
    }

    public function update(AuthUser $authUser, Staff $staff): bool
    {
        return $authUser->can('Update:Staff');
    }

    public function delete(AuthUser $authUser, Staff $staff): bool
    {
        return $authUser->can('Delete:Staff');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Staff');
    }

}