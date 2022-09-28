<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DailyTravelExpensesResource\Pages;
use App\Filament\Resources\DailyTravelExpensesResource\RelationManagers;
use App\Models\DailyTravelExpenses;
use App\Models\Dte;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DailyTravelExpensesResource extends Resource
{
    protected static ?string $model = Dte::class;

    protected static ?string $modelLabel = 'Daily Travel Expenses (DTE)';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationLabel = 'Daily Travel Expenses (DTE)';

    protected static ?string $navigationGroup = 'Others';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('philippine_region_id')->label('Region')->disabled()->required(),
                TextInput::make('amount')->integer()->minValue(0)->required(),
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
                TextColumn::make('amount')->searchable()->sortable(),
                TextColumn::make('philippine_region.region_description')->label('Region')->searchable()->sortable(),
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
            'index' => Pages\ListDailyTravelExpenses::route('/'),
            'create' => Pages\CreateDailyTravelExpenses::route('/create'),
            'edit' => Pages\EditDailyTravelExpenses::route('/{record}/edit'),
        ];
    }    
}
