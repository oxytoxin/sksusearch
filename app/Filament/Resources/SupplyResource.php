<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Supply;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SupplyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SupplyResource\RelationManagers;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ToggleColumn;

class SupplyResource extends Resource
{
    protected static ?string $model = Supply::class;

    protected static ?string $modelLabel = 'Supplies';

    protected static ?string $navigationIcon = 'heroicon-o-archive';

    protected static ?int $navigationSort = 35;

    protected static ?string $navigationLabel = 'Items';

    protected static ?string $navigationGroup = 'Work & Financial Plan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    Select::make('category_item_id')
                    ->relationship('categoryItems', 'name', fn (Builder $query) => $query->activeCategoryItems())
                    ->label('Account Title')
                    ->searchable()
                    ->preload()
                    ->required(),
                    Select::make('category_item_budget_id')
                    ->relationship('categoryItemsBudget', 'name')
                    ->label('Account Title Budget')
                    ->searchable()
                    ->preload()
                    ->required(),
                    Select::make('category_group_id')
                    ->relationship('categoryGroups', 'name')
                    ->label('Category Group')
                    ->searchable()
                    ->preload()
                    ->required(),
                    TextInput::make('supply_code')->required(),
                ]),
                Grid::make(1)->schema([
                    Textarea::make('particulars')->required(),
                ]),
                Grid::make(3)->schema([
                    TextInput::make('specifications')->required(),
                    TextInput::make('unit_cost')
                    ->reactive()
                    //   ->mask(fn (TextInput\Mask $mask) => $mask
                    //     ->numeric()
                    //     ->thousandsSeparator(','))
                      ->required(fn ($get) => $get('is_ppmp') ? true : false),
                      TextInput::make('uom')->required(),
                ]),

                  Radio::make('is_ppmp')
                  ->label('Is this PPMP?')
                  ->reactive()
                  ->options([
                      1 => 'Yes',
                      0 => 'No',
                  ])->inline()->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('categoryItems.budgetCategory.name')->wrap()->label('Budget Category'),
                // TextColumn::make('categoryItems.id')->label('Account Title ID')->searchable(isIndividual: true, isGlobal: false)->sortable(),
                TextColumn::make('categoryItems.name')->wrap()->label('Account Title')->searchable(),
                TextColumn::make('categoryItemsBudget.name')->wrap()->label('Account Title Budget')->searchable(),
                TextColumn::make('categoryGroups.name')->wrap()->label('Category Group')->searchable(),
                TextColumn::make('supply_code')->searchable(isIndividual: true, isGlobal: false)->sortable(),
                TextColumn::make('particulars')->wrap()->searchable(isIndividual: true, isGlobal: false)->html()->sortable(),
                //TextColumn::make('specifications')->searchable()->wrap()->sortable(),
                TextColumn::make('unit_cost')->searchable()
                ->formatStateUsing(fn ($record) => '₱ '.number_format($record->unit_cost, 2))->sortable(),
                TextColumn::make('uom')->label('UOM')->searchable()->wrap()->sortable(),
                TextColumn::make('is_ppmp')
                ->label('PPMP')
                ->formatStateUsing(fn ($record) => $record->is_ppmp === 1 ? 'Yes' : 'No')->sortable(),
                ToggleColumn::make('is_active')->label('Active'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
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
            'index' => Pages\ListSupplies::route('/'),
            'create' => Pages\CreateSupply::route('/create'),
            'edit' => Pages\EditSupply::route('/{record}/edit'),
        ];
    }
}
