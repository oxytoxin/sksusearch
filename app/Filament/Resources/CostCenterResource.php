<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Office;
use App\Models\CostCenter;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CostCenterResource\Pages;
use App\Filament\Resources\CostCenterResource\RelationManagers;

class CostCenterResource extends Resource
{
    protected static ?string $model = CostCenter::class;

    protected static ?string $modelLabel = 'Cost Center';

    protected static ?string $navigationIcon = 'heroicon-o-library';

    protected static ?int $navigationSort = 29;

    protected static ?string $navigationLabel = 'Cost Centers';

    protected static ?string $navigationGroup = 'Work & Financial Plan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('fund_cluster_w_f_p_s_id')
                    ->label('Fund Cluster')
                    ->relationship('fundClusterWFP', 'name'),
                Select::make('m_f_o_s_id')
                    ->label('MFO')
                    ->relationship('mfo', 'name'),
                Select::make('office_id')
                    ->searchable()
                    ->preload()
                    ->relationship('office', 'name'),
                TextInput::make('name')
                ->default('CC1'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('office.name')->wrap()->label('Office')->searchable()->sortable(),
                TextColumn::make('head')
                ->getStateUsing(function ($record) {
                    return $record->office->head_employee?->full_name;
                }),
                TextColumn::make('mfo.name')->label('MFO')->wrap()->searchable()->sortable(),
                TextColumn::make('fundClusterWFP.name')->label('Fund Cluster')->searchable()->sortable(),
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
            'index' => Pages\ListCostCenters::route('/'),
            'create' => Pages\CreateCostCenter::route('/create'),
            'edit' => Pages\EditCostCenter::route('/{record}/edit'),
        ];
    }
}