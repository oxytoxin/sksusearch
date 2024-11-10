<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\ErrorQuery;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ErrorQueryResource\Pages;
use App\Filament\Resources\ErrorQueryResource\RelationManagers;

class ErrorQueryResource extends Resource
{
    protected static ?string $model = ErrorQuery::class;

    protected static ?string $modelLabel = 'Error Queries';

    protected static ?string $navigationIcon = 'heroicon-o-x-circle';

    protected static ?int $navigationSort = 37;

    protected static ?string $navigationLabel = 'Error Queries';

    protected static ?string $navigationGroup = 'Work & Financial Plan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->color('success'),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListErrorQueries::route('/'),
            'create' => Pages\CreateErrorQuery::route('/create'),
            'edit' => Pages\EditErrorQuery::route('/{record}/edit'),
        ];
    }
}
