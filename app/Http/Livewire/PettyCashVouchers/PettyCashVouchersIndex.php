<?php

namespace App\Http\Livewire\PettyCashVouchers;

use DB;
use Livewire\Component;
use App\Models\PettyCashFund;
use App\Models\PettyCashVoucher;
use App\Models\PettyCashFundRecord;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Jobs\SendSmsJob;

class PettyCashVouchersIndex extends Component implements HasTable
{
    use InteractsWithTable;

    public $petty_cash_fund;

    protected function getTableQuery(): Builder|Relation
    {
        return PettyCashVoucher::whereRelation('petty_cash_fund', 'campus_id', $this->petty_cash_fund->campus_id)->latest('created_at');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('tracking_number'),
            TextColumn::make('payee'),
            TextColumn::make('requisitioner.employee_information.full_name')->label('Requisitioner'),
            TextColumn::make('amount_granted')->formatStateUsing(fn ($state) => 'P' . number_format($state, 2)),
            TextColumn::make('amount_paid')->formatStateUsing(fn ($state) => 'P' . number_format($state, 2)),
            TextColumn::make('pcv_date')->date(),
        ];
    }

    public function getTableActions()
    {
        return [
            Action::make('liquidate')
                ->button()
                ->outlined()
                ->action(function ($record, $data) {
                    if ($data['amount_paid'] > $record->amount_granted) {
                        $balance = PettyCashFundRecord::wherePettyCashFundId($record->petty_cash_fund_id)->latest()->first()?->running_balance;
                        if ($balance - ($data['amount_paid'] - $record->amount_granted) < 0) {
                            Notification::make()->title('Insufficient petty cash fund balance for reimbursement.')->warning()->send();
                            return;
                        }
                    }

                    DB::beginTransaction();
                    $record->update([
                        'amount_paid' => $data['amount_paid'],
                        'is_liquidated' => true,
                    ]);
                    if ($record->net_amount != 0) {
                        if ($record->net_amount > 0) {
                            $record->petty_cash_fund_records()->create([
                                'type' => PettyCashFundRecord::REFUND,
                                'nature_of_payment' => 'Petty Cash Voucher Refund',
                                'amount' => $record->net_amount,
                                'petty_cash_fund_id' => $record->petty_cash_fund_id,
                                'running_balance' => (PettyCashFundRecord::wherePettyCashFundId($record->petty_cash_fund_id)->latest()->first()?->running_balance ?? 0) + $record->net_amount,
                            ]);
                        } else {
                            $record->petty_cash_fund_records()->create([
                                'type' => PettyCashFundRecord::REIMBURSEMENT,
                                'nature_of_payment' => 'Petty Cash Voucher Reimbursement',
                                'amount' => abs($record->net_amount),
                                'petty_cash_fund_id' => $record->petty_cash_fund_id,
                                'running_balance' => (PettyCashFundRecord::wherePettyCashFundId($record->petty_cash_fund_id)->latest()->first()?->running_balance ?? 0) - abs($record->net_amount),
                            ]);
                        }
                    }

                    // Send SMS notification to requisitioner
                    $record->load(['requisitioner.employee_information']);
                    $trackingNumber = $record->tracking_number;
                    $amountPaidFormatted = number_format($data['amount_paid'], 2);

                    // Determine if it's a refund or reimbursement
                    $netAmount = $record->amount_granted - $data['amount_paid'];
                    $netAmountFormatted = number_format(abs($netAmount), 2);

                    if ($netAmount > 0) {
                        // Refund case (user returns money)
                        $refundReimbursementText = "and P{$netAmountFormatted} refunded";
                    } elseif ($netAmount < 0) {
                        // Reimbursement case (user needs more money)
                        $refundReimbursementText = "and P{$netAmountFormatted} reimbursed";
                    } else {
                        // Exact amount
                        $refundReimbursementText = "with no refund or reimbursement";
                    }

                    $message = "Your petty cash with PCV ref. no. {$trackingNumber} has been liquidated for P{$amountPaidFormatted} {$refundReimbursementText}.";

                    // ========== SMS NOTIFICATION (COMMENTED OUT) ==========
                    // $requisitioner = $record->requisitioner;
                    // if ($requisitioner && $requisitioner->employee_information && !empty($requisitioner->employee_information->contact_number)) {
                    //     SendSmsJob::dispatch(
                    //         '09366303145',
                    //         // $requisitioner->employee_information->contact_number,
                    //         $message,
                    //         'petty_cash_voucher_liquidated',
                    //         $requisitioner->id,
                    //         auth()->id()
                    //     );
                    // }
                    // ========== SMS NOTIFICATION END ==========

                    DB::commit();
                    Notification::make()->title('Petty Cash Voucher liquidated.')->success()->send();
                })
                ->icon('heroicon-o-cash')
                ->form(function ($record) {
                    return [
                        TextInput::make('amount_paid')->minValue(0)->label('Amount Paid')->numeric()->required(),
                    ];
                })
                ->visible(fn ($record) => !$record->is_liquidated)
                ->modalWidth('sm'),
            ViewAction::make()->modalContent(fn ($record) => view('livewire.petty-cash-vouchers.views.pcv-details', ['pcv' => $record])),

        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('is_liquidated')
                ->options([
                    true => 'Liquidated',
                    false => 'Unliquidated',
                ])
                ->default(0)
                ->label('Liquidation Status'),
        ];
    }

    protected function getTableFiltersFormColumns(): int
    {
        return 1;
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
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
        return view('livewire.petty-cash-vouchers.petty-cash-vouchers-index');
    }
}
