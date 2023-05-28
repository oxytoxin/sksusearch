<?php

namespace App\Http\Livewire\PettyCashVouchers;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;

class AccountantPettyCashDashboard extends Component implements HasForms
{
    use InteractsWithForms;

    public function render()
    {
        return view('livewire.petty-cash-vouchers.accountant-petty-cash-dashboard');
    }
}
