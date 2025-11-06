<?php

namespace App\Http\Livewire\Requisitioner\DisbursementVouchers;

use Livewire\Component;
use App\Models\DisbursementVoucher;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class DisbursementVouchersLiquidated extends Component implements HasTable
{
    use InteractsWithTable, OfficeDashboardActions;

    protected function getTableQuery(): Builder|Relation
    {
        return DisbursementVoucher::query()
            ->whereHas('liquidation_report', function (Builder $query) {
                $query->where('current_step_id', '>=', 8000);
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
            Action::make('view')
                ->button()
                ->url(fn($record) => route('requisitioner.liquidation-reports.show', [
                    'liquidation_report' => $record->liquidation_report
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
        return view('livewire.requisitioner.disbursement-vouchers.disbursement-vouchers-liquidated');
    }
}
