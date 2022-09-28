<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ModeOfPaymentResource\Pages;
use App\Filament\Resources\ModeOfPaymentResource\RelationManagers;
use App\Models\ModeOfPayment;
use App\Models\Mop;
use App\Models\Mot;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModeOfPaymentResource extends Resource
{
    protected static ?string $model = Mop::class;

    protected static ?string $modelLabel = 'Mode Of Payments';

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationLabel = 'Mode Of Payment';

    protected static ?string $navigationGroup = 'Others';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
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
                TextColumn::make('name')->searchable()->sortable(),
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
            'index' => Pages\ListModeOfPayments::route('/'),
            'create' => Pages\CreateModeOfPayment::route('/create'),
            'edit' => Pages\EditModeOfPayment::route('/{record}/edit'),
        ];
    }    
}
