<?php

namespace App\Filament\Exports;

use App\Models\District;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class DistrictExporter extends Exporter
{
    protected static ?string $model = District::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('district_name'),// add which column you need
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your district export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
