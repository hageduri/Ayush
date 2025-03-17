<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndicatorResource\Pages;
use App\Filament\Resources\IndicatorResource\RelationManagers;
use App\Models\Indicator;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Grid as ComponentsGrid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\callback;

class IndicatorResource extends Resource
{
    protected static ?string $model = Indicator::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nin')
                ->numeric()
                ->readOnly()
                ->default(Auth::user()->nin),

                // TextInput::make('month')
                // ->default(Carbon::now()->format('F-Y'))  // Format the current date as "Month-Year"
                // ->readOnly(),

                Select::make('month') //extracting month and year
                ->options(function () {
                    $months = [];
                    $currentYear = Carbon::now()->year;
                    foreach (range(1, 12) as $month) {
                        $months[Carbon::createFromDate($currentYear, $month, 1)->format('F-Y')] = Carbon::createFromDate($currentYear, $month, 1)->format('F-Y');
                    }
                    return $months;
                })

                ->default(Carbon::now()->format('F-Y'))
                ->required()
                ->rules([  // making selection of month unique for each nin
                    function ($get) {
                        return Rule::unique('indicators', 'month')->where(function ($query) use ($get) {
                            return $query->where('nin', $get('nin'));
                        });
                    },
                ])
                ->validationMessages([
                    'unique' => 'A report for this month has already been submitted for this NIN.',
                ])
                ->visibleOn('create'),//making this field readonly on edit


                TextInput::make('indicator_1')
                ->numeric()
                ->label('No.OPD cases in a month')
                ->required(),

                TextInput::make('indicator_2')
                ->numeric()
                ->label('No. of people underwent Prakriti Assessment')
                ->required(),

                TextInput::make('indicator_3')
                ->numeric()
                ->label('Portion of patient of DM on AYUSH Treatment')
                ->required(),

                TextInput::make('indicator_4')
                ->numeric()
                ->label('Portion of patient of HT on AYUSH Treatment')
                ->required(),

                TextInput::make('indicator_5')
                ->numeric()
                ->label('Population of above 30 years screen for HT ')
                ->required(),

                TextInput::make('indicator_6')
                ->numeric()
                ->label('No. of Individual empanelled in AYUSH Facilities ')
                ->required(),

                TextInput::make('indicator_7')
                ->numeric()
                ->label('Population of above 30 years screen for DM')
                ->required(),

                TextInput::make('indicator_8')
                ->numeric()
                ->label('Group session held at community level for AYUSH lifestyle counselling (15 persons/session)')
                ->required(),

                TextInput::make('indicator_9')
                ->numeric()
                ->label('Organizing/participating in inter sectoral meeting involving public. ')
                ->required(),

                TextInput::make('indicator_10')
                ->numeric()
                ->label('Conduct outreach programme & AYUSH Awareness for counselling & lifestyle modification. 3 (Three) campaigned in a month')
                ->required(),

                Hidden::make('status')
                ->default('submitted')





            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nin'),
                TextColumn::make('month'),
                TextColumn::make('status')
                ->alignCenter()
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    // 'draft' => 'gray',
                    // 'reviewing' => 'warning',
                    'submitted' => 'info',
                    'rejected' => 'danger',
                    'updated' => 'warning',
                    'approved' => 'success'
                }),
                TextColumn::make('remarks'),


            ])
            ->modifyQueryUsing(fn (Builder $query) =>
            $query->where('nin', '=', Auth::user()->nin
            ))//this line state that each mo will only can see his faclity member
            ->filters([
                // SelectFilter::make('month')
            ])
            ->actions([
                ViewAction::make()
                ->form([
                    ComponentsGrid::make() // Use Grid to define columns
                        ->schema([
                            TextInput::make('nin')->columnSpan(1), // Adjust column span if needed
                            TextInput::make('status')
                            ->columnSpan(1),
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
                            1,
                            'md' => 3, // Display 3 columns by default on larger screens
                            // 'sm' => 1,      // Display 1 column on mobile screens (small)
                        ]),
                ])
                ->modalHeading('Facility Details')
                ->modalDescription('View facility and its indicators')
                ->modalWidth('4xl'),

                Tables\Actions\EditAction::make()
                ->visible(fn ($record) => $record->status === 'rejected') // Only show edit action if status is 'rejected'
                ->disabled(fn ($record) => $record->status !== 'rejected')
                ->mutateFormDataUsing(function (array $data): array {
                    // Update the status field to 'updated'
                    $data['status'] = 'updated';
                    $data['remarks'] = null;
                    return $data;
                })
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListIndicators::route('/'),
            'create' => Pages\CreateIndicator::route('/create'),
            // 'edit' => Pages\EditIndicator::route('/{record}/edit'),
        ];
    }
}
