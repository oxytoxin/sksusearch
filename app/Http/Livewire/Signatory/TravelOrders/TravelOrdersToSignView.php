<?php

namespace App\Http\Livewire\Signatory\TravelOrders;

use App\Models\TravelOrder;
use App\Models\User;
use Livewire\Component;
use WireUi\Traits\Actions;

class TravelOrdersToSignView extends Component
{
    use Actions;

    public TravelOrder $travel_order;

    public $modal = false;

    public $limit = 2;

    public $note = '';

    public $from_oic = false;
    public $oic = false;
    public $oic_signatory;

    public function mount()
    {
        if (request('from_oic')) {
            $this->from_oic = (bool) request('from_oic');
            $this->oic_signatory =  (int) request('oic_signatory');
        }
    }

    public function render()
    {
        return view('livewire.signatory.travel-orders.travel-orders-to-sign-view');
    }

    public function showDialog()
    {
    }

    public function showMore()
    {
        $this->limit += 5;
    }

    public function addNote()
    {
        $this->validate(['note' => 'required|min:10']);
        $content = '';
        if ($this->from_oic) {
            $content .= "OIC's Note: ";
        }
        $content .= $this->note;
        $this->travel_order->sidenotes()->create(['content' => $content, 'user_id' => auth()->id()]);
        $this->modal = false;
        $this->note = '';
        $this->travel_order->refresh();
        $this->dialog()->success(
            $title = 'Note attached',
            $description = 'Your note has been attached and saved!',
            $icon = 'success',
        );
    }

    public function approve()
    {
        if ($this->from_oic) {
            $this->travel_order->signatories()->wherePivot('user_id', $this->oic_signatory)->update(['is_approved' => true]);
            $this->travel_order->sidenotes()->create(['content' => 'Travel order approved as OIC for: ' . User::find($this->oic_signatory)?->employee_information->full_name, 'user_id' => auth()->id()]);
        } else {
            $this->travel_order->signatories()->wherePivot('user_id', auth()->id())->update(['is_approved' => true]);
        }
        $this->travel_order->refresh();
        $this->dialog()->success(
            $title = 'Approved',
            $description = 'Travel order approved!',
            $icon = 'success',
        );
    }
}
