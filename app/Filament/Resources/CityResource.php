<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CityResource\Pages;
use App\Filament\Resources\CityResource\RelationManagers;
use App\Models\City;
use App\Models\PhilippineCity;
use App\Models\PhilippineProvince;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CityResource extends Resource
{
    protected static ?string $model = PhilippineCity::class;

    protected static ?string $modelLabel = 'Cities / Municipalities';

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?int $navigationSort = 13;

    protected static ?string $navigationLabel = 'Cities / Municipalities';

    protected static ?string $navigationGroup = 'Places';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('province_code')
                    ->label('Province')
                    ->options(PhilippineProvince::pluck('province_description', 'province_code'))
                    ->searchable()
                    ->required(),
                TextInput::make('city_municipality_description')->required(),
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
                TextColumn::make('province.province_description')
                    ->label('Province')
                    ->searchable(),
                TextColumn::make('city_municipality_description')
                    ->label('Name')
                    ->searchable(),
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
            'index' => Pages\ListCities::route('/'),
            'create' => Pages\CreateCity::route('/create'),
            'edit' => Pages\EditCity::route('/{record}/edit'),
        ];
    }
}
