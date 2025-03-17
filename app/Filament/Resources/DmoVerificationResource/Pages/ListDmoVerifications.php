<?php

namespace App\Filament\Resources\DmoVerificationResource\Pages;

use App\Filament\Resources\DmoVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDmoVerifications extends ListRecords
{
    protected static string $resource = DmoVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
