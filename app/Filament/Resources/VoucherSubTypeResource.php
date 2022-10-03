<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherSubTypeResource\Pages;
use App\Forms\Components\ArrayField;
use App\Models\VoucherSubType;
use App\Models\VoucherType;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\MultiSelectFilter;

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
                TextInput::make('name')
                    ->required(),
                Grid::make(1)->schema([
                    ArrayField::make('documents')
                        ->placeholder('Add document (Press Enter)')
                        ->label('Required Documents')
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
                TextColumn::make('voucher_type.name')
                    ->label('Type')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
            ])
            ->filters([
                MultiSelectFilter::make('voucher_type_id')
                    ->label('Type')
                    ->options(VoucherType::pluck('name', 'id')),
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
