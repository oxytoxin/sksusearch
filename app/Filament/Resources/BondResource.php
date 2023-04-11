<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BondResource\Pages;
use App\Filament\Resources\BondResource\RelationManagers;
use App\Models\Bond;
use App\Models\EmployeeInformation;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BondResource extends Resource
{
    protected static ?string $model = Bond::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Bonds';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationGroup = 'Employees';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                ->schema([
                    Select::make('employee')
                    ->label('Employee')
                    ->options(EmployeeInformation::where('bond_id', null)->pluck('full_name', 'id'))
                    ->helperText('Employees without bond are shown here')
                    ->searchable()->required(),
                TextInput::make('bond_certificate_number')
                ->label('Bond Certificate Number')
                ->required(),

                TextInput::make('amount')
                    ->label('Bond')
                    ->mask(
                        fn (TextInput\Mask $mask) => $mask
                            ->numeric()
                            ->decimalPlaces(2) // Set the number of digits after the decimal point.
                            ->minValue(1) // Set the minimum value that the number can be.
                            ->maxValue(99999999) // Set the maximum value that the number can be.
                            ->normalizeZeros() // Append or remove zeros at the end of the number.
                            ->padFractionalZeros() // Pad zeros at the end of the number to always maintain the maximum number of decimal places.
                            ->thousandsSeparator(','), // Add a separator for thousands.->required(),
                    )
                    ->helperText('Set amount of bond here')
                    ->required(),
                    ]),
                Grid::make(2)
                    ->schema([
                        DatePicker::make('validity_date_from')->required(),
                        DatePicker::make('validity_date_to')->required(),
                    ])
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
                TextColumn::make('employee_information.full_name')
                    ->label('Employee')->searchable()->sortable(),
                TextColumn::make('bond_certificate_number')->label('Certificate Number')->searchable()->sortable(),
                TextColumn::make('amount')->label('Bond')->searchable()->sortable(),
                TextColumn::make('validity_date_from')->label('From')->searchable()->sortable()->date(),
                TextColumn::make('validity_date_to')->label('To')->searchable()->sortable()->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->color('success'),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBonds::route('/'),
            'create' => Pages\CreateBond::route('/create'),
            'edit' => Pages\EditBond::route('/{record}/edit'),
        ];
    }
}
