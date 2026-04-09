<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MediaItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class MediaItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MediaItem');
    }

    public function view(AuthUser $authUser, MediaItem $mediaItem): bool
    {
        return $authUser->can('View:MediaItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MediaItem');
    }

    public function update(AuthUser $authUser, MediaItem $mediaItem): bool
    {
        return $authUser->can('Update:MediaItem');
    }

    public function delete(AuthUser $authUser, MediaItem $mediaItem): bool
    {
        return $authUser->can('Delete:MediaItem');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MediaItem');
    }

}