<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use App\Models\DisbursementVoucher;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;

class DisbursementVouchersUnliquidated extends Component implements HasTable
{
    use InteractsWithTable, OfficeDashboardActions;

    protected function getTableQuery(): Builder|Relation
    {
        return DisbursementVoucher::query()
            ->doesntHave('liquidation_report', 'and', function (Builder $query) {
                $query->whereNull('cancelled_at');
            })
            ->whereRelation('voucher_subtype', 'voucher_type_id', 1)
            ->whereNot('voucher_subtype_id', 69)
            ->whereUserId(auth()->id())
            ->whereNotNull('cheque_number');
    }

    protected function getTableColumns()
    {
        return [
            ...$this->officeTableColumns(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('liquidate')->button()->url(fn($record) => route('requisitioner.liquidation-reports.create', [
                'disbursement_voucher' => $record
            ])),

            Action::make('view_notices')
                ->label('View Notices')
                ->color('primary')
                ->icon('heroicon-o-bell')
                ->button()
                ->url(fn($record) => route('requisitioner.disbursement-vouchers.notices', [
                    'disbursement_voucher' => $record->id,
                ]))
                ->visible(
                    fn($record) =>
                    // show only if there are related notice histories
                    $record->cash_advance_reminder &&
                        $record->cash_advance_reminder->caReminderStepHistories()->exists()
                ),
        ];
    }

    public function render()
    {
        return view('livewire.requisitioner.disbursement-vouchers.disbursement-vouchers-unliquidated');
    }
}
