<?php

namespace App\View\Components;

use App\Models\DisbursementVoucher;
use App\Models\LiquidationReport;
use App\Models\TravelOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;

class Sidenav extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $dv_to_sign = DisbursementVoucher::whereSignatoryId(auth()->id())
            ->where('current_step_id', '<=', 4000)
            ->where('previous_step_id', '<=', 4000)
            ->whereNull('cancelled_at')
            ->count();
        $unliquidated_count = DisbursementVoucher::query()
            ->doesntHave('liquidation_report', 'and', function (Builder $query) {
                $query->whereNull('cancelled_at');
            })
            ->whereRelation('voucher_subtype', 'voucher_type_id', 1)
            ->whereNot('voucher_subtype_id', 69)
            ->whereUserId(auth()->id())
            ->whereNotNull('cheque_number')
            ->count();
        $lr_to_sign = LiquidationReport::whereSignatoryId(auth()->id())->whereNull('cancelled_at')->count();
        $to_to_sign = TravelOrder::query()->whereRelation('signatories', 'user_id', auth()->id())->count();
        return view('components.sidenav', [
            'dv_to_sign' => $dv_to_sign,
            'unliquidated_count' => $unliquidated_count,
            'lr_to_sign' => $lr_to_sign,
            'to_to_sign' => $to_to_sign
        ]);
    }
}
