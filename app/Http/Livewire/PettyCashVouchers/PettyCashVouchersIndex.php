<?php

namespace App\Http\Livewire\PettyCashVouchers;

use App\Models\PettyCashFund;
use App\Models\PettyCashVoucher;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;

class PettyCashVouchersIndex extends Component implements HasTable
{
    use InteractsWithTable;

    public $petty_cash_fund;

    protected function getTableQuery(): Builder|Relation
    {
        return PettyCashVoucher::whereRelation('petty_cash_fund', 'campus_id', $this->petty_cash_fund->campus_id)->orderByDesc('created_at');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('tracking_number'),
            TextColumn::make('payee'),
            TextColumn::make('requisitioner.employee_information.full_name')->label('Requisitioner'),
            TextColumn::make('amount_granted')->formatStateUsing(fn ($state) => 'P' . number_format($state, 2)),
            TextColumn::make('amount_paid')->formatStateUsing(fn ($state) => 'P' . number_format($state, 2)),
            TextColumn::make('pcv_date')->date(),
        ];
    }

    public function getTableActions()
    {
        return [
            ViewAction::make()->modalContent(fn ($record) => view('livewire.petty-cash-vouchers.views.pcv-details', ['pcv' => $record])),
        ];
    }

    public function mount()
    {
        $this->petty_cash_fund = auth()->user()->petty_cash_fund;
        if (!$this->petty_cash_fund) {
            abort(403, 'No petty cash fund found for your campus.');
        }
    }

    public function render()
    {
        return view('livewire.petty-cash-vouchers.petty-cash-vouchers-index');
    }
}
