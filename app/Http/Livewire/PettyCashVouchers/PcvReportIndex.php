<?php

namespace App\Http\Livewire\PettyCashVouchers;

use App\Forms\Components\Flatpickr;
use App\Models\FundCluster;
use App\Models\PettyCashFund;
use App\Models\PettyCashVoucher;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class PcvReportIndex extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;
    public $petty_cash_fund;

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema()
    {
        return [
            Grid::make(2)->schema([
                DatePicker::make('date_from')
                    ->default(today()->subMonth())
                    ->reactive()
                    ->lte('date_to'),
                DatePicker::make('date_to')
                    ->default(today())
                    ->reactive()
                    ->gte('date_from'),
                TextInput::make('entity_name')
                    ->default('SKSU')
                    ->reactive(),
                TextInput::make('report_no')
                    ->reactive(),
                Select::make('fund_cluster_id')
                    ->placeholder('All')
                    ->reactive()
                    ->label('Fund Cluster')
                    ->options(FundCluster::pluck('name', 'id')),
                TextInput::make('sheet_no')
                    ->reactive(),
            ])
        ];
    }

    public function mount()
    {
        $this->petty_cash_fund = auth()->user()->petty_cash_fund;
        if (!$this->petty_cash_fund) {
            abort(403, 'No petty cash fund found for your campus.');
        }
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.petty-cash-vouchers.pcv-report-index', [
            'fund_clusters' => FundCluster::all(),
            'petty_cash_vouchers' => PettyCashVoucher::query()
                ->where('petty_cash_fund_id', $this->petty_cash_fund->id)
                ->whereIsLiquidated(true)
                ->when($this->data['fund_cluster_id'], fn ($query) => $query->where('fund_cluster_id', $this->data['fund_cluster_id']))
                ->whereBetween('pcv_date', [$this->data['date_from'], Carbon::make($this->data['date_to'])->addDay()])
                ->orderBy('pcv_date')
                ->get(),
        ]);
    }
}
