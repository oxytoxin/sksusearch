<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegionResource\Pages;
use App\Filament\Resources\RegionResource\RelationManagers;
use App\Models\PhilippineRegion;
use App\Models\Region;
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

class RegionResource extends Resource
{
    protected static ?string $model = PhilippineRegion::class;

    protected static ?string $modelLabel = 'Region';

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationLabel = 'Regions';

    protected static ?string $navigationGroup = 'Places';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('region_description')->required(),
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
                TextColumn::make('region_description')
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
            'index' => Pages\ListRegions::route('/'),
            'create' => Pages\CreateRegion::route('/create'),
            'edit' => Pages\EditRegion::route('/{record}/edit'),
        ];
    }
}
