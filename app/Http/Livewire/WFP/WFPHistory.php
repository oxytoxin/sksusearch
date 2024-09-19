<?php

namespace App\Http\Livewire\WFP;

use Carbon\Carbon;
use App\Models\Wfp;
use Filament\Tables;
use Livewire\Component;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class WFPHistory extends Component implements HasTable
{
    use InteractsWithTable;
    public $cost_centers;

    public function mount()
    {
        $this->cost_centers = Auth::user()->employee_information->office->cost_centers;
    }

    protected function getTableQuery()
    {
        return Wfp::query()->whereIn('cost_center_id', $this->cost_centers->pluck('id')->toArray());
    }

    protected function getTableColumns()
    {
        return [
            Tables\Columns\TextColumn::make('wfpType.description')->label('WFP Type')->searchable(),
            Tables\Columns\TextColumn::make('fundClusterWfp.name')->label('Fund Cluster')->searchable(),
            Tables\Columns\TextColumn::make('costCenter.mfo.name')->label('MFO')->searchable(),
            Tables\Columns\TextColumn::make('fund_description')->searchable(),
            Tables\Columns\TextColumn::make('created_at')
            ->label('Date Created')
            ->formatStateUsing(fn ($record) => Carbon::parse($record->created_at)->format('F d, Y h:i A'))
            ->searchable()->sortable(),
        ];
    }

    public function getTableActions()
    {
        return [
            Action::make('view wfp')
            ->button()
            ->icon('heroicon-o-eye')
            ->url(fn ($record): string => route('wfp.print-wfp', $record)),
            Action::make('view ppmp')
            ->button()
            ->icon('heroicon-o-eye')
            ->url(fn ($record): string => route('wfp.print-ppmp', $record))
        ];
    }

    public function render()
    {
        return view('livewire.w-f-p.w-f-p-history');
    }
}
