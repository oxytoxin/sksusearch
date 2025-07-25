<?php

namespace App\Exports;

use App\Models\CostCenter;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class CostCenterPreExport  implements FromView
{
    public function __construct(public $cost_centers,public $total_allocated,public $total_programmed,public $total_balance)
    {}
    public function view(): View
    {
        return view('exports.cost-center-164', [
            'cost_centers' => $this->cost_centers,
            'total_allocated' => $this->total_allocated,
            'total_programmed' => $this->total_programmed,
            'total_balance' => $this->total_balance,
        ]);
    }
}
