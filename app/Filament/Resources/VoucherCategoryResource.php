<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherCategoryResource\Pages;
use App\Filament\Resources\VoucherCategoryResource\RelationManagers;
use App\Models\VoucherCategory;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VoucherCategoryResource extends Resource
{
    protected static ?string $model = VoucherCategory::class;

    protected static ?string $modelLabel = 'Voucher Categories';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationLabel = 'Category';

    protected static ?string $navigationGroup = 'Vouchers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            'index' => Pages\ListVoucherCategories::route('/'),
            'create' => Pages\CreateVoucherCategory::route('/create'),
            'edit' => Pages\EditVoucherCategory::route('/{record}/edit'),
        ];
    }
}
