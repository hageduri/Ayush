<?php

namespace App\Filament\Resources\AllAhwcMemnberResource\Pages;

use App\Filament\Resources\AllAhwcMemnberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAllAhwcMemnber extends EditRecord
{
    protected static string $resource = AllAhwcMemnberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
