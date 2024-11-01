<?php

namespace App\Http\Livewire\WFP;

use App\Models\CategoryGroup;
use Filament\Tables;
use Livewire\Component;
use App\Models\CostCenter;
use App\Models\WpfType;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;

class FundAllocation extends Component implements HasTable
{
    use InteractsWithTable;


    public $wfp_type;
    public $fund_cluster;
    public $group_keys = [];

    public function mount()
    {
        $this->fund_cluster = 1;
        $this->wfp_type = WpfType::all()->count();
        $group = CategoryGroup::all()->pluck('name', 'id');

        foreach($group as $key => $value) {
            $this->group_keys[$value] = 0;
        }
    }

    protected function getTableQuery()
    {
        return CostCenter::query()->where('fund_cluster_w_f_p_s_id', $this->fund_cluster);
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('name')
            ->wrap()
            ->searchable(),
            Tables\Columns\TextColumn::make('office.name')
            ->wrap()->label('Office')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('head')
            ->getStateUsing(function ($record) {
                return $record->office->head_employee?->full_name;
            }),
            Tables\Columns\TextColumn::make('mfo.name')->label('MFO')
            ->wrap()->searchable()->sortable(),
            Tables\Columns\TextColumn::make('fundClusterWFP.name')
            ->label('Fund Cluster')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('mfoFee.name')
            ->label('MFO Fee')->searchable()->sortable()->wrap(),
            Tables\Columns\TextColumn::make('fundAllocations.wpf_type_id')
            ->getStateUsing(function ($record) {
                return $record->fundAllocations->first()?->wpfType->description;
            })
            ->label('WFP Type'),
            Tables\Columns\TextColumn::make('fundAllocations.amount')
            ->label('Amount')
            ->formatStateUsing(function ($record) {
                if($record->fundClusterWFP->id === 1 || $record->fundClusterWFP->id === 3) {
                    $sum = $record->fundAllocations->where('cost_center_id', $record->id)->where('wpf_type_id', $record->fundAllocations->first()?->wpf_type_id)->sum('initial_amount');
                    return '₱ '.number_format($sum, 2);
                }else
                {
                    return '₱ '.number_format($record->fundAllocations->sum('initial_amount'), 2);

                }
            }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('allocate_fund')
            ->icon('ri-money-dollar-circle-line')
            ->label('Allocate Fund')
            ->button()
            ->url(fn (CostCenter $record): string => route('wfp.allocate-funds', $record))
            // ->form([
            //     Select::make('wpf_type_id')
            //     ->label('WFP Type')
            //     ->required()
            //     ->options(WpfType::all()->pluck('description', 'id')),
            //     TextInput::make('amount')
            //     ->label('Amount')
            //     ->required()
            //     ->mask(fn (TextInput\Mask $mask) => $mask
            //         ->numeric()
            //         ->thousandsSeparator(','),
            // )
            // ])
            // ->action(function($data, $record) {
            //     $record->fundAllocations()->create([
            //         'wpf_type_id' => $data['wpf_type_id'],
            //         'amount' => $data['amount']
            //     ]);

            //     Notification::make()->title('Operation Success')->body('Fund Successfully Added')->success()->send();

            // })
            ->requiresConfirmation()
            ->visible(fn ($record) => !$record->fundAllocations()->exists()),
            Action::make('edit_fund')
            ->icon('ri-pencil-line')
            ->label('Edit fund')
            ->button()
            ->color('warning')
            ->url(fn (CostCenter $record): string => route('wfp.edit-allocate-funds', $record))
            // ->mountUsing(fn ($record, Forms\ComponentContainer $form) => $form->fill([
            //     'wpf_type_id' => $record->fundAllocations->first()->wpf_type_id,
            //     'amount' => $record->fundAllocations->sum('amount')
            // ]))
            // ->form([
            //     Select::make('wpf_type_id')
            //     ->label('WFP Type')
            //     ->required()
            //     ->options(WpfType::all()->pluck('description', 'id')),
            //     TextInput::make('amount')
            //     ->label('Amount')
            //     ->required()
            //     ->mask(fn (TextInput\Mask $mask) => $mask
            //         ->numeric()
            //         ->thousandsSeparator(','),
            //     )
            // ])
            // ->action(function($data, $record) {
            //     $record->fundAllocations()->update([
            //         'wpf_type_id' => $data['wpf_type_id'],
            //         'amount' => $data['amount']
            //     ]);

            //     Notification::make()->title('Operation Success')->body('Fund Successfully Updated')->success()->send();

            // })
            // ->requiresConfirmation()
            ->visible(fn ($record) => $record->fundAllocations()->exists() && !$record->fundAllocations()->where('is_locked', true)->exists()),
            Action::make('lock_fund')
            ->icon('ri-lock-line')
            ->label('Lock fund')
            ->button()
            ->color('danger')
            ->requiresConfirmation()
            ->action(function($record) {
                $record->fundAllocations()->update([
                    'is_locked' => true
                ]);

                Notification::make()->title('Operation Success')->body('Fund Successfully Locked')->success()->send();

            })
            ->visible(fn ($record) => $record->fundAllocations()->exists() && !$record->fundAllocations()->where('is_locked', true)->exists())
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('mfo')
            ->label('MFO')
            ->relationship('mfo', 'name')
        ];
    }

    public function filter($id)
    {
        $this->fund_cluster = $id;
    }

    public function render()
    {
        return view('livewire.w-f-p.fund-allocation');
    }
}
