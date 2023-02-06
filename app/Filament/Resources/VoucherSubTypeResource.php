<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherSubTypeResource\Pages;
use App\Forms\Components\ArrayField;
use App\Forms\Components\SlimRepeater;
use App\Models\VoucherSubType;
use App\Models\VoucherType;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\MultiSelectFilter;
use Filament\Tables\Filters\SelectFilter;

class VoucherSubTypeResource extends Resource
{
    protected static ?string $model = VoucherSubType::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Sub Type';

    protected static ?string $navigationGroup = 'Vouchers';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('voucher_type_id')
                    ->label('Type')
                    ->options(VoucherType::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Grid::make(1)->schema([
                    SlimRepeater::make('documents')
                        ->label('Required Documents')
                        ->schema([
                            TextInput::make('name')
                                ->label('')
                                ->required(),
                        ]),
                    SlimRepeater::make('liquidation_report_documents')
                        ->label('Liquidation Report Required Documents')
                        ->schema([
                            TextInput::make('name')
                                ->label('')
                                ->required(),
                        ])
                        ->visible(fn ($context, $record) => $context == 'create' || $record->voucher_type_id == 1),
                ])
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
                TextColumn::make('voucher_type.name')
                    ->label('Type')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
            ])
            ->filters([
                SelectFilter::make('voucher_type_id')
                    ->label('Type')
                    ->multiple()
                    ->options(VoucherType::pluck('name', 'id')),
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
            'index' => Pages\ListVoucherSubTypes::route('/'),
            'create' => Pages\CreateVoucherSubType::route('/create'),
            'edit' => Pages\EditVoucherSubType::route('/{record}/edit'),
        ];
    }
}
