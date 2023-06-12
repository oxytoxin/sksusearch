<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InternetAccountNumberResource\Pages;
use App\Filament\Resources\InternetAccountNumberResource\RelationManagers;
use App\Models\InternetAccountNumber;
use App\Models\Campus;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InternetAccountNumberResource extends Resource
{
    protected static ?string $model = InternetAccountNumber::class;

    protected static ?string $navigationIcon = 'heroicon-o-wifi';

    protected static ?int $navigationSort = 14;

    protected static ?string $navigationGroup = 'Vouchers';

    protected static ?string $navigationLabel = 'Internet Accounts';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('campus_id')
                ->label('Campus')
                ->options(Campus::all()->pluck('name', 'id'))
                ->searchable()->required(),
                TextInput::make('supplier_name')->required(),
                TextInput::make('account_number')->required(),
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
                TextColumn::make('campus.name')->searchable()->sortable(),
                TextColumn::make('supplier_name')->searchable()->sortable(),
                TextColumn::make('account_number')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
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
            'index' => Pages\ListInternetAccountNumbers::route('/'),
            'create' => Pages\CreateInternetAccountNumber::route('/create'),
            'edit' => Pages\EditInternetAccountNumber::route('/{record}/edit'),
        ];
    }
}
