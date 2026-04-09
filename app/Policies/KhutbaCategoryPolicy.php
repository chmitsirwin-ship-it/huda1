<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\KhutbaCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class KhutbaCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:KhutbaCategory');
    }

    public function view(AuthUser $authUser, KhutbaCategory $khutbaCategory): bool
    {
        return $authUser->can('View:KhutbaCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:KhutbaCategory');
    }

    public function update(AuthUser $authUser, KhutbaCategory $khutbaCategory): bool
    {
        return $authUser->can('Update:KhutbaCategory');
    }

    public function delete(AuthUser $authUser, KhutbaCategory $khutbaCategory): bool
    {
        return $authUser->can('Delete:KhutbaCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:KhutbaCategory');
    }

}