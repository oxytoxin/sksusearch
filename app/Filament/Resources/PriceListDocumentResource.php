<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\PriceListDocument;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PriceListDocumentResource\Pages;
use App\Filament\Resources\PriceListDocumentResource\RelationManagers;

class PriceListDocumentResource extends Resource
{
    protected static ?string $model = PriceListDocument::class;

    protected static ?string $modelLabel = 'Price List Document';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 38;

    protected static ?string $navigationLabel = 'Price List Document';

    protected static ?string $navigationGroup = 'Work & Financial Plan';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                ->schema([
                    TextInput::make('description')->required(),
                ]),
                Grid::make(2)
                ->schema([
                    DatePicker::make('revised_date')->required(),
                    DatePicker::make('effective_date')->required(),
                ]),
                Grid::make(1)
                ->schema([
                FileUpload::make('path')->directory('pricelist-attachments')->preserveFilenames()->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('description')->searchable()->sortable(),
                TextColumn::make('revised_date')->formatStateUsing(fn($record) => Carbon::parse($record->revised_date)->format('F d, Y'))->searchable()->sortable(),
                TextColumn::make('effective_date')->formatStateUsing(fn($record) => Carbon::parse($record->effective_date)->format('F d, Y'))->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->button(),
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
            'index' => Pages\ListPriceListDocuments::route('/'),
            'create' => Pages\CreatePriceListDocument::route('/create'),
            'edit' => Pages\EditPriceListDocument::route('/{record}/edit'),
        ];
    }
}
