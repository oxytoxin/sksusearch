<?php

namespace App\Http\Livewire\Offices;

use App\Models\ActivityLogType;
use App\Models\DisbursementVoucher;
use App\Models\FundCluster;
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
                    if ($record->current_step_id == 8000) {
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
            Action::make('Forward')->button()->action(function ($record) {
                if ($this->canBeForwarded($record)) {
                    DB::beginTransaction();
                    $record->update([
                        'current_step_id' => $record->current_step_id + 1000,
                    ]);
                    $record->refresh();
                    $record->activity_logs()->create([
                        'activity_log_type_id' => ActivityLogType::DISBURSEMENT_VOUCHER_LOG,
                        'description' => $record->current_step->process.' '.$record->current_step->recipient.' by '.auth()->user()->employee_information->full_name,
                    ]);
                    DB::commit();
                    Notification::make()->title('Document Forwarded')->success()->send();
                }
            })
                ->visible(fn ($record) => $this->canBeForwarded($record))
                ->requiresConfirmation(),
            Action::make('ors_burs')->label('ORS/BURS')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                $record->update([
                    'ors_burs' => $data['ors_burs'],
                    'fund_cluster_id' => $data['fund_cluster_id'],
                    'current_step_id' => $record->current_step_id + 1000,
                ]);
                $record->refresh();
                $record->activity_logs()->create([
                    'activity_log_type_id' => ActivityLogType::DISBURSEMENT_VOUCHER_LOG,
                    'description' => $record->current_step->process,
                ]);
                DB::commit();
                Notification::make()->title('ORS/BURS and Fund Cluster updated.')->success()->send();
            })
                ->visible(fn ($record) => $record->current_step_id == 9000)
                ->form(function () {
                    return [
                        Select::make('fund_cluster_id')
                            ->options(FundCluster::pluck('name', 'id'))
                            ->required(),
                        TextInput::make('ors_burs')
                            ->label('ORS/BURS')
                            ->required(),
                    ];
                })
                ->requiresConfirmation(),
            Action::make('verify')->button()->action(function ($record, $data) {
                DB::beginTransaction();
                $record->update([
                    'dv_number' => $data['dv_number'],
                    'current_step_id' => $record->current_step_id + 1000,
                ]);
                $record->refresh();
                $record->activity_logs()->create([
                    'activity_log_type_id' => ActivityLogType::DISBURSEMENT_VOUCHER_LOG,
                    'description' => $record->current_step->process.' '.$record->current_step->recipient,
                ]);
                DB::commit();
                Notification::make()->title('Disbursement Voucher verified.')->success()->send();
            })
                ->visible(fn ($record) => $record->current_step_id == 10000)
                ->form(function () {
                    return [
                        TextInput::make('dv_number')
                            ->label('DV Number')
                            ->required(),
                    ];
                })
                ->requiresConfirmation(),
            ViewAction::make('view')->modalContent(fn ($record) => view('components.disbursement_vouchers.disbursement_voucher_view', ['disbursement_voucher' => $record])),
        ];
    }

    private function canBeForwarded($record)
    {
        return ($record->current_step->process == 'Received in' && ! in_array($record->current_step_id, [8000, 11000, 16000]))
            || ($record->current_step_id == 11000 && $record->certified_by_accountant)
            || ($record->current_step_id == 16000 && filled($record->cheque_number));
    }

    public function render()
    {
        return view('livewire.offices.office-dashboard');
    }
}
