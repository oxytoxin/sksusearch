<?php

namespace App\Http\Livewire\Signatory\LiquidationReports;

use Livewire\Component;
use App\Models\LiquidationReport;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use App\Models\LiquidationReportStep;
use Filament\Forms\Components\Select;
use App\Models\DisbursementVoucherStep;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Concerns\InteractsWithTable;

class LiquidationReportsIndex extends Component implements HasTable
{

    use InteractsWithTable;

    protected function getTableQuery()
    {
        return LiquidationReport::whereSignatoryId(auth()->id())->whereNull('cancelled_at')->latest('report_date');
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('tracking_number'),
            TextColumn::make('disbursement_voucher.tracking_number'),
            TextColumn::make('report_date')->date()->label('Date'),
            TextColumn::make('status')->formatStateUsing(fn ($record) => $record->for_cancellation ? ($record->cancelled_at ? 'Cancelled' : 'For Cancellation') : (($record->current_step_id > 4000 || $record->previous_step_id > 4000) ? 'Signed' : 'To Sign')),
        ];
    }

    protected function getTableActions()
    {
        return [
            Action::make('Receive')->button()->action(function ($record) {
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
                        Notification::make()->title('Selected document not found.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 3000;
                })
                ->requiresConfirmation(),
            Action::make('Forward')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                if ($record->disbursement_voucher->travel_order_id) {
                    $actual_itinerary = $record->disbursement_voucher->travel_order?->itineraries()->whereIsActual(true)->first();
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
                if ($record->current_step_id >= ($record->previous_step_id ?? 0)) {
                    $record->update([
                        'signatory_date' => now(),
                        'current_step_id' => $record->current_step->next_step->id,
                    ]);
                } else {
                    $record->update([
                        'signatory_date' => now(),
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
                    return $record->current_step_id == 4000 && !$record->for_cancellation;
                })
                ->requiresConfirmation(),
            Action::make('return')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                if ($record->current_step_id < $record->previous_step_id) {
                    $previous_step_id = $record->previous_step_id;
                } else {
                    $previous_step_id = DisbursementVoucherStep::where('process', 'Forwarded to')->where('id', '<', $record->current_step->id)->latest('id')->first()->id;
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
                            ->options(fn ($record) => LiquidationReportStep::where('process', 'Forwarded to')->where('recipient', '!=', $record->current_step->recipient)->where('id', '<', $record->current_step_id)->pluck('recipient', 'id'))
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
                    'description' => 'Cancellation approved.',
                ]);
                DB::commit();
                Notification::make()->title('Liquidation Report approved for cancellation.')->success()->send();
                return;
            })
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 4000 && $record->for_cancellation && !$record->cancelled_at && !$record->certified_by_accountant;
                })
                ->requiresConfirmation()
                ->button()
                ->color('danger'),
            ActionGroup::make([
                ViewAction::make('progress')
                    ->label('Progress')
                    ->icon('ri-loader-4-fill')
                    ->modalHeading('Liquidation Report Progress')
                    ->modalContent(fn ($record) => view('components.timeline_views.progress_logs', [
                        'record' => $record,
                        'steps' => LiquidationReportStep::whereEnabled(true)->where('id', '>', 2000)->get(),
                    ])),
                ViewAction::make('logs')
                    ->label('Activity Timeline')
                    ->icon('ri-list-check-2')
                    ->modalHeading('Liquidation Report Activity Timeline')
                    ->modalContent(fn ($record) => view('components.timeline_views.activity_logs', [
                        'record' => $record,
                    ])),
                ViewAction::make('related_documents')
                    ->label('Related Documents')
                    ->icon('ri-file-copy-2-line')
                    ->modalHeading('Disbursement Voucher Related Documents')
                    ->modalContent(fn ($record) => view('components.liquidation_reports.liquidation-report-verified-documents', [
                        'liquidation_report' => $record,
                    ])),
                ViewAction::make('actual_itinerary')
                    ->label('Actual Itinerary')
                    ->icon('ri-file-copy-line')
                    ->url(fn ($record) => route('signatory.itinerary.print', ['itinerary' => $record->disbursement_voucher->travel_order->itineraries()->whereIsActual(true)->first()]), true)
                    ->visible(fn ($record) => $record->disbursement_voucher->travel_order?->itineraries()->whereIsActual(true)->exists()),
                ViewAction::make('view')
                    ->label('Preview')
                    ->openUrlInNewTab()
                    ->url(fn ($record) => route('signatory.liquidation-reports.show', ['liquidation_report' => $record]), true),
            ])->icon('ri-eye-line'),
        ];
    }

    public function render()
    {
        return view('livewire.signatory.liquidation-reports.liquidation-reports-index');
    }
}
