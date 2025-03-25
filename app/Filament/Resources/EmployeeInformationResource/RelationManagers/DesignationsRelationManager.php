<?php

namespace App\Filament\Resources\EmployeeInformationResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DesignationsRelationManager extends RelationManager
{
    protected static string $relationship = 'designations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('campus_id')
                    ->label('Campus')
                    ->relationship('campus', 'name')
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(fn($set) => $set('office_id', null))
                    ->searchable()
                    ->required(),
                Select::make('office_id')
                    ->label('Office')
                    ->relationship('office', 'name', fn($get, $query) => $query->where('campus_id', $get('campus_id')))
                    ->preload()
                    ->searchable()
                    ->required(),
                Select::make('position_id')
                    ->label('Position')
                    ->relationship('position', 'description')
                    ->preload()
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('campus.name')
                    ->label('Campus'),
                TextColumn::make('office.name')
                    ->label('Office'),
                TextColumn::make('position.description')
                    ->label('Position'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
