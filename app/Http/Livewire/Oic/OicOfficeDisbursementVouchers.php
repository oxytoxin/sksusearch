<?php

namespace App\Http\Livewire\Oic;

use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\DisbursementVoucher;
use App\Models\EmployeeInformation;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Layout;
use Filament\Forms\Components\Select;
use App\Models\DisbursementVoucherStep;
use App\Models\OicUser;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;

class OicOfficeDisbursementVouchers extends Component implements HasTable
{
    use InteractsWithTable, OfficeDashboardActions;

    public function isOic()
    {
        return true;
    }

    protected function getTableQuery()
    {
        return DisbursementVoucher::query();
    }

    public function getTableColumns()
    {
        return [
            ...$this->officeTableColumns()
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
                    $query->whereRelation('current_step', 'office_id', '=', User::find($state)->first()?->employee_information->office_id);
                }),
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
                $description = 'Disbursement voucher certified.';
                if ($this->isOic()) {
                    $description .= "\nOIC: " . auth()->user()->employee_information->full_name . '.';
                }
                $record->activity_logs()->create([
                    'description' => $description,
                ]);
                DB::commit();
                Notification::make()->title('Disbursement voucher certified.')->success()->send();
            })
                ->visible(fn ($record, $livewire) => $record->current_step_id == 13000 && !$record->certified_by_accountant && User::find($livewire->tableFilters['as']['value'])?->employee_information->position_id == 12)
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
                $description = 'Disbursement Voucher returned to ' . $record->current_step->recipient . '.';
                if ($this->isOic()) {
                    $description .= "\nOIC: " . auth()->user()->employee_information->full_name . '.';
                }
                $record->activity_logs()->create([
                    'description' => $description,
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
            ...$this->viewActions(),
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

    public function render()
    {
        return view('livewire.oic.oic-office-disbursement-vouchers');
    }
}
