<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PettyCashFundResource\Pages;
use App\Filament\Resources\PettyCashFundResource\RelationManagers;
use App\Models\EmployeeInformation;
use App\Models\PettyCashFund;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PettyCashFundResource extends Resource
{
    protected static ?string $model = PettyCashFund::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 14;

    protected static ?string $navigationGroup = 'Others';

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    protected static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('custodian_id')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(EmployeeInformation::pluck('full_name', 'user_id'))
                    ->label('Custodian'),
                Select::make('campus_id')
                    ->required()
                    ->relationship('campus', 'name')
                    ->label('Campus'),
                TextInput::make('voucher_limit')
                    ->numeric()
                    ->default(3000)
                    ->gte(0)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('campus.name'),
                Tables\Columns\TextColumn::make('custodian.employee_information.full_name'),
                Tables\Columns\TextColumn::make('voucher_limit')->formatStateUsing(fn ($state) => number_format($state, 2))->prefix('P'),
                Tables\Columns\TextColumn::make('balance')->formatStateUsing(fn ($state) => number_format($state, 2))->prefix('P'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListPettyCashFunds::route('/'),
            'create' => Pages\CreatePettyCashFund::route('/create'),
            'edit' => Pages\EditPettyCashFund::route('/{record}/edit'),
        ];
    }
}
