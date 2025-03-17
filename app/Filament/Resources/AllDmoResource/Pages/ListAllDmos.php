<?php

namespace App\Filament\Resources\AllDmoResource\Pages;

use App\Filament\Resources\AllDmoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllDmos extends ListRecords
{
    protected static string $resource = AllDmoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
