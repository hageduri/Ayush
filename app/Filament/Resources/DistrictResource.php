<?php

namespace App\Filament\Resources;

use App\Filament\Exports\DistrictExporter;
use App\Filament\Resources\DistrictResource\Pages;
use App\Filament\Resources\DistrictResource\RelationManagers;
use App\Models\District;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DistrictResource extends Resource
{
    protected static ?string $model = District::class;

    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('district_name')
                ->label('District Name')
                ->required()
                ->dehydrateStateUsing(fn ($state)=>strtoupper($state)),


                // ->mutateAfterFillUsing(fn ($value)=>strtoupper($value)),

                TextInput::make('district_code')
                ->required()
                ->numeric()


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([



                    TextColumn::make('district_name')
                        ->searchable()
                        ->sortable()
                        ->weight('bold'),
                    // TextColumn::make('district_code')
                    //     ->sortable()


            ])
            ->filters([

            ])
            ->headerActions([

                    ExportAction::make()->exporter(DistrictExporter::class),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDistricts::route('/'),
            'create' => Pages\CreateDistrict::route('/create'),
            'edit' => Pages\EditDistrict::route('/{record}/edit'),
        ];
    }
}
