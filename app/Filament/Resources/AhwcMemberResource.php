<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AhwcMemberResource\Pages;
use App\Filament\Resources\AhwcMemberResource\RelationManagers;
use App\Models\AhwcMember;
use App\Models\District;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

use function Laravel\Prompts\select;

class AhwcMemberResource extends Resource
{

    protected static ?string $model = AhwcMember::class;
    protected static ?string $label = 'My Team';

    public static function canViewAny(): bool
    {
        return Auth::user()->can('viewAny', AhwcMember::class);
    }

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
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
                            ->unique(table: 'ahwc_members', ignoreRecord: true),//email validation on update

                            TextInput::make('nin')
                            ->numeric()
                            ->readOnly()
                            ->default(Auth::user()->nin)
                            ,
                            // ->visibleOn('edit'),


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


                            TextInput::make('contact_1')
                            ->label('Phone Number-1')
                            ->required()
                            ->tel(),

                            TextInput::make('contact_2')
                            ->label('Phone Number-2 (Optional)')
                            ->tel(),

                            Select::make('role')
                            ->required()
                            ->options([
                                // 'SupAdmin' => 'SupAdmin',
                                // 'Admin' => 'Admin',
                                'ASHA' => 'ASHA',
                                'ANM'=>'ANM',
                                'MTS' => 'MTS',
                            ]),
                            // TextInput::make('designation')
                            // ->dehydrateStateUsing(fn ($state)=>strtoupper($state)),

                            Select::make('district_code')
                            ->label('District')
                            ->required()
                            ->options(
                                District::all()->pluck('district_name', 'district_code')->toArray()
                            ),

                            // Select::make('district_code')
                            // ->label('District')
                            // ->required()
                            // ->options(
                            //     District::all()->pluck('district_name', 'district_code')->toArray()
                            // )
                            // ->default(function () {
                            //     // Fetch district code based on the authenticated user's nin
                            //     $user = Auth::user();
                            //     $district = District::where('nin', $user->nin)->first();
                            //     return $district ? $district->district_code : null;
                            // }) // Set default based on nin
                            // ->readOnly(), // Optionally make it read-only


                            Textarea::make('address')
                            ->rows(6)
                            ->columns(10),


                        ]),
                    Tabs\Tab::make('Bank Details')
                        ->schema([
                            TextInput::make('bank_name')
                            ->required()
                            ->dehydrateStateUsing(fn ($state)=>strtoupper($state)),

                            TextInput::make('account_no')
                            ->label('Account Number')
                            ->required()
                            ->minLength(11)
                            ->numeric(),

                            TextInput::make('ifsc_code')
                            ->required()
                            ->maxLength(11)
                            ->dehydrateStateUsing(fn ($state)=>strtoupper($state)),
                        ]),
                ])->columns(2),






                // TextInput::make('status')->default('')
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        $user = Auth::user();

        return $table

            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('nin'),
                TextColumn::make('email'),
                TextColumn::make('gender'),
                TextColumn::make('role')->label('Designation'),
                TextColumn::make('contact_1')->label('Contact'),
                TextColumn::make('status'),
            ])
            ->modifyQueryUsing(fn (Builder $query) =>
            $query->where('nin', '=', $user->nin
            ))//this line state that each mo will only can see his faclity member
            ->filters([


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

    // protected function getTableQuery(): Builder
    // {

    //     dd($user->nin); // Add this line
    //     return parent::getTableQuery()->where('nin','=', $user->nin);
    // }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAhwcMembers::route('/'),
            'create' => Pages\CreateAhwcMember::route('/create'),
            'edit' => Pages\EditAhwcMember::route('/{record}/edit'),
        ];
    }
}
