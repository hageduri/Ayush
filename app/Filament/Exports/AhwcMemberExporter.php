<?php

namespace App\Filament\Exports;

use App\Models\AhwcMember;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AhwcMemberExporter extends Exporter
{
    protected static ?string $model = AhwcMember::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name'),
            ExportColumn::make('email'),
            ExportColumn::make('nin'),
            ExportColumn::make('facility.district.district_name'),
            ExportColumn::make('gender'),
            ExportColumn::make('role')->label('Designation'),
            ExportColumn::make('contact_1')->label('Contact'),
            ExportColumn::make('bank_name'),
            ExportColumn::make('account_no'),
            ExportColumn::make('ifsc_code'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your ahwc member export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
