<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiquidationReportStepResource\Pages;
use App\Filament\Resources\LiquidationReportStepResource\RelationManagers;
use App\Models\LiquidationReportStep;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LiquidationReportStepResource extends Resource
{
    protected static ?string $model = LiquidationReportStep::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Miscellaneous';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id'),
                Forms\Components\Toggle::make('enabled')
                    ->required(),
                Select::make('office_group_id')
                    ->relationship('office_group', 'name'),
                Forms\Components\TextInput::make('process')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('recipient')
                    ->maxLength(191),
                Forms\Components\TextInput::make('sender')
                    ->maxLength(191),
                Forms\Components\TextInput::make('return_step_id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\IconColumn::make('enabled')->boolean(),
                Tables\Columns\TextColumn::make('process'),
                Tables\Columns\TextColumn::make('recipient'),
                Tables\Columns\TextColumn::make('sender'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListLiquidationReportSteps::route('/'),
            'create' => Pages\CreateLiquidationReportStep::route('/create'),
            'edit' => Pages\EditLiquidationReportStep::route('/{record}/edit'),
        ];
    }
}
