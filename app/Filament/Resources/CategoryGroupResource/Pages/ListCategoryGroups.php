<?php

namespace App\Filament\Resources\CategoryGroupResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryGroupResource;

class ListCategoryGroups extends ListRecords
{
    protected static string $resource = CategoryGroupResource::class;

    public static ?string $title = 'Account Groups';

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->orderBy('sort_id'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('New Account Group')
            ->color('success'),
        ];
    }
}
