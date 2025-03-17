<?php

namespace App\Filament\Resources\DmoVerificationResource\Pages;

use App\Filament\Resources\DmoVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDmoVerification extends EditRecord
{
    protected static string $resource = DmoVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
