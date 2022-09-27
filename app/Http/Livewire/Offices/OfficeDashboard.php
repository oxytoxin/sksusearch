<?php

namespace App\Http\Livewire\Offices;

use App\Forms\Components\Flatpickr;
use App\Models\ActivityLogType;
use App\Models\DisbursementVoucher;
use App\Models\DisbursementVoucherStep;
use App\Models\FundCluster;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OfficeDashboard extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return DisbursementVoucher::whereRelation('current_step', 'office_id', '=', auth()->user()->employee_information->office_id);
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('tracking_number'),
            TextColumn::make('user.employee_information.full_name')
                ->label('Applicant'),
            TextColumn::make('submitted_at')->dateTime('h:i A F d, Y'),
        ];
    }

    protected function getTableActions()
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
                        'activity_log_type_id' => ActivityLogType::DISBURSEMENT_VOUCHER_LOG,
                        'description' => $record->current_step->process.' '.$record->current_step->recipient.' by '.auth()->user()->employee_information->full_name,
                    ]);
                    if ($record->current_step_id == 8000 || $record->current_step_id == 11000) {
                        $record->update([
                            'current_step_id' => $record->current_step_id + 1000,
                        ]);
                        $record->refresh();
                        $record->activity_logs()->create([
                            'activity_log_type_id' => ActivityLogType::DISBURSEMENT_VOUCHER_LOG,
                            'description' => $record->current_step->process,
                        ]);
                    }
                    DB::commit();
                    Notification::make()->title('Document Received')->success()->send();
                }
            })
                ->visible(fn ($record) => $record->current_step->process == 'Forwarded to')
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
                        'activity_log_type_id' => ActivityLogType::DISBURSEMENT_VOUCHER_LOG,
                        'description' => $record->current_step->process.' '.$record->current_step->recipient.' by '.auth()->user()->employee_information->full_name,
                        'remarks' => $data['remarks'] ?? null,
                    ]);
                    DB::commit();
                    Notification::make()->title('Document Forwarded')->success()->send();
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
            Action::make('ors_burs')->label('ORS/BURS')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                $record->update([
                    'ors_burs' => $data['ors_burs'],
                    'fund_cluster_id' => $data['fund_cluster_id'],
                ]);
                $record->activity_logs()->create([
                    'activity_log_type_id' => ActivityLogType::DISBURSEMENT_VOUCHER_LOG,
                    'description' => 'ORS/BURS and Fund Cluster assigned to Disbursement Voucher',
                ]);
                DB::commit();
                Notification::make()->title('ORS/BURS and Fund Cluster updated.')->success()->send();
            })
                ->visible(fn ($record) => $record->current_step_id == 9000 && (blank($record->ors_burs) || blank($record->fund_cluster_id)))
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
            Action::make('verify')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                $record->update([
                    'dv_number' => $data['dv_number'],
                    'journal_date' => $data['journal_date'],
                ]);
                $record->refresh();
                $record->activity_logs()->create([
                    'activity_log_type_id' => ActivityLogType::DISBURSEMENT_VOUCHER_LOG,
                    'description' => 'Disbursement Voucher verified.',
                ]);
                DB::commit();
                Notification::make()->title('Disbursement Voucher verified.')->success()->send();
            })
                ->visible(fn ($record) => $record->current_step_id == 12000 && blank($record->journal_date) && blank($record->dv_number))
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
            Action::make('cheque_ada')->label('Cheque/ADA')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                $record->update([
                    'cheque_number' => $data['cheque_number'],
                    'current_step_id' => $record->current_step_id + 1000,
                ]);
                $record->activity_logs()->create([
                    'activity_log_type_id' => ActivityLogType::DISBURSEMENT_VOUCHER_LOG,
                    'description' => 'Cheque/ADA made for requisitioner.',
                ]);
                DB::commit();
                Notification::make()->title('Cheque/ADA made for requisitioner.')->success()->send();
            })
                ->visible(fn ($record) => $record->current_step_id == 17000 && blank($record->cheque_number))
                ->form(function () {
                    return [
                        TextInput::make('cheque_number')
                            ->label('Cheque number/ADA')
                            ->required(),
                    ];
                })
                ->requiresConfirmation(),
            Action::make('return')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                $record->update([
                    'current_step_id' => $data['return_step_id'],
                    'previous_step_id' => DisbursementVoucherStep::where('process', 'Forwarded to')->where('recipient', $record->current_step->recipient)->first()->id,
                ]);
                $record->refresh();
                $record->activity_logs()->create([
                    'activity_log_type_id' => ActivityLogType::DISBURSEMENT_VOUCHER_LOG,
                    'description' => 'Disbursement Voucher returned to '.$record->current_step->recipient,
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
            ViewAction::make('view')->modalContent(fn ($record) => view('components.disbursement_vouchers.disbursement_voucher_view', ['disbursement_voucher' => $record])),
        ];
    }

    private function canBeForwarded($record)
    {
        return ($record->current_step->process == 'Received in' && ! in_array($record->current_step_id, [9000, 13000, 17000]))
            || ($record->current_step_id == 9000 && filled($record->ors_burs) && filled($record->fund_cluster_id))
            || ($record->current_step_id == 12000 && filled($record->journal_date) && filled($record->dv_number))
            || ($record->current_step_id == 13000 && $record->certified_by_accountant)
            || ($record->current_step_id == 18000 && filled($record->cheque_number));
    }

    public function render()
    {
        return view('livewire.offices.office-dashboard');
    }
}
