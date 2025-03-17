<?php

namespace App\Filament\Resources\VerifyReportResource\Pages;

use App\Filament\Resources\VerifyReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVerifyReport extends EditRecord
{
    protected static string $resource = VerifyReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
