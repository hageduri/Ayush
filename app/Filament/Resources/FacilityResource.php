<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Filament\Resources\FacilityResource\RelationManagers;
use App\Models\District;
use App\Models\Facility;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Tabs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;
    protected static ?string $label = 'facility';
    public static function canViewAny(): bool
    {
        return Auth::user()->can('viewAny', Facility::class);
    }

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    // public static function canViewOwn():bool
    // {
    //     return Auth::user()->can('viewAny', User::class);
    // }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nin')
                ->label('National Identification no.')
                ->numeric()
                ->required(),

                TextInput::make('name')
                ->label('Facility Name')
                ->required()
                ->dehydrateStateUsing(fn ($state)=>strtoupper($state)),

                Select::make('facility_type')
                ->label('Facility Type')
                ->options([
                    'Dispensary' => 'Dispensary',
                    'PHC' => 'PHC',
                    'Sub Center' => 'Subcenter'
                ]),

                Select::make('district_code')
                ->label('District')
                ->required()
                ->options(
                    District::all()->pluck('district_name', 'district_code')->toArray()
                ),

                TextInput::make('block_name')
                ->required()
                ->dehydrateStateUsing(fn ($state)=>strtoupper($state)),




                //from db
            ]);
    }

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
            'index' => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacility::route('/create'),
            'edit' => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}
