<?php

namespace App\Http\Livewire\Oic;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\DisbursementVoucher;
use App\Models\EmployeeInformation;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Layout;
use Filament\Forms\Components\Select;
use App\Models\DisbursementVoucherStep;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use App\Models\OicUser;
use App\Jobs\SendSmsJob;

class OicSignatoryDisbursementVouchers extends Component implements HasTable
{
    use InteractsWithTable, OfficeDashboardActions;

    protected function getTableQuery()
    {
        return DisbursementVoucher::query();
    }

    protected function getTableColumns()
    {
        return [
            ...$this->officeTableColumns(),
            TextColumn::make('status')->formatStateUsing(fn ($record) => $record->cancelled_at ? 'Cancelled' : (($record->current_step_id > 4000 || $record->previous_step_id > 4000) ? 'Signed' : 'To Sign')),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('as')
                ->searchable()
                ->placeholder('Select User')
                ->options(EmployeeInformation::whereIn('user_id', OicUser::valid()->distinct('user_id')->pluck('user_id'))->pluck('full_name', 'user_id'))
                ->query(function ($query, $state) {
                    $query->where('signatory_id', $state);
                }),
            SelectFilter::make('for_cancellation')->options([
                true => 'For Cancellation',
                false => 'For Approval',
            ])
                ->default(0)->label('Status'),
        ];
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
                        'description' => $record->current_step->process . ' OIC: ' . auth()->user()->employee_information->full_name,
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
                    'description' => $record->current_step->process . ' ' . $record->current_step->recipient . ' by OIC:' . auth()->user()->employee_information->full_name,
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
                    'description' => 'Disbursement Voucher returned to ' . $record->current_step->recipient . ' by OIC:' . auth()->user()->employee_information->full_name,
                    'remarks' => $data['remarks'] ?? null,
                ]);
                DB::commit();

                // ========== SMS NOTIFICATION ==========
                $record->load(['user.employee_information']);
                $trackingNumber = $record->tracking_number;
                $officerName = auth()->user()->employee_information->full_name ?? 'Officer';
                $remarks = $data['remarks'] ?? 'No remarks provided';

                // Strip HTML tags and decode HTML entities from remarks
                $remarks = strip_tags($remarks);
                $remarks = html_entity_decode($remarks, ENT_QUOTES, 'UTF-8');

                $message = "Your DV with ref. no. {$trackingNumber} has been returned by {$officerName} with the following remarks: \"{$remarks}\". Please retrieve your documents immediately.";

                // Send to the user who requested the disbursement voucher
                $requestedBy = $record->user;
                if ($requestedBy && $requestedBy->employee_information && !empty($requestedBy->employee_information->contact_number)) {
                    SendSmsJob::dispatch(
                        '09273464891',  // TEST PHONE - Remove this line for production
                        // $requestedBy->employee_information->contact_number,  // PRODUCTION - Uncomment this
                        $message,
                        'disbursement_voucher_returned',
                        $requestedBy->id,
                        auth()->id()
                    );
                }
                // ========== SMS NOTIFICATION END ==========

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
            Action::make('Cancel')->action(function ($record) {
                DB::beginTransaction();
                $record->update([
                    'cancelled_at' => now(),
                ]);
                $record->activity_logs()->create([
                    'description' => 'Cancellation approved by OIC:' . auth()->user()->employee_information->full_name,
                ]);
                DB::commit();
                Notification::make()->title('Disbursement voucher cancelled.')->success()->send();
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

    protected function getTableFiltersFormColumns(): int
    {
        return 2;
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }


    public function render()
    {
        return view('livewire.oic.oic-signatory-disbursement-vouchers');
    }
}
