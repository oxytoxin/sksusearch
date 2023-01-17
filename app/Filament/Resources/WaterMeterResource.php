<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WaterMeterResource\Pages;
use App\Filament\Resources\WaterMeterResource\RelationManagers;
use App\Models\WaterMeter;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WaterMeterResource extends Resource
{
    protected static ?string $model = WaterMeter::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper'; 

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationGroup = 'Vouchers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('meter_number')->required(),
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
                TextColumn::make('meter_number')->searchable()->sortable(),
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
            'index' => Pages\ListWaterMeters::route('/'),
            'create' => Pages\CreateWaterMeter::route('/create'),
            'edit' => Pages\EditWaterMeter::route('/{record}/edit'),
        ];
    }    
}
