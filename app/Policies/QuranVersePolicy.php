<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\QuranVerse;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuranVersePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:QuranVerse');
    }

    public function view(AuthUser $authUser, QuranVerse $quranVerse): bool
    {
        return $authUser->can('View:QuranVerse');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:QuranVerse');
    }

    public function update(AuthUser $authUser, QuranVerse $quranVerse): bool
    {
        return $authUser->can('Update:QuranVerse');
    }

    public function delete(AuthUser $authUser, QuranVerse $quranVerse): bool
    {
        return $authUser->can('Delete:QuranVerse');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:QuranVerse');
    }

}