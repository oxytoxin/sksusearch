<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProvinceResource\Pages;
use App\Filament\Resources\ProvinceResource\RelationManagers;
use App\Models\PhilippineProvince;
use App\Models\PhilippineRegion;
use App\Models\Province;
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

class ProvinceResource extends Resource
{
    protected static ?string $model = PhilippineProvince::class;

    protected static ?string $modelLabel = 'Provinces';

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationLabel = 'Provinces';

    protected static ?string $navigationGroup = 'Places';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('region_code')
                    ->label('Region')
                    ->options(PhilippineRegion::pluck('region_description', 'region_code'))
                    ->searchable()
                    ->required(),
                TextInput::make('province_description')->required(),
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
                TextColumn::make('region.region_description')
                    ->label('Region')
                    ->searchable(),
                TextColumn::make('province_description')
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
            'index' => Pages\ListProvinces::route('/'),
            'create' => Pages\CreateProvince::route('/create'),
            'edit' => Pages\EditProvince::route('/{record}/edit'),
        ];
    }
}
