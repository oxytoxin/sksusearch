<?php

namespace App\Http\Livewire\Requisitioner\TravelOrders;

use App\Models\EmployeeInformation;
use App\Models\TravelOrder;
use App\Models\User;
use Livewire\Component;

class TravelOrdersShow extends Component
{
    public TravelOrder $travel_order;

    public function mount()
    {
        $this->travel_order->load([
            'immediate_supervisors.signature',
            'immediate_supervisors.employee_information',
            'recommending_approval.signature',
            'recommending_approval.employee_information',
            'university_president.signature',
            'university_president.employee_information',
            'signatories.signature',
            'signatories.employee_information.position',
        ]);

        $oicUsers = User::with(['signature', 'employee_information.position'])
            ->findMany($this->travel_order->signatories->pluck('pivot.approved_by_oic_id')->filter()->unique())
            ->keyBy('id');

        $this->travel_order->signatories->each(function (User $signatory) use ($oicUsers) {
            if ($signatory->pivot?->approved_by_oic_id) {
                $signatory->pivot->setRelation('oic', $oicUsers->get($signatory->pivot->approved_by_oic_id));
            }
        });
    }

    public function render()
    {
        return view('livewire.requisitioner.travel-orders.travel-orders-show');
    }
}
