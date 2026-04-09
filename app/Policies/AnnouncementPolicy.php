<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Announcement;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnnouncementPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Announcement');
    }

    public function view(AuthUser $authUser, Announcement $announcement): bool
    {
        return $authUser->can('View:Announcement');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Announcement');
    }

    public function update(AuthUser $authUser, Announcement $announcement): bool
    {
        return $authUser->can('Update:Announcement');
    }

    public function delete(AuthUser $authUser, Announcement $announcement): bool
    {
        return $authUser->can('Delete:Announcement');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Announcement');
    }

}