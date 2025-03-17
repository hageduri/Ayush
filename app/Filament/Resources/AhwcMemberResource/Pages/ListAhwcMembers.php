<?php

namespace App\Filament\Resources\AhwcMemberResource\Pages;

use App\Filament\Resources\AhwcMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAhwcMembers extends ListRecords
{
    protected static string $resource = AhwcMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
