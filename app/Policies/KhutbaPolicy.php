<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Khutba;
use Illuminate\Auth\Access\HandlesAuthorization;

class KhutbaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Khutba');
    }

    public function view(AuthUser $authUser, Khutba $khutba): bool
    {
        return $authUser->can('View:Khutba');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Khutba');
    }

    public function update(AuthUser $authUser, Khutba $khutba): bool
    {
        return $authUser->can('Update:Khutba');
    }

    public function delete(AuthUser $authUser, Khutba $khutba): bool
    {
        return $authUser->can('Delete:Khutba');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Khutba');
    }

}