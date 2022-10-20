<?php

namespace App\Http\Livewire\PettyCashVouchers;

use Livewire\Component;
use App\Models\FundCluster;
use App\Models\PettyCashFundRecord;
use Carbon\Carbon;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class PettyCashFundRecordIndex extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;

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
        return view('livewire.petty-cash-vouchers.petty-cash-fund-record-index', [
            'fund_clusters' => FundCluster::all(),
            'petty_cash_fund_records' => $this->petty_cash_fund
                ->petty_cash_fund_records()
                ->whereBetween('created_at', [
                    $this->data['date_from'],
                    Carbon::make($this->data['date_to'])->addDay(),
                ])
                ->get()
        ]);
    }
}
