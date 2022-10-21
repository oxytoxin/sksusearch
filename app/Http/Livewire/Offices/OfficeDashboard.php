<?php

namespace App\Http\Livewire\Offices;

use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use App\Models\DisbursementVoucher;
use App\Models\DisbursementVoucherStep;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OfficeDashboard extends Component implements HasTable
{
    use InteractsWithTable, OfficeDashboardActions;

    public function mount()
    {
        if (!in_array(auth()->user()->employee_information?->office_id, [2, 3, 5, 51, 52])) {
            abort(403);
        }
    }

    public function render()
    {
        return view('livewire.offices.office-dashboard');
    }

    protected function getTableQuery()
    {
        return DisbursementVoucher::whereForCancellation(false)->whereRelation('current_step', 'office_id', '=', auth()->user()->employee_information->office_id)->latest();
    }

    protected function getTableColumns()
    {
        return [
            ...$this->officeTableColumns()
        ];
    }

    protected function getTableActions()
    {
        return [
            ...$this->commonActions(),
            ...$this->budgetOfficeActions(),
            ...$this->accountingActions(),
            ...$this->cashierActions(),
            ...$this->icuActions(),
            Action::make('certify')->button()->action(function ($record) {
                DB::beginTransaction();
                $record->update([
                    'certified_by_accountant' => true,
                ]);
                $record->activity_logs()->create([
                    'description' => 'Disbursement voucher certified.',
                ]);
                DB::commit();
                Notification::make()->title('Disbursement voucher certified.')->success()->send();
            })
                ->visible(fn ($record) => $record->current_step_id == 13000 && $record->for_cancellation == false && !$record->certified_by_accountant && auth()->user()->employee_information->position_id == 12)
                ->requiresConfirmation(),
            Action::make('return')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                if ($record->current_step_id < $record->previous_step_id) {
                    $previous_step_id = $record->previous_step_id;
                } else {
                    $previous_step_id = DisbursementVoucherStep::where('process', 'Forwarded to')->where('recipient', $record->current_step->recipient)->first()->id;
                }
                $record->update([
                    'current_step_id' => $data['return_step_id'],
                    'previous_step_id' => $previous_step_id,
                ]);
                $record->refresh();
                $record->activity_logs()->create([
                    'description' => 'Disbursement Voucher returned to ' . $record->current_step->recipient,
                    'remarks' => $data['remarks'] ?? null,
                ]);
                DB::commit();
                Notification::make()->title('Disbursement Voucher returned.')->success()->send();
            })
                ->color('danger')
                ->visible(fn ($record) => $record->current_step->process != 'Forwarded to' && $record->for_cancellation == false)
                ->form(function () {
                    return [
                        Select::make('return_step_id')
                            ->label('Return to')
                            ->options(fn ($record) => DisbursementVoucherStep::where('process', 'Forwarded to')->where('recipient', '!=', $record->current_step->recipient)->where('id', '<', $record->current_step_id)->pluck('recipient', 'id'))
                            ->required(),
                        RichEditor::make('remarks')
                            ->label('Remarks (Optional)')
                            ->fileAttachmentsDisk('remarks'),
                    ];
                })
                ->modalWidth('4xl')
                ->requiresConfirmation(),
            ...$this->viewActions(),

        ];
    }
}
