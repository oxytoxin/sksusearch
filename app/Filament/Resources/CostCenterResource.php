<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\MfoFee;
use App\Models\FundClusterWFP;
use App\Models\Office;
use App\Models\CostCenter;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
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

    protected static ?int $navigationSort = 36;

    protected static ?string $navigationLabel = 'Cost Centers';

    protected static ?string $navigationGroup = 'Work & Financial Plan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
                ->schema([
                    Select::make('fund_cluster_w_f_p_s_id')
                    ->label('Fund Cluster')
                    // ->relationship('fundClusterWFP', 'name')
                    ->options(fn ($record) => FundClusterWFP::orderBy('id')->pluck('name', 'id'))
                    ->reactive(),
                    Select::make('m_f_o_s_id')
                    ->label('MFO')
                    ->relationship('mfo', 'name')
                    ->reactive(),
                    Select::make('mfo_fee_id')
                    ->label('MFO Fee')
                    ->options(function ($get) {

                        return MfoFee::where('m_f_o_s_id', $get('m_f_o_s_id'))->where('fund_cluster_w_f_p_s_id', $get('fund_cluster_w_f_p_s_id'))->pluck('name', 'id');
                    })
                    ->searchable()
                    //->relationship('mfoFee', 'name')
                    ->visible(fn ($get) => $get('fund_cluster_w_f_p_s_id') > 2),
                ]),

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
                TextColumn::make('id')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('office.name')->wrap()->label('Office')->searchable()->sortable(),
                TextColumn::make('head')
                ->getStateUsing(function ($record) {
                    return $record->office->head_employee?->full_name;
                }),
                TextColumn::make('mfo.name')->label('MFO')->wrap()->searchable()->sortable(),
                TextColumn::make('fundClusterWFP.name')->label('Fund Cluster')->searchable()->sortable(),
                TextColumn::make('mfoFee.name')->label('MFO Fee')->searchable()->sortable(),
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
