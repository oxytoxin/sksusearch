<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DesignationResource\Pages;
use App\Filament\Resources\DesignationResource\RelationManagers;
use App\Models\Designation;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DesignationResource extends Resource
{
    protected static ?string $model = Designation::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Assignments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('employee_information_id')
                    ->label('Employee')
                    ->relationship('employee_information', 'full_name')
                    ->preload()
                    ->searchable()
                    ->required(),
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
                    ->relationship('office', 'name', fn($get, $query) => $query->when($get('campus_id'), fn($query, $campus_id) => $query->where('campus_id', $campus_id)))
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
                TextColumn::make('employee_information.full_name')
                    ->label('Employee'),
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListDesignations::route('/'),
            'create' => Pages\CreateDesignation::route('/create'),
            'edit' => Pages\EditDesignation::route('/{record}/edit'),
        ];
    }
}
