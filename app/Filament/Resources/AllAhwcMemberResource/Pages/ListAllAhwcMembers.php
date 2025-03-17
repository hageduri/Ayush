<?php

namespace App\Filament\Resources\AllAhwcMemberResource\Pages;

use App\Filament\Resources\AllAhwcMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAllAhwcMembers extends ListRecords
{
    protected static string $resource = AllAhwcMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
