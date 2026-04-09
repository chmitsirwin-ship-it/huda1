<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\News;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:News');
    }

    public function view(AuthUser $authUser, News $news): bool
    {
        return $authUser->can('View:News');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:News');
    }

    public function update(AuthUser $authUser, News $news): bool
    {
        return $authUser->can('Update:News');
    }

    public function delete(AuthUser $authUser, News $news): bool
    {
        return $authUser->can('Delete:News');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:News');
    }

}