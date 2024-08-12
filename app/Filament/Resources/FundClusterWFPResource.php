<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\FundClusterWFP;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FundClusterWFPResource\Pages;
use App\Filament\Resources\FundClusterWFPResource\RelationManagers;

class FundClusterWFPResource extends Resource
{
    protected static ?string $model = FundClusterWFP::class;

    protected static ?string $modelLabel = 'Fund Clusters';

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?int $navigationSort = 28;

    protected static ?string $navigationLabel = 'Fund Clusters';

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
            'index' => Pages\ListFundClusterWFPS::route('/'),
            'create' => Pages\CreateFundClusterWFP::route('/create'),
            'edit' => Pages\EditFundClusterWFP::route('/{record}/edit'),
        ];
    }
}
