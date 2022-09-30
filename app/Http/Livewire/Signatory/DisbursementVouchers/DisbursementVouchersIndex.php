<?php

namespace App\Http\Livewire\Signatory\DisbursementVouchers;

use App\Models\DisbursementVoucher;
use App\Models\DisbursementVoucherStep;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DisbursementVouchersIndex extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return DisbursementVoucher::whereSignatoryId(auth()->id());
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('tracking_number'),
            TextColumn::make('submitted_at')
                ->label('Date Submitted')
                ->dateTime('h:i A F j, Y'),
            TextColumn::make('status')->formatStateUsing(fn ($record) => ($record->current_step_id > 4000 || $record->previous_step_id > 4000) ? 'Signed' : 'To Sign'),
        ];
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
                ->visible(fn ($record) => $record->current_step_id == 3000)
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
                ->visible(fn ($record) => $record->current_step_id == 4000)
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
                ->visible(fn ($record) => $record->current_step->process != 'Forwarded to')
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
            ActionGroup::make([
                ViewAction::make('progress')
                    ->label('Progress')
                    ->icon('ri-loader-4-fill')
                    ->button()
                    ->outlined()
                    ->modalHeading('Disbursement Voucher Progress')
                    ->modalContent(fn ($record) => view('components.disbursement_vouchers.disbursement_voucher_progress', [
                        'disbursement_voucher' => $record,
                        'steps' => DisbursementVoucherStep::where('id', '>', 2000)->get(),
                    ])),
                ViewAction::make('logs')
                    ->label('Activity Timeline')
                    ->icon('ri-list-check-2')
                    ->button()
                    ->outlined()
                    ->modalHeading('Disbursement Voucher Activity Timeline')
                    ->modalContent(fn ($record) => view('components.disbursement_vouchers.disbursement_voucher_logs', [
                        'disbursement_voucher' => $record,
                    ])),
                ViewAction::make('view')
                    ->label('Preview')
                    ->url(fn ($record) => route('disbursement-vouchers.show', ['disbursement_voucher' => $record]), true),
            ])->icon('ri-eye-line'),
        ];
    }

    public function render()
    {
        return view('livewire.signatory.disbursement-vouchers.disbursement-vouchers-index');
    }
}
