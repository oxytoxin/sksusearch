<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryItemsResource\Pages;
use App\Filament\Resources\CategoryItemsResource\RelationManagers;
use App\Models\CategoryItems;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryItemsResource extends Resource
{
    protected static ?string $model = CategoryItems::class;

    protected static ?string $modelLabel = 'Category Items';

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';

    protected static ?int $navigationSort = 33;

    protected static ?string $navigationLabel = 'Account Titles';

    protected static ?string $navigationGroup = 'Work & Financial Plan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('budget_category_id')
                    ->relationship('budgetCategory', 'name')
                    ->required(),
                TextInput::make('uacs_code')->required(),
                TextInput::make('name')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('budgetCategory.name')->label('Budget Category')->searchable()->sortable(),
                TextColumn::make('uacs_code')->searchable()->sortable(),
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
            'index' => Pages\ListCategoryItems::route('/'),
            'create' => Pages\CreateCategoryItems::route('/create'),
            'edit' => Pages\EditCategoryItems::route('/{record}/edit'),
        ];
    }
}
