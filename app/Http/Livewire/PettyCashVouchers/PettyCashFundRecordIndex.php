<?php

namespace App\Http\Livewire\PettyCashVouchers;

use App\Models\Campus;
use Livewire\Component;
use App\Models\FundCluster;
use App\Models\PettyCashFund;
use App\Models\PettyCashFundRecord;
use Carbon\Carbon;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class PettyCashFundRecordIndex extends Component implements HasForms
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
                    ->label('Date Until')
                    ->gte('date_from'),
                Select::make('campus_id')
                    ->required()
                    ->disablePlaceholderSelection()
                    ->options(Campus::pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(function () {
                        $original_pcf = $this->petty_cash_fund;
                        $this->petty_cash_fund = PettyCashFund::whereCampusId($this->data['campus_id'])->first();
                        if (!$this->petty_cash_fund) {
                            Notification::make()->title('No petty cash fund found for your campus.')->danger()->send();
                            $this->petty_cash_fund = $original_pcf;
                        }
                    })
                    ->visible(fn () => auth()->user()->employee_information->position_id == 15 && auth()->user()->employee_information->office_id == 3)
                    ->label('Campus'),
                TextInput::make('entity_name')
                    ->default('SKSU')
                    ->reactive(),
                Select::make('fund_cluster_id')
                    ->placeholder('All')
                    ->reactive()
                    ->label('Fund Cluster')
                    ->options(FundCluster::whereIn('id', [1, 2, 3, 8])->pluck('name', 'id')),

            ])
        ];
    }

    public function mount()
    {
        $campus_id = auth()->user()->employee_information?->campus_id;
        $this->petty_cash_fund = PettyCashFund::whereCampusId($campus_id)->first();
        if (!$this->petty_cash_fund) {
            abort(403, 'No petty cash fund found for your campus.');
        }
        $this->form->fill();
        $this->data['campus_id'] = $campus_id;
    }

    public function render()
    {
        return view('livewire.petty-cash-vouchers.petty-cash-fund-record-index', [
            'fund_clusters' => FundCluster::all(),
            'petty_cash_fund_records' => $this->petty_cash_fund
                ->petty_cash_fund_records()
                ->when($this->data['date_from'], function ($query) {
                    $query->whereDate('created_at', '>=', $this->data['date_from']);
                })
                ->when($this->data['date_to'], function ($query) {
                    $query->whereDate('created_at', '<=', Carbon::make($this->data['date_to'])->addDay());
                })
                ->get()
        ]);
    }
}
