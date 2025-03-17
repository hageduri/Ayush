<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AllFacilityResource\Pages;
use App\Filament\Resources\AllFacilityResource\RelationManagers;
use App\Models\AllFacility;
use App\Models\District;
use App\Models\Facility;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AllFacilityResource extends Resource
{
    protected static ?string $model = Facility::class;
    protected static ?string $label = 'DMO Facility';

    public static function canViewAny(): bool
    {
        return Auth::user()->can('viewAnyAll', Facility::class);
    }
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             TextInput::make('nin')
    //             ->label('National Identification no.')
    //             ->numeric()
    //             ->required(),

    //             TextInput::make('name')
    //             ->label('Facility Name')
    //             ->required()
    //             ->dehydrateStateUsing(fn ($state)=>strtoupper($state)),

    //             Select::make('facility_type')
    //             ->label('Facility Type')
    //             ->options([
    //                 'Dispensary' => 'Dispensary',
    //                 'PHC' => 'PHC',
    //                 'Sub Center' => 'Subcenter'
    //             ]),

    //             Select::make('district_code')
    //             ->label('District')
    //             ->required()
    //             ->options(
    //                 District::all()->pluck('district_name', 'district_code')->toArray()
    //             ),

    //             TextInput::make('block_name')
    //             ->required()
    //             ->dehydrateStateUsing(fn ($state)=>strtoupper($state)),




    //             //from db
    //         ]);
    // }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('district.district_name'),
                TextColumn::make('nin'),
                TextColumn::make('name'),
                TextColumn::make('block_name'),
                TextColumn::make('user.name')->label('Incharge'),
                TextColumn::make('status')->default(''),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                // Filter by the DMO's district
                return $query->whereHas('district', function ($query) {
                    $query->where('district_code', Auth::user()->district_code);
                });
            })
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
            'index' => Pages\ListAllFacilities::route('/'),
            'create' => Pages\CreateAllFacility::route('/create'),
            'edit' => Pages\EditAllFacility::route('/{record}/edit'),
        ];
    }
}
