<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ElectricityMeterResource\Pages;
use App\Filament\Resources\ElectricityMeterResource\RelationManagers;
use App\Models\ElectricityMeter;
use App\Models\Campus;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ElectricityMeterResource extends Resource
{
    protected static ?string $model = ElectricityMeter::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationGroup = 'Vouchers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('campus_id')
                ->label('Campus')
                ->options(Campus::all()->pluck('name', 'id'))
                ->searchable()->required(),
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
                TextColumn::make('campus.name')->searchable()->sortable(),
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
            'index' => Pages\ListElectricityMeters::route('/'),
            'create' => Pages\CreateElectricityMeter::route('/create'),
            'edit' => Pages\EditElectricityMeter::route('/{record}/edit'),
        ];
    }    
}
