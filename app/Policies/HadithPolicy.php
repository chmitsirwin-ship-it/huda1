<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Hadith;
use Illuminate\Auth\Access\HandlesAuthorization;

class HadithPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Hadith');
    }

    public function view(AuthUser $authUser, Hadith $hadith): bool
    {
        return $authUser->can('View:Hadith');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Hadith');
    }

    public function update(AuthUser $authUser, Hadith $hadith): bool
    {
        return $authUser->can('Update:Hadith');
    }

    public function delete(AuthUser $authUser, Hadith $hadith): bool
    {
        return $authUser->can('Delete:Hadith');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Hadith');
    }

}