<?php

namespace App\Models;

use App\Enums\ContactSubmissionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContactSubmission extends Model
{
    protected function casts(): array
    {
        return [
            'status' => ContactSubmissionStatus::class,
            'read_at' => 'datetime',
        ];
    }

    public function scopeUnread(Builder $query): void
    {
        $query->where('status', ContactSubmissionStatus::New);
    }
}
