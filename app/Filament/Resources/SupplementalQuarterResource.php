<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplementalQuarterResource\Pages;
use App\Filament\Resources\SupplementalQuarterResource\RelationManagers;
use App\Models\SupplementalQuarter;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplementalQuarterResource extends Resource
{
    protected static ?string $model = SupplementalQuarter::class;

    protected static ?string $modelLabel = 'Supplementals';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 39;

    protected static ?string $navigationLabel = 'Supplementals';

    protected static ?string $navigationGroup = 'Work & Financial Plan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                Toggle::make('is_active')->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                ToggleColumn::make('is_active')
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSupplementalQuarters::route('/'),
            'create' => Pages\CreateSupplementalQuarter::route('/create'),
            'edit' => Pages\EditSupplementalQuarter::route('/{record}/edit'),
        ];
    }    
}
