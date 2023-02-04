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
        $office_final_step_id = auth()->user()->employee_information->office->office_group->disbursement_voucher_final_step->id;
        if ($office_final_step_id == 20000) {
            $office_final_step_id = 6000;
        }
        return DisbursementVoucher::whereForCancellation(false)->where('current_step_id', '>', $office_final_step_id)->latest();
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
                        fn (Builder $query, $date): Builder => $query->whereDate('submitted_at', '>=', $date),
                    )
                    ->when(
                        $data['to'],
                        fn (Builder $query, $date): Builder => $query->whereDate('submitted_at', '<=', $date),
                    );
            })
        ];
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('tracking_number'),
            TextColumn::make('user.employee_information.full_name')
                ->label('Requisitioner'),
            TextColumn::make('payee')
                ->label('Payee'),
            TextColumn::make('submitted_at')->label('Created by Requisitioner at')->dateTime('F d, Y'),
            TextColumn::make('disbursement_voucher_particulars_sum_amount')->sum('disbursement_voucher_particulars', 'amount')->label('Amount')->money('php'),
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
