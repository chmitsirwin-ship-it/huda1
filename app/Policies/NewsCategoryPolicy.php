<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\NewsCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:NewsCategory');
    }

    public function view(AuthUser $authUser, NewsCategory $newsCategory): bool
    {
        return $authUser->can('View:NewsCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:NewsCategory');
    }

    public function update(AuthUser $authUser, NewsCategory $newsCategory): bool
    {
        return $authUser->can('Update:NewsCategory');
    }

    public function delete(AuthUser $authUser, NewsCategory $newsCategory): bool
    {
        return $authUser->can('Delete:NewsCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:NewsCategory');
    }

}