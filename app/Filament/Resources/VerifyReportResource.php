<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VerifyReportResource\Pages;
use App\Filament\Resources\VerifyReportResource\RelationManagers;
use App\Filament\Resources\VerifyReportResource\Widgets\StatusOverview;
use App\Models\District;
use App\Models\Facility;
use App\Models\Indicator;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

// Add this widget class within the same resource file


class VerifyReportResource extends Resource
{
    protected static ?string $model = Indicator::class;

    protected static ?string $label = 'Monthly Reports';
    public static function canViewAny(): bool
    {
        return Auth::user()->can('viewByAdmin', Indicator::class);
    }

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    // public static function widgets(): array
    // {
    //     return [
    //         StatusOverview::class,
    //     ];
    // }

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

                TextColumn::make('facility.district.district_name'),

                TextColumn::make('facility.name')
                ->label('Facility Name')
                ->sortable()
                ->weight(FontWeight::Bold)
                ->searchable(),
                TextColumn::make('month')
                ->sortable()
                ->weight(FontWeight::Bold)
                ->searchable(),

                TextColumn::make('status')
                    ->label('Indicator Status')
                    ->sortable()
                    ->default('pending')
                    ->alignCenter()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        // 'draft' => 'gray',
                        'pending' => 'warning',
                        'submitted' => 'info',
                        'rejected' => 'danger',
                        'updated' => 'warning',
                        'approved' => 'success'
                    }),
            ])

            ->filters([
                SelectFilter::make('month')
                ->options(function () {
                    $months = [];
                    $currentYear = Carbon::now()->year;
                    foreach (range(1, 12) as $month) {
                        $months[Carbon::createFromDate($currentYear, $month, 1)->format('F-Y')] = Carbon::createFromDate($currentYear, $month, 1)->format('F-Y');
                    }
                    return $months;
                }),

                SelectFilter::make('district')
                    ->label('District')
                    ->relationship('facility.district', 'district_name')
                    ->options(District::all()->pluck('district_name', 'id')),

                SelectFilter::make('Status')
                ->options([
                    'submitted' => 'Submitted',
                    'pending' => 'Pending',
                    'rejected' => 'Rejected',
                    'updated' => 'Updated',
                    'approved' => 'Approved',
                ])

            ],layout: FiltersLayout::AboveContent)

            ->actions([

                ViewAction::make()
                ->form([
                    Grid::make() // Use Grid to define columns
                        ->schema([
                            TextInput::make('nin')->columnSpan(1), // Adjust column span if needed
                            TextInput::make('status')->columnSpan(1),
                            TextInput::make('month')->columnSpan(1),

                            TextInput::make('indicator_1')->label('No. OPD cases in a month')->columnSpan(1),
                            TextInput::make('indicator_2')->label('No. of people underwent Prakriti Assessment')->columnSpan(1),
                            TextInput::make('indicator_3')->label('Portion of patient of DM on AYUSH Treatment')->columnSpan(1),
                            TextInput::make('indicator_4')->label('Portion of patient of HT on AYUSH Treatment')->columnSpan(1),
                            TextInput::make('indicator_5')->label('Population of above 30 years screen for HT')->columnSpan(1),
                            TextInput::make('indicator_6')->label('No. of Individual empanelled in AYUSH Facilities')->columnSpan(1),
                            TextInput::make('indicator_7')->label('Population of above 30 years screen for DM')->columnSpan(1),
                            TextInput::make('indicator_8')->label('Group session held at community level for AYUSH lifestyle counselling')->columnSpan(1),
                            TextInput::make('indicator_9')->label('Organizing/participating in inter sectoral meeting involving public')->columnSpan(1),
                            TextInput::make('indicator_10')->label('Conduct outreach programme & AYUSH Awareness')->columnSpan(1),
                        ])
                        ->columns([
                            'default' => 3, // Display 3 columns by default on larger screens
                            'sm' => 1,      // Display 1 column on mobile screens (small)
                        ]),
                ])
                ->modalHeading('Facility Details')
                ->modalDescription('View facility and its indicators')
                ->modalWidth('4xl'),





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
            'index' => Pages\ListVerifyReports::route('/'),
            'create' => Pages\CreateVerifyReport::route('/create'),
            'edit' => Pages\EditVerifyReport::route('/{record}/edit'),
        ];
    }
}
