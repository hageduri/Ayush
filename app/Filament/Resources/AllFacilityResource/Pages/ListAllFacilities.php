<?php

namespace App\Filament\Resources\AllFacilityResource\Pages;

use App\Filament\Resources\AllFacilityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllFacilities extends ListRecords
{
    protected static string $resource = AllFacilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
