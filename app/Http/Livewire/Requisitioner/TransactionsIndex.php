<?php

namespace App\Http\Livewire\Requisitioner;

use App\Models\VoucherCategory;
use Livewire\Component;

class TransactionsIndex extends Component
{
    public function render()
    {
        return view('livewire.requisitioner.transactions-index', [
            'categories' => VoucherCategory::with(['voucher_types.voucher_subtypes'])->get(),
        ]);
    }
}
