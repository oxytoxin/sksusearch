<?php

namespace App\Http\Livewire\Requisitioner;

use App\Models\VoucherCategory;
use Livewire\Component;

class TransactionsIndex extends Component
{
    public function render()
    {
        return view('livewire.requisitioner.transactions-index', [
            'voucher_types' => VoucherCategory::find(1)->voucher_types()->with(['voucher_subtypes'])->get(),
        ]);
    }
}
