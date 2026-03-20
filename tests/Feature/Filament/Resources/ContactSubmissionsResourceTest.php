<?php

use App\Enums\ContactSubmissionStatus;
use App\Filament\Admin\Resources\ContactSubmissions\ContactSubmissionResource;
use App\Models\ContactSubmission;

it('protects the contact submission resource pages', function () {
    $submission = ContactSubmission::query()->firstOrFail();

    assertGuestIsRedirectedFrom(adminPageUrls(ContactSubmissionResource::class, $submission, false));
});

it('lets admins open contact submission resource pages and see seeded messages', function () {
    $submission = ContactSubmission::query()->firstOrFail();

    assertAdminCanOpen(adminPageUrls(ContactSubmissionResource::class, $submission, false));

    $this->followingRedirects()
        ->get(ContactSubmissionResource::getUrl('index'))
        ->assertOk()
        ->assertSeeText($submission->name)
        ->assertSeeText($submission->subject);
});

it('marks a new contact submission as read when an admin opens it', function () {
    loginAsAdmin();

    $submission = ContactSubmission::query()
        ->where('status', ContactSubmissionStatus::New)
        ->firstOrFail();

    $this->followingRedirects()
        ->get(ContactSubmissionResource::getUrl('edit', ['record' => $submission]))
        ->assertOk();

    $submission->refresh();

    expect($submission->status)->toBe(ContactSubmissionStatus::Read)
        ->and($submission->read_at)->not()->toBeNull();
});
