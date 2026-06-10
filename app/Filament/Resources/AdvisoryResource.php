<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\Advisory;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\AdvisoryResource\Pages;

class AdvisoryResource extends Resource
{
    protected static ?string $model = Advisory::class;

    protected static ?string $modelLabel = 'Advisory';

    protected static ?string $pluralModelLabel = 'Advisories';

    protected static ?string $navigationIcon = 'heroicon-o-speakerphone';

    protected static ?string $navigationLabel = 'Advisories';

    protected static ?string $navigationGroup = 'Others';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(1)
                    ->schema([
                        TextInput::make('title')->required()->maxLength(255),
                    ]),
                Grid::make(2)
                    ->schema([
                        DatePicker::make('published_at')
                            ->label('Date Uploaded')
                            ->default(now())
                            ->required(),
                    ]),
                Grid::make(1)
                    ->schema([
                        Textarea::make('description')
                            ->label('Short Description')
                            ->rows(3)
                            ->required()
                            ->maxLength(500),
                        FileUpload::make('file_path')
                            ->label('Advisory PDF')
                            ->directory('advisories')
                            ->acceptedFileTypes(['application/pdf'])
                            ->preserveFilenames()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('published_at')
                    ->label('Date Uploaded')
                    ->formatStateUsing(fn ($record) => Carbon::parse($record->published_at)->format('F d, Y'))
                    ->sortable(),
                TextColumn::make('description')->label('Short Description')->limit(60)->wrap(),
            ])
            ->defaultSort('published_at', 'desc')
            ->actions([
                Tables\Actions\Action::make('view_pdf')
                    ->label('View PDF')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(fn ($record) => Storage::disk('public')->url($record->file_path))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make()->color('success'),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAdvisories::route('/'),
            'create' => Pages\CreateAdvisory::route('/create'),
            'edit' => Pages\EditAdvisory::route('/{record}/edit'),
        ];
    }
}
