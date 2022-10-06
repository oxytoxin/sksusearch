<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherTypeResource\Pages;
use App\Filament\Resources\VoucherTypeResource\RelationManagers;
use App\Filament\Resources\VoucherTypeResource\RelationManagers\VoucherSubtypesRelationManager;
use App\Models\VoucherCategory;
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

class VoucherTypeResource extends Resource
{
    protected static ?string $model = VoucherType::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationLabel = 'Type';

    protected static ?string $navigationGroup = 'Vouchers';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('voucher_category_id')
                    ->label('Category')
                    ->options(VoucherCategory::pluck('name', 'id'))
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
                TextColumn::make('voucher_category.name')
                    ->label('Category')->searchable()->sortable(),
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
            ])
            ->reorderable('order_column')
            ->defaultSort('order_column');
    }


    protected function isTablePaginationEnabledWhileReordering(): bool
    {
        return true;
    }

    public static function getRelations(): array
    {
        return [
            VoucherSubtypesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVoucherTypes::route('/'),
            'create' => Pages\CreateVoucherType::route('/create'),
            'edit' => Pages\EditVoucherType::route('/{record}/edit'),
        ];
    }
}
