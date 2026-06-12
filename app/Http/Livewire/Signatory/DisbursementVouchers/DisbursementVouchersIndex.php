<?php

namespace App\Http\Livewire\Signatory\DisbursementVouchers;

use Livewire\Component;
use App\Models\VoucherSubType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
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
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use App\Http\Controllers\NotificationController;
use App\Jobs\SendSmsJob;

class DisbursementVouchersIndex extends Component implements HasTable
{
    use InteractsWithTable, OfficeDashboardActions;

    protected function getTableQuery()
    {
        return DisbursementVoucher::whereSignatoryId(auth()->id())->latest('submitted_at');
    }

    protected function getTableColumns()
    {
        return [
            ...$this->officeTableColumns(),
            TextColumn::make('status')->formatStateUsing(fn ($record) => $record->for_cancellation ? ($record->cancelled_at ? 'Cancelled' : 'For Cancellation') : (($record->current_step_id > 4000 || $record->previous_step_id > 4000) ? 'Signed' : 'To Sign')),
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
                        'current_step_id' => $record->current_step->next_step->id,
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
                        'current_step_id' => $record->current_step->next_step->id,
                    ]);
                    if ($record->travel_order_id && in_array($record->voucher_subtype_id, [6, 7])) {
                        $actual_itinerary = $record->travel_order?->itineraries()->whereIsActual(true)->first();
                        if (!$actual_itinerary) {
                            DB::rollBack();
                            Notification::make()->title('Actual itinerary not found.')->warning()->send();
                            return false;
                        } else {
                            $actual_itinerary->update([
                                'approved_at' => now(),
                            ]);
                        }
                    }
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

                // ========== SMS NOTIFICATION (DISABLED) ==========
                // Per Memo No. 75, s. 2025 (Annex A): NO SMS on DV movement/forward — it
                // floods the requisitioner on every hop. They track status via their
                // dashboard. DV SMS fires only on submit, return/disapproval, and
                // check/ADA issuance. Re-enable by uncommenting if the policy changes.
                // $record->load(['user.employee_information', 'current_step']);
                // $trackingNumber = $record->tracking_number;
                // $officerName = auth()->user()->employee_information->full_name ?? 'Officer';
                // $nextRecipient = $record->current_step->recipient ?? 'the next office';
                // $message = "Your DV with ref. no. {$trackingNumber} has been approved by {$officerName} and forwarded to {$nextRecipient}.";
                // $requestedBy = $record->user;
                // if ($requestedBy && $requestedBy->employee_information && !empty($requestedBy->employee_information->contact_number)) {
                //     SendSmsJob::dispatch(
                //         $requestedBy->employee_information->contact_number,
                //         $message,
                //         'disbursement_voucher_forwarded',
                //         $requestedBy->id,
                //         auth()->id()
                //     );
                // }
                // ========== SMS NOTIFICATION END ==========

                Notification::make()->title('Document Forwarded')->success()->send();
            })
                ->form(function () {
                    return [
                        Placeholder::make('confirmation')
                            ->label('Important!')
                            ->content(
                                function ($record) {
                                    if (in_array($record->voucher_subtype_id, [6, 7]))
                                        return new HtmlString("By forwarding this transaction, you are concurring in the contents of the Disbursement Voucher <br/>(including its supporting documents) and the related Actual Itinerary of Travel and are hereby approving the same.");
                                    else
                                        return new HtmlString("By forwarding this transaction, you are concurring in the contents of the Disbursement Voucher <br/>(including its supporting documents) and are hereby approving the same.");
                                }
                            ),
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
                    return $record->current_step_id == 4000 && !$record->for_cancellation;
                })
                ->requiresConfirmation(),
            Action::make('return')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                $destinationStep = DisbursementVoucherStep::find($data['return_step_id']);
                $record->update([
                    'pending_return_step_id' => $data['return_step_id'],
                ]);
                $record->activity_logs()->create([
                    'description' => 'DV marked for return to ' . ($destinationStep->recipient ?? 'Unknown') . '. Awaiting physical release.',
                    'remarks' => $data['remarks'] ?? null,
                ]);
                DB::commit();

                // ========== SMS NOTIFICATION ==========
                $record->load(['user.employee_information']);
                $trackingNumber = $record->tracking_number;
                $officerName = auth()->user()->employee_information->full_name ?? 'Officer';
                $remarks = $data['remarks'] ?? 'No remarks provided';
                $remarks = strip_tags($remarks);
                $remarks = html_entity_decode($remarks, ENT_QUOTES, 'UTF-8');
                $message = "Your DV with ref. no. {$trackingNumber} has been returned by {$officerName} with the following remarks: \"{$remarks}\". Please retrieve your documents immediately.";
                $requestedBy = $record->user;
                if ($requestedBy && $requestedBy->employee_information && !empty($requestedBy->employee_information->contact_number)) {
                    SendSmsJob::dispatch(
                        $requestedBy->employee_information->contact_number,
                        $message,
                        'disbursement_voucher_returned',
                        $requestedBy->id,
                        auth()->id()
                    );
                }
                // ========== SMS NOTIFICATION END ==========

                // ========== EMAIL NOTIFICATION (PREPARED, DISABLED) ==========
                // No live email provider / verified domain yet. The full sender is
                // built and ready in dispatchReturnEmail(); re-enable by uncommenting
                // the line below once email is set up.
                // $this->dispatchReturnEmail($record, $officerName, $remarks);
                // ========== EMAIL NOTIFICATION END ==========

                Notification::make()->title('DV marked for return. Use "Release Document" when the hardcopy is picked up.')->success()->send();
            })
                ->color('danger')
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 4000 && $record->for_cancellation == false && blank($record->pending_return_step_id);
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
            ...$this->releaseAction(),
            Action::make('Cancel')->action(function ($record) {
                DB::beginTransaction();
                $record->update([
                    'cancelled_at' => now(),
                ]);
                $record->activity_logs()->create([
                    'description' => 'Cancellation approved.',
                ]);
                DB::commit();
                Notification::make()->title('Disbursement voucher approved for cancellation.')->success()->send();
                return;
            })
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 4000 && $record->for_cancellation && !$record->cancelled_at;
                })
                ->requiresConfirmation()
                ->button()
                ->color('danger'),
            ...$this->viewActions(),
        ];
    }

    public function render()
    {
        return view('livewire.signatory.disbursement-vouchers.disbursement-vouchers-index');
    }
}
