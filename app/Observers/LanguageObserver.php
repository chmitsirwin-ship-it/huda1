<?php

namespace App\Observers;

use App\Models\Language;
use Illuminate\Support\Facades\Cache;

class LanguageObserver
{
    public function created(Language $language): void
    {
        Cache::forget('local');
    }

    public function updated(Language $language): void
    {
        Cache::forget('local');
    }

    public function deleted(Language $language): void
    {
        Cache::forget('local');
    }
}
