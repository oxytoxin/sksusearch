<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FundClusterResource\Pages;
use App\Filament\Resources\FundClusterResource\RelationManagers;
use App\Models\FundCluster;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FundClusterResource extends Resource
{
    protected static ?string $model = FundCluster::class;

    protected static ?string $modelLabel = 'Fund Cluster';

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?int $navigationSort = 14;

    protected static ?string $navigationLabel = 'Fund Clusters';

    protected static ?string $navigationGroup = 'Others';

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
            'index' => Pages\ListFundClusters::route('/'),
            'create' => Pages\CreateFundCluster::route('/create'),
            'edit' => Pages\EditFundCluster::route('/{record}/edit'),
        ];
    }    
}
