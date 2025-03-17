<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DmoVerificationResource\Pages;
use App\Filament\Resources\DmoVerificationResource\RelationManagers;
use App\Models\DmoVerification;
use App\Models\Indicator;
use Carbon\Carbon;
use Filament\Actions\ViewAction as ActionsViewAction;
use Filament\Forms;
use Filament\Forms\Components\Grid as ComponentsGrid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class DmoVerificationResource extends Resource
{
    protected static ?string $model = Indicator::class;

    protected static ?string $label = 'Approve Report';
    public static function canViewAny(): bool
    {
        return Auth::user()->can('viewReport', Indicator::class);
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
                TextColumn::make('facility.name')
                ->label('Facility Name')
                ->sortable()
                ->weight(FontWeight::Bold)
                ->searchable(),

                TextColumn::make('month'),

                TextColumn::make('facility.user.name')
                ->label('Incharge')
                ->default('Not Assigned')
                ->colors([
                    'warning' => 'Not Assigned',
                ]),

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
            ->modifyQueryUsing(function (Builder $query) {
                // Filter by the DMO's district
                return $query
                ->whereHas('facility', function ($query) {
                    $query->where('district_code', Auth::user()->district_code);
                });
            })
            ->filters([
                SelectFilter::make('month')
                ->options(function () {
                    $months = [];
                    $currentYear = Carbon::now()->year;
                    foreach (range(1, 12) as $month) {
                        $months[Carbon::createFromDate($currentYear, $month, 1)->format('F-Y')] = Carbon::createFromDate($currentYear, $month, 1)->format('F-Y');
                    }
                    return $months;
                })
            ],layout: FiltersLayout::AboveContent)



            ->actions([
                // ViewAction::make()
                // ->form([
                //     TextInput::make('nin'),
                //     TextInput::make('status'),
                //     TextInput::make('month'),
                //     TextInput::make('indicator_1'),
                //     TextInput::make('indicator_2'),
                //     TextInput::make('indicator_3'),
                //     TextInput::make('indicator_4'),
                //     TextInput::make('indicator_5'),
                //     TextInput::make('indicator_6'),
                //     TextInput::make('indicator_7'),
                //     TextInput::make('indicator_8'),
                //     TextInput::make('indicator_9'),
                //     TextInput::make('indicator_10'),
                // ])
                // ->modalHeading('Facility Details')
                // ->modalDescription('View facility and its indicators')
                // ->modalWidth('4xl'),
                ViewAction::make()
                ->form([
                    ComponentsGrid::make() // Use Grid to define columns
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


                Action::make('Verify')
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'approved' => 'Approve',
                                'rejected' => 'Reject',
                            ])
                            ->required(),

                        TextInput::make('remarks')
                            ->label('Remarks')
                            ->placeholder('Provide a reason for rejection')
                            ->required(fn ($get) => $get('status') === 'rejected') // Make remarks required if status is "rejected"
                            ->visible(fn ($get) => $get('status') === 'rejected'),
                    ])
                    ->action(function ($record, $data) {
                        $record->update([
                            'status' => $data['status'],
                            'remarks' => $data['remarks'] ?? null,
                        ]);
                         // Check if the status was updated to 'rejected'
                        // if ($data['status'] === 'rejected') {
                        //     // Notify the MO that their submission has been rejected
                        //     Notification::make()
                        //         ->title('Submission Rejected')
                        //         ->body('Your submission for the month of ' . $record->month . ' has been rejected. Remarks: ' . ($data['remarks'] ?? 'No remarks provided.'))
                        //         ->danger() // Display notification with a red badge
                        //         ->sendToDatabase($record->user); // Assuming 'user' is the MO related to the record
                        // }
                    })
                    ->visible(fn ($record) => $record->status !== 'approved') // Only show edit action if status is 'rejected'
                    ->disabled(fn ($record) => $record->status === 'approved'),

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
            'index' => Pages\ListDmoVerifications::route('/'),
            'create' => Pages\CreateDmoVerification::route('/create'),
            'edit' => Pages\EditDmoVerification::route('/{record}/edit'),
        ];
    }
}
