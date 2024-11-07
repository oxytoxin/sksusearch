<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BudgetCategoryResource\Pages;
use App\Filament\Resources\BudgetCategoryResource\RelationManagers;
use App\Models\BudgetCategory;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BudgetCategoryResource extends Resource
{
    protected static ?string $model = BudgetCategory::class;

    protected static ?string $modelLabel = 'Budget Categories';

    protected static ?string $navigationIcon = 'heroicon-o-menu';

    protected static ?int $navigationSort = 29;

    protected static ?string $navigationLabel = 'Budget Categories';

    protected static ?string $navigationGroup = 'Work & Financial Plan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
            ]);
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
            'index' => Pages\ListBudgetCategories::route('/'),
            'create' => Pages\CreateBudgetCategory::route('/create'),
            'edit' => Pages\EditBudgetCategory::route('/{record}/edit'),
        ];
    }
}
