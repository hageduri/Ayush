<?php

namespace App\Filament\Resources\VerifyReportResource\Pages;

use App\Filament\Resources\VerifyReportResource;
use App\Filament\Widgets\StatusOverview;
use App\Models\Indicator;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ListVerifyReports extends ListRecords
{
    protected static string $resource = VerifyReportResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make(),
        ];
    }
    // protected function getHeaderWidgets(): array
    // {
    //     return [
    //     ];
    // }


}
