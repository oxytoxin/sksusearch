<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeInformationResource\Pages;
use App\Filament\Resources\EmployeeInformationResource\RelationManagers;
use App\Models\Campus;
use App\Models\EmployeeInformation;
use App\Models\Office;
use App\Models\Position;
use App\Models\Role;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeInformationResource extends Resource
{
    protected static ?string $model = EmployeeInformation::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Information';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationGroup = 'Employees';


    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Fieldset::make('Employee Information')
                    ->schema([
                        TextInput::make('first_name')->required(),
                        TextInput::make('last_name')->required(),
                        TextInput::make('address'),
                        DatePicker::make('birthday'),
                        Grid::make(1)
                            ->schema([
                                TextInput::make('full_name')->required(),
                                TextInput::make('email')->default('@sksu.edu.ph')
                                    ->email()
                                    ->unique('users', 'email', fn ($record) => $record?->user)
                                    ->required(),
                            ]),
                    ])->columns(2),
                Fieldset::make('Assignment')
                    ->schema([

                        Grid::make(2)
                            ->schema([
                                Select::make('campus_id')
                                    ->label('Campus')
                                    ->options(Campus::pluck('name', 'id'))
                                    ->searchable()->reactive()
                                    ->afterStateUpdated(function ($set, $state) {
                                        $set('office_id', null);
                                    })
                                    ->required(),
                                Select::make('office_id')
                                    ->label('Office')
                                    ->reactive()
                                    ->visible(fn ($get) => $get('campus_id'))
                                    ->options(fn ($get) => Office::where('campus_id', $get('campus_id'))->pluck('name', 'id'))
                                    ->searchable()->required(),
                            ]), 
                        Select::make('role_id')
                            ->label('Role')
                            ->options(Role::pluck('description', 'id'))
                            ->searchable()->required(),
                        Select::make('position_id')
                            ->label('Position')
                            ->options(Position::pluck('description', 'id'))
                            ->searchable()->required(),
                    ])->columns(2)


            ]);
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // TextColumn::make('user_id')->label('USER ID'),
                TextColumn::make('full_name')->searchable(['first_name', 'last_name'])->sortable(),
                TextColumn::make('position.description')->searchable()->sortable()->limit(20)
                    ->tooltip(fn ($record): string => $record->position?->description ?? 'No Position')
                    ->default('No Position'),
                TextColumn::make('campus.name')->searchable()->sortable()->limit(20)
                    ->tooltip(fn ($record): string => $record->campus?->name ?? "Not Assigned")
                    ->default('Not Assigned'),    
                TextColumn::make('office.name')->searchable()->sortable()->limit(20)
                    ->tooltip(fn ($record): string => $record->office?->name ?? "No Office")
                    ->default('No Office'),
               
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('success'),
            ])
            ->bulkActions([
                //Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListEmployeeInformation::route('/'),
            'create' => Pages\CreateEmployeeInformation::route('/create'),
            'edit' => Pages\EditEmployeeInformation::route('/{record}/edit'),
        ];
    }
}
