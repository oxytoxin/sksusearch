<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherSubTypeResource\Pages;
use App\Filament\Resources\VoucherSubTypeResource\RelationManagers;
use App\Models\VoucherSubType;
use App\Models\VoucherType;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoucherSubTypeResource extends Resource
{
    protected static ?string $model = VoucherSubType::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Sub Type';

    protected static ?string $navigationGroup = 'Vouchers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('voucher_type_id')
                    ->label('Type')
                    ->options(VoucherType::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('name')->required(),
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
                TextColumn::make('voucher_types.name')
                    ->label('Type')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
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
            'index' => Pages\ListVoucherSubTypes::route('/'),
            'create' => Pages\CreateVoucherSubType::route('/create'),
            'edit' => Pages\EditVoucherSubType::route('/{record}/edit'),
        ];
    }
}
