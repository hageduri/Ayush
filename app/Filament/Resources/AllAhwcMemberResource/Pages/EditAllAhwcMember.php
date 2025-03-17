<?php

namespace App\Filament\Resources\AllAhwcMemberResource\Pages;

use App\Filament\Resources\AllAhwcMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAllAhwcMember extends EditRecord
{
    protected static string $resource = AllAhwcMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
