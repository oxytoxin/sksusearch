<?php

namespace App\Http\Livewire\Signatory\DisbursementVouchers;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\DisbursementVoucher;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Layout;
use Filament\Forms\Components\Select;
use App\Models\DisbursementVoucherStep;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;

class DisbursementVouchersIndex extends Component implements HasTable
{
    use InteractsWithTable, OfficeDashboardActions;

    protected function getTableQuery()
    {
        return DisbursementVoucher::whereSignatoryId(auth()->id())->latest();
    }

    protected function getTableColumns()
    {
        return [
            ...$this->officeTableColumns(),
            TextColumn::make('status')->formatStateUsing(fn ($record) => ($record->current_step_id > 4000 || $record->previous_step_id > 4000) ? 'Signed' : 'To Sign'),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('for_cancellation')->options([
                true => 'For Cancellation',
                false => 'For Approval',
            ])->default(0)->label('Status'),
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

    public function getTableActions()
    {
        return [
            Action::make('Receive')->button()->action(function (DisbursementVoucher $record) {
                if ($record->current_step->process == 'Forwarded to') {
                    DB::beginTransaction();
                    $record->update([
                        'current_step_id' => $record->current_step_id + 1000,
                    ]);
                    $record->refresh();
                    $record->activity_logs()->create([
                        'description' => $record->current_step->process . ' ' . auth()->user()->employee_information->full_name,
                    ]);
                    DB::commit();
                    Notification::make()->title('Document Received')->success()->send();
                }
            })
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 3000 && $record->for_cancellation == false;
                })
                ->requiresConfirmation(),
            Action::make('Forward')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                if ($record->current_step_id >= ($record->previous_step_id ?? 0)) {
                    $record->update([
                        'current_step_id' => $record->current_step_id + 1000,
                    ]);
                } else {
                    $record->update([
                        'current_step_id' => $record->previous_step_id,
                    ]);
                }
                $record->refresh();
                $record->activity_logs()->create([
                    'description' => $record->current_step->process . ' ' . $record->current_step->recipient . ' by ' . auth()->user()->employee_information->full_name,
                    'remarks' => $data['remarks'] ?? null,
                ]);
                DB::commit();
                Notification::make()->title('Document Forwarded')->success()->send();
            })
                ->form(function () {
                    return [
                        RichEditor::make('remarks')
                            ->label('Remarks (Optional)')
                            ->fileAttachmentsDisk('remarks'),
                    ];
                })
                ->modalWidth('4xl')
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 4000 && $record->for_cancellation == false;
                })
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
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 4000 && $record->for_cancellation == false;
                })
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

    public function render()
    {
        return view('livewire.signatory.disbursement-vouchers.disbursement-vouchers-index');
    }
}
