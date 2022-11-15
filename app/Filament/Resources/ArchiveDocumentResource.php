<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArchiveDocumentResource\Pages;
use App\Filament\Resources\ArchiveDocumentResource\RelationManagers;
use App\Models\ArchiveDocument;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ArchiveDocumentResource extends Resource
{
    protected static ?string $model = ArchiveDocument::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationLabel = 'Archive Documents';

    protected static ?int $navigationSort = 21;

    protected static ?string $navigationGroup = 'Archives';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
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
                TextColumn::make('name')
                    ->label('Name')->searchable()->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
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
            'index' => Pages\ListArchiveDocuments::route('/'),
            'create' => Pages\CreateArchiveDocument::route('/create'),
            'edit' => Pages\EditArchiveDocument::route('/{record}/edit'),
        ];
    }
}
