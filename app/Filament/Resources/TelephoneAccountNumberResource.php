<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TelephoneAccountNumberResource\Pages;
use App\Filament\Resources\TelephoneAccountNumberResource\RelationManagers;
use App\Models\TelephoneAccountNumber;
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

class TelephoneAccountNumberResource extends Resource
{
    protected static ?string $model = TelephoneAccountNumber::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?int $navigationSort = 13;

    protected static ?string $navigationGroup = 'Vouchers';

    protected static ?string $navigationLabel = 'Telephone Accounts';

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
            'index' => Pages\ListTelephoneAccountNumbers::route('/'),
            'create' => Pages\CreateTelephoneAccountNumber::route('/create'),
            'edit' => Pages\EditTelephoneAccountNumber::route('/{record}/edit'),
        ];
    }
}
