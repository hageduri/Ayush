<?php

namespace App\Filament\Resources;

use App\Filament\Exports\AhwcMemberExporter;
use App\Filament\Resources\AllAhwcMemberResource\Pages;
use App\Filament\Resources\AllAhwcMemberResource\RelationManagers;
use App\Models\AhwcMember;
use App\Models\AllAhwcMember;
use App\Models\District;
use Filament\Forms;
use Filament\Forms\Components\Split;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AllAhwcMemberResource extends Resource
{
    protected static ?string $model = AhwcMember::class;

    public static function canViewAny(): bool
    {
        return Auth::user()->can('viewAnyAll', AhwcMember::class);
    }

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

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
                // Tables\Columns\Layout\Split::make([
                    // Stack::make([
                        TextColumn::make('name')->searchable()->sortable()->weight(FontWeight::Bold),
                        // TextColumn::make('email'),
                    // ])->visibleFrom('md'),

                    TextColumn::make('nin')->weight(FontWeight::Bold),
                    TextColumn::make('facility.district.district_name')
                    ->label('District')
                    ->sortable(),

                    TextColumn::make('gender'),
                    TextColumn::make('role')->label('Designation'),
                    TextColumn::make('contact_1')->label('Contact'),
                // ])


                // TextColumn::make('status'),
            ])
            ->headerActions([
                ExportAction::make()->exporter(AhwcMemberExporter::class)->label('Download')->color('primary'),
            ])
            ->filters([
                SelectFilter::make('district')
                ->label('District')
                ->relationship('facility.district', 'district_name') // Filters through the relationship
                ->options(
                    District::all()->pluck('district_name', 'district_code')->toArray()
                ),
            ],layout: FiltersLayout::AboveContent)
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
            'index' => Pages\ListAllAhwcMembers::route('/'),
            'create' => Pages\CreateAllAhwcMember::route('/create'),
            'edit' => Pages\EditAllAhwcMember::route('/{record}/edit'),
        ];
    }
}
