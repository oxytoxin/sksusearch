<?php

namespace App\Http\Livewire\PettyCashVouchers;

use DB;
use Livewire\Component;
use App\Models\PettyCashFund;
use App\Models\DisbursementVoucher;
use App\Models\PettyCashFundRecord;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Relations\Relation;

class PettyCashFundReplenish extends Component implements HasTable
{
    use InteractsWithTable;

    public $disbursement_voucher_id;
    public $petty_cash_fund;

    protected function getTableQuery(): Builder|Relation
    {
        return PettyCashFundRecord::whereRecordableType(DisbursementVoucher::class)
            ->latest()
            ->wherePettyCashFundId($this->petty_cash_fund->id);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('recordable.tracking_number')->searchable()->label('Tracking Number'),
            TextColumn::make('recordable.total_amount')->label('Amount Replenished')->formatStateUsing(fn ($state) => 'P' . number_format($state, 2)),
            TextColumn::make('created_at')->label('Date')->dateTime('h:i A M d, Y'),
        ];
    }

    protected function getFormSchema(): array
    {
        $used_dvs = PettyCashFundRecord::whereRecordableType(DisbursementVoucher::class)
            ->wherePettyCashFundId($this->petty_cash_fund->id)
            ->pluck('recordable_id')
            ->toArray();
        return [
            Card::make([
                Placeholder::make('dv')->content(fn ($get) => view('livewire.petty-cash-vouchers.views.pcf-replenish-dv-details', [
                    'dv' => DisbursementVoucher::find($get('disbursement_voucher_id')),
                ]))->disableLabel()->visible(fn ($get) => $get('disbursement_voucher_id')),
            ])->visible(fn ($get) => $get('disbursement_voucher_id')),
            Select::make('disbursement_voucher_id')->options(
                DisbursementVoucher::whereUserId(auth()->id())
                    ->whereNotNull('cheque_number')
                    ->whereVoucherSubtypeId(69)
                    ->whereNotIn('id', $used_dvs)
                    ->pluck('tracking_number', 'id'),
            )->required()->searchable()->reactive()->label('Disbursement Voucher'),
        ];
    }

    public function replenish()
    {
        $this->form->validate();
        $dv = DisbursementVoucher::findOrFail($this->disbursement_voucher_id);
        DB::beginTransaction();
        foreach ($dv->disbursement_voucher_particulars as $key => $particular) {
            $dv->petty_cash_fund_records()->create([
                'type' => PettyCashFundRecord::REPLENISHMENT,
                'nature_of_payment' => $particular->purpose,
                'amount' => $particular->amount,
                'petty_cash_fund_id' => $this->petty_cash_fund->id,
                'running_balance' => ((PettyCashFundRecord::wherePettyCashFundId($this->petty_cash_fund->id)->latest()->first()?->running_balance) ?? 0) + $particular->amount,
            ]);
            $this->petty_cash_fund->refresh();
        }
        DB::commit();
        Notification::make()->title('Petty Cash Fund Replenished')->success()->send();
        redirect()->route('pcv.index');
    }

    public function mount()
    {
        $this->petty_cash_fund = auth()->user()->petty_cash_fund;
        if (!$this->petty_cash_fund) {
            abort(403, 'No petty cash fund found for your campus.');
        }
    }

    public function render()
    {
        return view('livewire.petty-cash-vouchers.petty-cash-fund-replenish');
    }
}
