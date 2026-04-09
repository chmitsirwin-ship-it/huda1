<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ContactSubmission;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactSubmissionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ContactSubmission');
    }

    public function view(AuthUser $authUser, ContactSubmission $contactSubmission): bool
    {
        return $authUser->can('View:ContactSubmission');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ContactSubmission');
    }

    public function update(AuthUser $authUser, ContactSubmission $contactSubmission): bool
    {
        return $authUser->can('Update:ContactSubmission');
    }

    public function delete(AuthUser $authUser, ContactSubmission $contactSubmission): bool
    {
        return $authUser->can('Delete:ContactSubmission');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ContactSubmission');
    }

}