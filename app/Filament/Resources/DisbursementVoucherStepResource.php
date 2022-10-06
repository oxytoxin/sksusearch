<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DisbursementVoucherStepResource\Pages;
use App\Filament\Resources\DisbursementVoucherStepResource\RelationManagers;
use App\Models\DisbursementVoucherStep;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DisbursementVoucherStepResource extends Resource
{
    protected static ?string $model = DisbursementVoucherStep::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 20;
    protected static ?string $navigationGroup = 'Miscellaneous';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->unique('disbursement_voucher_steps', 'id', fn ($record) => $record)
                    ->required(),
                Forms\Components\TextInput::make('process')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('recipient')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('sender')
                    ->maxLength(191),
                Forms\Components\Select::make('office_id')
                    ->options(\App\Models\Office::pluck('name', 'id'))
                    ->searchable(),
                Forms\Components\TextInput::make('return_step_id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('process'),
                Tables\Columns\TextColumn::make('recipient'),
                Tables\Columns\TextColumn::make('sender'),
                Tables\Columns\TextColumn::make('office.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListDisbursementVoucherSteps::route('/'),
            'create' => Pages\CreateDisbursementVoucherStep::route('/create'),
            'edit' => Pages\EditDisbursementVoucherStep::route('/{record}/edit'),
        ];
    }
}
