<?php

namespace App\Http\Livewire\Offices;

use Livewire\Component;
use App\Models\DisbursementVoucher;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Http\Livewire\Offices\Traits\OfficeDashboardActions;

class OfficeDisbursementVouchersIndex extends Component implements HasTable
{
    use InteractsWithTable, OfficeDashboardActions;

    protected $listeners = ['refresh' => '$refresh'];

    protected function getTableQuery()
    {
        $office_final_step_id = auth()->user()->employee_information->office->disbursement_voucher_final_step->id;
        return DisbursementVoucher::whereForCancellation(false)->where('current_step_id', '>', $office_final_step_id)->latest();
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
        return view('livewire.offices.office-disbursement-vouchers-index');
    }
}
