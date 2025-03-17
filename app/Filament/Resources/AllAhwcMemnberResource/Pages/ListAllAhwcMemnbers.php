<?php

namespace App\Filament\Resources\AllAhwcMemnberResource\Pages;

use App\Filament\Resources\AllAhwcMemnberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllAhwcMemnbers extends ListRecords
{
    protected static string $resource = AllAhwcMemnberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
