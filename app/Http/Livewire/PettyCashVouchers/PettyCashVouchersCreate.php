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
        $balance = PettyCashFundRecord::wherePettyCashFundId($this->petty_cash_fund->id)->latest()->first()?->running_balance;
        if ($balance > $this->petty_cash_fund->voucher_limit) {
            $limit = $this->petty_cash_fund->voucher_limit;
        } else {
            $limit = $balance;
        }

        return [
            Select::make('requisitioner_id')
                ->label('Requisitioner')
                ->searchable()
                ->required()
                ->options(EmployeeInformation::pluck('full_name', 'user_id'))
                ->reactive()
                ->afterStateUpdated(fn($set, $state) => $set('payee', EmployeeInformation::firstWhere('user_id', $state)?->full_name)),
            Select::make('signatory_id')->label('Signatory')->searchable()->required()->options(EmployeeInformation::pluck('full_name', 'user_id')),
            Grid::make(2)->schema([
                TextInput::make('entity_name')->default('SKSU'),
                Select::make('fund_cluster_id')->label('Fund Cluster')->required()->options(FundCluster::pluck('name', 'id')),
                TextInput::make('payee')->required(),
                TextInput::make('address')->maxLength(191),
                TextInput::make('responsibility_center'),
            ]),
            TextInput::make('grand_total')->maxValue($balance)->disabled()->default(0)->extraInputAttributes(['class' => 'text-right'])->numeric(),
            SlimRepeater::make('particulars')->schema([
                TextInput::make('name')->required()->disableLabel(),
                TextInput::make('amount')->numeric()->required()->disableLabel(),
            ])->default([
                [
                    'name' => '',
                    'amount' => 0,
                ],
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

        if (collect($this->data['particulars'])->sum('amount') > $this->petty_cash_fund->voucher_limit) {
            Notification::make()->title('Petty cash voucher is above voucher limit.')->danger()->send();
            return;
        }
        DB::beginTransaction();
        $pcv_number = PettyCashVoucher::generateTrackingNumber($this->petty_cash_fund);
        $amount = collect($this->data['particulars'])->sum('amount');

        $pcv = PettyCashVoucher::create([
            'custodian_id' => auth()->id(),
            'signatory_id' => $this->data['signatory_id'],
            'requisitioner_id' => $this->data['requisitioner_id'],
            'petty_cash_fund_id' => $this->petty_cash_fund->id,
            'tracking_number' => $pcv_number,
            'entity_name' => $this->data['entity_name'],
            'fund_cluster_id' => $this->data['fund_cluster_id'],
            'pcv_number' => $pcv_number,
            'pcv_date' => now(),
            'payee' => $this->data['payee'],
            'address' => $this->data['address'],
            'responsibility_center' => $this->data['responsibility_center'],
            'particulars' => collect($this->data['particulars'])->values(),
            'amount_granted' => $amount,
            'amount_paid' => $amount < 0 ? $amount : 0,
            'is_liquidated' => $amount < 0,
        ]);

        foreach ($pcv->particulars as $key => $particular) {
            $pcv->petty_cash_fund_records()->create([
                'type' => PettyCashFundRecord::DISBURSEMENT,
                'nature_of_payment' => $particular['name'],
                'amount' => $particular['amount'],
                'petty_cash_fund_id' => $this->petty_cash_fund->id,
                'running_balance' => (PettyCashFundRecord::wherePettyCashFundId($this->petty_cash_fund->id)->latest()->first()?->running_balance ?? 0) - $particular['amount'],
            ]);
        }
        DB::commit();
        Notification::make()->title('Petty Cash Voucher request created.')->success()->send();
        redirect()->route('pcv.index');
    }

    public function mount()
    {

        $this->petty_cash_fund = auth()->user()->petty_cash_fund;
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
        $this->data['grand_total'] = collect($this->data['particulars'])->sum(fn($item) => $item['amount'] == '' ? 0 : $item['amount']);
        return view('livewire.petty-cash-vouchers.petty-cash-vouchers-create');
    }
}
