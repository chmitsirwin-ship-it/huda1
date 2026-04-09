<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Page;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Page');
    }

    public function view(AuthUser $authUser, Page $page): bool
    {
        return $authUser->can('View:Page');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Page');
    }

    public function update(AuthUser $authUser, Page $page): bool
    {
        return $authUser->can('Update:Page');
    }

    public function delete(AuthUser $authUser, Page $page): bool
    {
        return $authUser->can('Delete:Page');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Page');
    }

}