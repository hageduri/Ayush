<?php

namespace App\Filament\Resources\AhwcMemberResource\Pages;

use App\Filament\Resources\AhwcMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAhwcMember extends EditRecord
{
    protected static string $resource = AhwcMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
