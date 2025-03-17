<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AllDmoResource\Pages;
use App\Filament\Resources\AllDmoResource\RelationManagers;
use App\Models\AllDmo;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AllDmoResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'All DMO';
    public static function canViewAny(): bool
    {
        return Auth::user()->can('viewAnyDmo', User::class);
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Split::make([
                TextColumn::make('name')->searchable()->sortable()->weight(FontWeight::Bold),


                TextColumn::make('district.district_name'),

                TextColumn::make('email'),
                TextColumn::make('contact_1'),
                TextColumn::make('role')->label('Designation'),
            ])->from('md'),



        ])
        ->modifyQueryUsing(fn (Builder $query) => $query
                    ->select('users.*', 'districts.district_name')
                    ->join('districts', 'users.district_code', '=', 'districts.district_code') // Ensure district is joined
                    ->where('users.role', 'DMO')


            )
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
            'index' => Pages\ListAllDmos::route('/'),
            'create' => Pages\CreateAllDmo::route('/create'),
            'edit' => Pages\EditAllDmo::route('/{record}/edit'),
        ];
    }
}
