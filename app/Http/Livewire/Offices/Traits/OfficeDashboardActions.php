<?php

namespace App\Http\Livewire\Offices\Traits;

use App\Forms\Components\Flatpickr;
use App\Models\DisbursementVoucher;
use App\Models\DisbursementVoucherStep;
use App\Models\FundCluster;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\View;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

trait OfficeDashboardActions
{
    private function canBeForwarded($record)
    {
        if (!$record) {
            Notification::make()->title('Selected document not found in office.')->warning()->send();
            return;
        }
        return ($record->current_step->process == 'Received in' && !in_array($record->current_step_id, [6000, 9000, 13000, 17000]))
            || ($record->current_step_id == 9000 && filled($record->ors_burs) && filled($record->fund_cluster_id))
            || ($record->current_step_id == 12000 && filled($record->journal_date) && filled($record->dv_number))
            || ($record->current_step_id == 13000 && $record->certified_by_accountant)
            || ($record->current_step_id == 18000 && filled($record->cheque_number))
            || ($record->current_step_id == 6000 && (!$record->voucher_subtype->related_documents_list || filled($record->related_documents)));
    }

    private function viewActions()
    {
        return [
            ActionGroup::make([
                ViewAction::make('progress')
                    ->label('Progress')
                    ->icon('ri-loader-4-fill')
                    ->modalHeading('Disbursement Voucher Progress')
                    ->modalContent(fn ($record) => view('components.disbursement_vouchers.disbursement_voucher_progress', [
                        'disbursement_voucher' => $record,
                        'steps' => DisbursementVoucherStep::where('id', '>', 2000)->get(),
                    ])),
                ViewAction::make('logs')
                    ->label('Activity Timeline')
                    ->icon('ri-list-check-2')
                    ->modalHeading('Disbursement Voucher Activity Timeline')
                    ->modalContent(fn ($record) => view('components.disbursement_vouchers.disbursement_voucher_logs', [
                        'disbursement_voucher' => $record,
                    ])),
                ViewAction::make('related_documents')
                    ->label('Related Documents')
                    ->icon('ri-file-copy-2-line')
                    ->modalHeading('Disbursement Voucher Related Documents')
                    ->modalContent(fn ($record) => view('components.disbursement_vouchers.disbursement_voucher_documents', [
                        'disbursement_voucher' => $record,
                    ])),
                ViewAction::make('view')
                    ->label('Preview')
                    ->openUrlInNewTab()
                    ->url(fn ($record) => route('disbursement-vouchers.show', ['disbursement_voucher' => $record]), true),
            ])->icon('ri-eye-line'),
        ];
    }

    private function icuActions()
    {
        return [
            EditAction::make()
                ->button()
                ->icon('ri-file-copy-2-line')
                ->label('Verify Related Documents')
                ->modalHeading('Verify Related Documents')
                ->action(function ($record, $data) {
                    $record->refresh();
                    DB::beginTransaction();
                    if ($record->voucher_subtype->related_documents_list && blank($record->related_documents)) {
                        $record->update(['related_documents' => [
                            'required_documents' => $record->voucher_subtype->related_documents_list->documents,
                            'verified_documents' => $data['verified_documents'],
                            'remarks' => $data['remarks'] ?? '',
                        ]]);
                        $record->activity_logs()->create([
                            'description' => 'Related documents have been verified.',
                        ]);
                        DB::commit();
                        Notification::make()->title('Related documents have been verified.')->success()->send();
                    }
                    DB::rollBack();
                })
                ->form([
                    CheckboxList::make('verified_documents')
                        ->options(function ($record) {
                            return collect($record?->voucher_subtype->related_documents_list?->documents)->flatMap(fn ($d) => [$d => $d]) ?? [];
                        }),
                    RichEditor::make('remarks')
                ])->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 6000 && $record->voucher_subtype->related_documents_list && blank($record->related_documents);
                })

        ];
    }

    private function cashierActions()
    {
        return [
            Action::make('cheque_ada')->label('Cheque/ADA')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                $record->update([
                    'cheque_number' => $data['cheque_number'],
                    'current_step_id' => $record->current_step_id + 1000,
                ]);
                $record->activity_logs()->create([
                    'description' => 'Cheque/ADA made for requisitioner.',
                ]);
                DB::commit();
                Notification::make()->title('Cheque/ADA made for requisitioner.')->success()->send();
            })
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 17000 && blank($record->cheque_number);
                })
                ->form(function () {
                    return [
                        TextInput::make('cheque_number')
                            ->label('Cheque number/ADA')
                            ->required(),
                    ];
                })
                ->requiresConfirmation(),
        ];
    }

    private function accountingActions()
    {
        return [
            Action::make('verify')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                $record->update([
                    'dv_number' => $data['dv_number'],
                    'journal_date' => $data['journal_date'],
                ]);
                $record->refresh();
                $record->activity_logs()->create([
                    'description' => 'Disbursement Voucher verified.',
                ]);
                DB::commit();
                Notification::make()->title('Disbursement Voucher verified.')->success()->send();
            })
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 12000 && blank($record->journal_date) && blank($record->dv_number);
                })
                ->form(function () {
                    return [
                        TextInput::make('dv_number')
                            ->label('DV Number')
                            ->required(),
                        Flatpickr::make('journal_date')
                            ->disableTime()
                            ->required(),
                    ];
                })
                ->requiresConfirmation(),
        ];
    }

    private function budgetOfficeActions()
    {
        return [
            Action::make('ors_burs')->label('ORS/BURS')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                $record->update([
                    'ors_burs' => $data['ors_burs'],
                    'fund_cluster_id' => $data['fund_cluster_id'],
                ]);
                $record->activity_logs()->create([
                    'description' => 'ORS/BURS and Fund Cluster assigned to Disbursement Voucher',
                ]);
                DB::commit();
                Notification::make()->title('ORS/BURS and Fund Cluster updated.')->success()->send();
            })
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step_id == 9000 && (blank($record->ors_burs) || blank($record->fund_cluster_id));
                })
                ->form(function ($record) {
                    return [
                        Select::make('fund_cluster_id')
                            ->label('Fund Cluster')
                            ->options(FundCluster::pluck('name', 'id'))
                            ->default($record->fund_cluster_id)
                            ->required(),
                        TextInput::make('ors_burs')
                            ->label('ORS/BURS')
                            ->default($record->ors_burs)
                            ->required(),
                    ];
                })
                ->requiresConfirmation(),
        ];
    }

    private function commonActions()
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
                        'description' => $record->current_step->process . ' ' . $record->current_step->recipient . ' by ' . auth()->user()->employee_information->full_name,
                    ]);
                    if ($record->current_step_id == 8000 || $record->current_step_id == 11000) {
                        $record->update([
                            'current_step_id' => $record->current_step_id + 1000,
                        ]);
                        $record->refresh();
                        $record->activity_logs()->create([
                            'description' => $record->current_step->process,
                        ]);
                    }
                    DB::commit();
                    Notification::make()->title('Document Received')->success()->send();
                }
            })
                ->visible(function ($record) {
                    if (!$record) {
                        Notification::make()->title('Selected document not found in office.')->warning()->send();
                        return false;
                    }
                    return $record->current_step->process == 'Forwarded to';
                })
                ->requiresConfirmation(),
            Action::make('Forward')->button()->action(function ($record, $data) {
                if ($this->canBeForwarded($record)) {
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
                } else {
                    Notification::make()->title('Document cannot be forwarded.')->body('Document may have been updated. Please refresh this page.')->success()->send();
                }
            })
                ->form(function () {
                    return [
                        RichEditor::make('remarks')
                            ->label('Remarks (Optional)')
                            ->fileAttachmentsDisk('remarks'),
                    ];
                })
                ->modalWidth('4xl')
                ->visible(fn ($record) => $this->canBeForwarded($record))
                ->requiresConfirmation(),
        ];
    }
}
