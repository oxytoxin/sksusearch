<?php

namespace App\Http\Livewire\Requisitioner\LiquidationReports;

use Livewire\Component;
use App\Models\LiquidationReport;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use App\Models\DisbursementVoucherStep;
use App\Models\LiquidationReportStep;
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
        return LiquidationReport::whereForCancellation(false)->whereUserId(auth()->id())->latest();
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('tracking_number'),
            TextColumn::make('disbursement_voucher.tracking_number'),
            TextColumn::make('report_date')->date()->label('Date'),
        ];
    }

    public function getTableActions()
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
                    return $record->current_step_id == 1000;
                })
                ->requiresConfirmation(),
            Action::make('Forward')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                if ($record->current_step_id >= ($record->previous_step_id ?? 0)) {
                    $record->update([
                        'current_step_id' => $record->current_step->next_step->id,
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
                    return $record->current_step_id == 2000;
                })
                ->requiresConfirmation(),
            Action::make('Cancel')->action(function ($record) {
                DB::beginTransaction();
                $record->update([
                    'for_cancellation' => true,
                ]);
                $record->refresh();
                $record->activity_logs()->create([
                    'description' => 'Cancellation requested by ' . auth()->user()->employee_information->full_name,
                ]);
                if ($record->current_step_id < 4000) {
                    $record->update([
                        'cancelled_at' => now(),
                    ]);
                    $record->activity_logs()->create([
                        'description' => 'Cancellation approved.',
                    ]);
                    DB::commit();
                    Notification::make()->title('Liquidation Report cancelled.')->success()->send();
                    return;
                }
                DB::commit();
                Notification::make()->title('Liquidation Report requested for cancellation.')->success()->send();
            })
                ->visible(fn ($record) => !$record->cheque_number && !$record->cancelled_at && !$record->for_cancellation && !$record->certified_by_accountant)
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
                    ->modalHeading('Liquidation Report Related Documents')
                    ->modalContent(fn ($record) => view('components.liquidation_reports.liquidation-report-verified-documents', [
                        'liquidation_report' => $record,
                    ])),
                ViewAction::make('view')
                    ->label('Preview')
                    ->openUrlInNewTab()
                    ->url(fn ($record) => route('requisitioner.liquidation-reports.show', ['liquidation_report' => $record]), true),
            ])->icon('ri-eye-line'),
        ];
    }

    public function render()
    {
        return view('livewire.requisitioner.liquidation-reports.liquidation-reports-index');
    }
}
