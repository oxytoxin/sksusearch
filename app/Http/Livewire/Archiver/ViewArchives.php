<?php

namespace App\Http\Livewire\Archiver;

use App\Models\DisbursementVoucher;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Component;

class ViewArchives extends Component implements HasTable
{
    use InteractsWithTable;

    protected function getTableQuery()
    {
        return DisbursementVoucher::whereUserId(auth()->id());
    }

    protected function getTableColumns()
    {
        return [
            TextColumn::make('tracking_number'),
            TextColumn::make('user.employee_information.full_name')->label('Requisitioner'),            
            TextColumn::make('payee')
            ->label('Payee'),
            TextColumn::make('disbursement_voucher_particulars_sum_amount')->sum('disbursement_voucher_particulars', 'amount')->label('Amount')->money('php'),
        ];
    }

    public function render()
    {
        return view('livewire.archiver.view-archives');
    }
}
