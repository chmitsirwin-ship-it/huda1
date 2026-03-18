<?php

namespace App\Filament\Admin\Resources\ContactSubmissions\Pages;

use App\Enums\ContactSubmissionStatus;
use App\Filament\Admin\Resources\ContactSubmissions\ContactSubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContactSubmission extends EditRecord
{
    protected static string $resource = ContactSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateRecordDataUsing(array $data): array
    {
        if ($this->record->status === ContactSubmissionStatus::New) {
            $this->record->update([
                'status' => ContactSubmissionStatus::Read,
                'read_at' => now(),
            ]);

            $data['status'] = ContactSubmissionStatus::Read;
            $data['read_at'] = now();
        }

        return $data;
    }
}
