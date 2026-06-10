<?php

    namespace App\Http\Livewire\Offices;

    use Livewire\Component;
    use App\Models\DisbursementVoucher;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Contracts\HasTable;
    use Filament\Tables\Concerns\InteractsWithTable;
    use Filament\Forms;
    use Filament\Tables\Filters\Filter;
    use Illuminate\Database\Eloquent\Builder;
    use Filament\Forms\Components\Grid;
    use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;

    class OfficeDisbursementVouchersForwarded extends Component implements HasTable
    {
        use InteractsWithTable, OfficeDashboardActions;

        protected $listeners = ['refresh' => '$refresh'];

        protected function getTableQuery()
        {
            $officeGroupId = auth()->user()->employee_information->office->office_group_id;
            $officeStepIds = \App\Models\DisbursementVoucherStep::where('office_group_id', $officeGroupId)
                ->whereEnabled(true)
                ->pluck('id')
                ->toArray();

            return DisbursementVoucher::whereForCancellation(false)
                ->whereNotIn('current_step_id', $officeStepIds)
                ->where('current_step_id', '>', min($officeStepIds))
                ->latest('submitted_at');
        }


        protected function getTableFilters(): array
        {
            return [

                Filter::make('submitted_at')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('to'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('submitted_at', '>=', $date),
                            )
                            ->when(
                                $data['to'],
                                fn(Builder $query, $date): Builder => $query->whereDate('submitted_at', '<=', $date),
                            );
                    })
            ];
        }

        protected function getTableColumns()
        {
            return [
                TextColumn::make('tracking_number')->searchable(),
                TextColumn::make('user.employee_information.full_name')
                    ->searchable()
                    ->wrap()
                    ->label('Requisitioner'),
                TextColumn::make('payee')
                    ->searchable()
                    ->wrap()
                    ->label('Payee'),
                TextColumn::make('current_step.process')
                    ->label('Status')
                    ->formatStateUsing(fn ($record) => $record->current_step->process . ' ' . $record->current_step->recipient),
                TextColumn::make('submitted_at')->label('Submitted at')->dateTime('F d, Y'),
                TextColumn::make('disbursement_voucher_particulars_sum_amount')->sum('disbursement_voucher_particulars', 'amount')->label('Amount')->money('php', true),
            ];
        }

        protected function getTableActions()
        {
            return [
                ...$this->viewActions(),
            ];
        }

        public function render()
        {
            return view('livewire.offices.office-disbursement-vouchers-forwarded');
        }
    }
