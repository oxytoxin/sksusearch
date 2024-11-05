<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\MfoFee;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MfoFeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MfoFeeResource\RelationManagers;

class MfoFeeResource extends Resource
{
    protected static ?string $model = MfoFee::class;

    protected static ?string $modelLabel = 'MFO Fees';

    protected static ?string $navigationIcon = 'heroicon-o-archive';

    protected static ?int $navigationSort = 31;

    protected static ?string $navigationLabel = 'MFO Fees';

    protected static ?string $navigationGroup = 'Work & Financial Plan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('m_f_o_s_id')
                ->label('MFO')
                ->relationship('mfo', 'name')
                ->searchable()
                ->preload()
                ->required(),
                Select::make('fund_cluster_w_f_p_s_id')
                ->label('Fund Cluster')
                ->relationship('fundClusterWFP', 'name')
                ->searchable()
                ->preload()
                ->required(),
                TextInput::make('name')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mfo.name')->label('MFO')->sortable()->searchable(),
                TextColumn::make('fundClusterWFP.name')->sortable()->label('Fund Cluster')->searchable(),
                TextColumn::make('name')->label('Name')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->color('success'),
                // Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListMfoFees::route('/'),
            'create' => Pages\CreateMfoFee::route('/create'),
            'edit' => Pages\EditMfoFee::route('/{record}/edit'),
        ];
    }
}
