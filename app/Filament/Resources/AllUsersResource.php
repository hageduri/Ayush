<?php

namespace App\Filament\Resources;
use Filament\Tables\Actions\ToggleAction;
use App\Filament\Exports\AllUsersExporter;
use App\Filament\Exports\UserExporter;
use App\Filament\Resources\AllUsersResource\Pages;
use App\Filament\Resources\AllUsersResource\RelationManagers;
use App\Models\AllUsers;
use App\Models\District;
use App\Models\Facility;
use App\Models\User;
use App\Policies\AllUsersPolicy;
use Filament\Actions\Exports\Exporter;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class AllUsersResource extends Resource
{
    protected static ?string $model = User::class;

    public static function canViewAny(): bool
    {
        return Auth::user()->can('viewAnyAll', User::class);
    }

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    public static function form(Form $form): Form
    {
        $usedNins = User::whereNotNull('nin')->pluck('nin')->toArray();
        $usedDistricts = User::where('role', 'DMO')->pluck('district_code')->toArray();
        $availableDistricts = District::whereNotIn('district_code',
        $usedDistricts)->pluck('district_name', 'district_code')->toArray();
        return $form
        ->schema([
            Tabs::make('Tabs')
            ->tabs([
                Tabs\Tab::make('Profile')
                    ->schema([
                        TextInput::make('name')
                        ->autocapitalize()
                        ->required()
                        ->dehydrateStateUsing(fn ($state)=>strtoupper($state)),

                        TextInput::make('email')
                        ->required()
                        ->email()
                        ->unique(table: 'users', ignoreRecord: true),//email validation on update

                        TextInput::make('contact_1')
                        ->label('Phone Number-1')
                        ->required()
                        ->tel(),

                        TextInput::make('contact_2')
                        ->label('Phone Number-2 (Optional)')
                        ->tel(),

                        TextInput::make('password')
                        ->password()
                        ->revealable()
                        ->required()->visibleOn('create'),

                        // TextInput::make('nin')
                        // ->numeric()
                        // ->visibleOn('edit'),

                        Select::make('nin')
                        ->options(Facility::whereNotIn('nin', $usedNins)->pluck('name', 'nin')->toArray())
                        ->searchable(),

                        // Select::make('role')
                        // ->required()
                        // ->options([
                        //     // 'SupAdmin' => 'SupAdmin',
                        //     // 'Admin' => 'Admin',
                        //     'DMO' => 'DMO',
                        //     'MO' => 'MO',
                        // ]),
                        // Select::make('district_code')
                        // ->label('District')
                        // ->required()
                        // ->options(
                        //     District::all()->pluck('district_name', 'district_code')->toArray()
                        // ),
                        Select::make('role')
                            ->required()
                            ->options([
                                'DMO' => 'DMO',
                                'MO' => 'MO',
                            ])
                            ->reactive() // Make role reactive to trigger dynamic behavior
                            ->afterStateUpdated(function ($state, callable $set) use ($availableDistricts) {
                                // If role is 'DMO', limit the districts to available ones
                                if ($state === 'DMO') {
                                    $set('district_code', array_key_first($availableDistricts)); // Set the first available district by default
                                }
                            }),

                        Select::make('district_code')
                            ->label('District')
                            ->required()
                            ->options(function (callable $get) use ($availableDistricts) {
                                // If role is 'DMO', show only districts without DMO
                                if ($get('role') === 'DMO') {
                                    return $availableDistricts;
                                }

                                // Otherwise, show all districts
                                return District::all()->pluck('district_name', 'district_code')->toArray();
                            })
                            ->searchable(),
                    ]),

                Tabs\Tab::make('Personal Details')
                    ->schema([
                        Select::make('gender')
                        ->required()
                        ->options([
                            'Male' => 'Male',
                            'Female' => 'Female',
                        ]),
                        DatePicker::make('dob')
                        ->required(),



                        TextInput::make('designation')
                        ->dehydrateStateUsing(fn ($state)=>strtoupper($state)),



                        Textarea::make('address')
                        ->rows(6)
                        ->columns(10),

                    ])->visibleOn('edit')->hidden(fn (callable $get) => in_array($get('role'), ['DMO', 'ADMIN'])),


                Tabs\Tab::make('Bank Details')
                    ->schema([
                        TextInput::make('bank_name')
                        ->required()
                        ->dehydrateStateUsing(fn ($state)=>strtoupper($state))
                        ,

                        TextInput::make('account_no')
                        ->label('Account Number')
                        ->required()
                        ->minLength(11)
                        ->numeric(),

                        TextInput::make('ifsc_code')
                        ->required()
                        ->maxLength(11)
                        ->dehydrateStateUsing(fn ($state)=>strtoupper($state)),
                    ])//->hidden(fn()=>Auth::user()->role=='SUPER')
                    ->visibleOn('edit')->hidden(fn (callable $get) => in_array($get('role'), ['DMO', 'ADMIN'])),
            ])->columns(2)
            ,


            // TextInput::make('status')->default('')
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([



                    TextColumn::make('name')
                        ->searchable()
                        ->sortable()
                        ->weight('bold')
                        ->description(fn ($record) => $record->email)
                        // ->icon('heroicon-m-envelope')
                        ->label('Name'),

                    TextColumn::make('facility.name'),
                    TextColumn::make('district.district_name'),

                    TextColumn::make('gender'),
                    TextColumn::make('role')->label('Designation'),

                    TextColumn::make('contact_1')->label('Contact'),
                    TextColumn::make('status'),
                    // ToggleColumn::make('is_admin')
                    // ->label('Status')
                    // ->onColor('success')
                    // ->offColor('danger'),


                    ToggleColumn::make('status')
                        ->label('Active Status')
                        ->onColor('success') // Green when active
                        ->offColor('danger') // Red when inactive
                        ->onIcon('heroicon-o-check-circle') // Icon for active
                        ->offIcon('heroicon-o-x-circle') // Icon for inactive
                        ->beforeStateUpdated(fn ($record, $state) =>
                            $record->update(['status' => $state ? 'Active' : 'Inactive'])
                        ),
                    ])





            ->filters([
                SelectFilter::make('role')
                ->label('Filter by Designationt')
                ->options([
                    'MO' => 'Medical Officer',
                    'DMO' => 'District Medical Officer',

                ])
                ],layout: FiltersLayout::AboveContent)


            ->actions([
                Tables\Actions\EditAction::make(),
                // ToggleAction::make('is_admin')
                // ->label('Toggle Admin')
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
            'index' => Pages\ListAllUsers::route('/'),
            'create' => Pages\CreateAllUsers::route('/create'),
            'edit' => Pages\EditAllUsers::route('/{record}/edit'),
        ];
    }

}
