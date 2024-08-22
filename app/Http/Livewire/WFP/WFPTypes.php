<?php

namespace App\Http\Livewire\WFP;

use Filament\Tables;
use Livewire\Component;
use App\Models\WpfType as WFPTypesModel;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;

class WFPTypes extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return WFPTypesModel::query();
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('type')
            ->formatStateUsing(fn ($state) => ucfirst($state))
            ->wrap()
            ->searchable(),
            Tables\Columns\TextColumn::make('description')
            ->wrap()
            ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
            ->formatStateUsing(fn ($state) => Carbon::parse($state)->format('F d, Y'))
        ];
    }
    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('create_new')
            ->icon('ri-folder-add-line')
            ->label('Create New WFP Type')
            ->button()
            ->form([
                Select::make('type')
                ->options([
                    'quarterly' => 'Quarterly',
                    'yearly' => 'Yearly',
                ])->required(),
                Textarea::make('description')
                ->required()

            ])->action(function ($data) {
                WFPTypesModel::create([
                    'type' => $data['type'],
                    'description' => $data['description']
                ]);

                Notification::make()->title('Operation Success')->body('WFP Type Successfully Added')->success()->send();
            })
        ];
    }

    public function render()
    {
        return view('livewire.w-f-p.w-f-p-types');
    }
}
