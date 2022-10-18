<?php

namespace App\Http\Livewire\PettyCashVouchers;

use App\Forms\Components\Flatpickr;
use App\Forms\Components\SlimRepeater;
use App\Models\EmployeeInformation;
use App\Models\FundCluster;
use App\Models\PettyCashFund;
use App\Models\PettyCashFundRecord;
use App\Models\PettyCashVoucher;
use App\Models\User;
use DB;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Livewire\Component;

class PettyCashVouchersCreate extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;
    public $petty_cash_fund;


    protected function getFormStatePath(): string
    {
        return 'data';
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('requisitioner_id')->label('Requisitioner')->searchable()->required()->options(EmployeeInformation::pluck('full_name', 'user_id')),
            Select::make('signatory_id')->label('Signatory')->searchable()->required()->options(EmployeeInformation::pluck('full_name', 'user_id')),
            Grid::make(2)->schema([
                TextInput::make('entity_name'),
                Select::make('fund_cluster_id')->label('Fund Cluster')->required()->options(FundCluster::pluck('name', 'id')),
                TextInput::make('pcv_number')->label('PCV Number')->required(),
                Flatpickr::make('pcv_date')->disableTime()->label('PCV Date')->default(today()->format('Y-m-d'))->required(),
                TextInput::make('payee')->required(),
                TextInput::make('responsibility_center')->required(),
            ]),
            TextInput::make('grand_total')->maxValue($this->petty_cash_fund->voucher_limit)->disabled()->default(0)->extraInputAttributes(['class' => 'text-right'])->numeric(),
            SlimRepeater::make('particulars')->schema([
                TextInput::make('name')->required()->disableLabel(),
                TextInput::make('amount')->numeric()->required()->disableLabel(),
            ])->default([
                [],
            ])->minItems(1)->reactive()->columns(2),
        ];
    }

    public function save()
    {
        $this->form->validate();
        $this->petty_cash_fund->refresh();
        if ($this->petty_cash_fund->latest_petty_cash_fund_record?->running_balance < $this->data['grand_total']) {
            Notification::make()->title('Insufficient petty cash fund balance.')->danger()->send();
            return;
        }
        DB::beginTransaction();
        $pcv = PettyCashVoucher::create([
            'custodian_id' => auth()->id(),
            'signatory_id' => $this->data['signatory_id'],
            'requisitioner_id' => $this->data['requisitioner_id'],
            'petty_cash_fund_id' => $this->petty_cash_fund->id,
            'tracking_number' => PettyCashVoucher::generateTrackingNumber(),
            'entity_name' => $this->data['entity_name'],
            'fund_cluster_id' => $this->data['fund_cluster_id'],
            'pcv_number' => $this->data['pcv_number'],
            'pcv_date' => $this->data['pcv_date'],
            'payee' => $this->data['payee'],
            'responsibility_center' => $this->data['responsibility_center'],
            'particulars' => collect($this->data['particulars'])->values(),
            'amount_granted' => collect($this->data['particulars'])->sum('amount'),
        ]);
        foreach ($pcv->particulars as $key => $particular) {
            $pcv->petty_cash_fund_records()->create([
                'type' => PettyCashFundRecord::DISBURSEMENT,
                'petty_cash_fund_id' => $this->petty_cash_fund->id,
                'running_balance' => (PettyCashFundRecord::wherePettyCashFundId($this->petty_cash_fund->id)->latest()->first()?->running_balance ?? 0) - $particular['amount'],
            ]);
        }
        DB::commit();
        Notification::make()->title('Petty Cash Voucher request created.')->send();
    }

    public function mount()
    {
        $campus = auth()->user()->employee_information->office?->campus_id;
        if (!$campus) {
            abort(403, 'Employee has no office assigned.');
        }
        $this->petty_cash_fund = PettyCashFund::whereCampusId($campus)->first();
        if (!$this->petty_cash_fund) {
            abort(403, 'No petty cash fund found for your campus.');
        }

        if ($this->petty_cash_fund->latest_petty_cash_fund_record?->running_balance == 0 || !$this->petty_cash_fund->latest_petty_cash_fund_record?->running_balance) {
            abort(403, 'Petty Cash Fund has insufficient balance.');
        }
        return $this->form->fill();
    }

    public function render()
    {
        $this->data['grand_total'] = collect($this->data['particulars'])->sum(fn ($item) => $item['amount'] == '' ? 0 : $item['amount']);
        return view('livewire.petty-cash-vouchers.petty-cash-vouchers-create');
    }
}
