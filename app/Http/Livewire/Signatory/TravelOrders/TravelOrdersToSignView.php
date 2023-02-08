<?php

namespace App\Http\Livewire\Signatory\TravelOrders;

use App\Models\Itinerary;
use App\Models\TravelOrder;
use App\Models\User;
use Livewire\Component;
use WireUi\Traits\Actions;

class TravelOrdersToSignView extends Component
{
    use Actions;

    public $rejectionNote;

    public TravelOrder $travel_order;

    public $modal = false;
    public $modalRejection = false;

    public $limit = 3;

    public $note = '';

    public $from_oic = false;
    public $oic = false;
    public $oic_signatory;

    public function mount()
    {
        $this->travel_order->load('applicants.employee_information');
        if (request('from_oic')) {
            $this->from_oic = (bool) request('from_oic');
            $this->oic_signatory =  (int) request('oic_signatory');
        }
    }


    public function render()
    {
        return view('livewire.signatory.travel-orders.travel-orders-to-sign-view', [
            'sidenotes' => $this->travel_order->sidenotes()->limit($this->limit)->with('user.employee_information')->get(),
            'itineraries' => $this->travel_order->itineraries()->with('user.employee_information')->get(),
        ]);
    }

    public function showDialog()
    {
    }

    public function approveItinerary(Itinerary $itinerary)
    {
        $itinerary->update([
            'approved_at' => now()
        ]);
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
            $this->travel_order->signatories()->updateExistingPivot($this->oic_signatory, ['is_approved' => true]);
            $this->travel_order->sidenotes()->create(['content' => 'Travel order approved as OIC for: ' . User::find($this->oic_signatory)?->employee_information->full_name, 'user_id' => auth()->id()]);
        } else {
            $this->travel_order->signatories()->updateExistingPivot(auth()->id(), ['is_approved' => true]);
        }
        $this->travel_order->refresh();
        $this->dialog()->success(
            $title = 'Approved',
            $description = 'Travel order approved!',
            $icon = 'success',
        );
    }
    public function reject()
    {
        $this->validate(['rejectionNote' => 'required|min:10']);
        $this->modalRejection = false;
        $this->dialog()->id('custom')->confirm([
            "icon" => 'question',
            "iconColor" => 'text-primary-500',
            'accept'  => [
                'label'  => 'Proceed',
                'method' => 'rejectFinal',
            ],
        ]);
    }

    public function rejectFinal()
    {
        if ($this->from_oic) {
            $this->travel_order->signatories()->updateExistingPivot($this->oic_signatory, ['is_approved' => 2]);
            $this->travel_order->sidenotes()->create(['content' => 'Travel order Rejected as OIC for: ' . User::find($this->oic_signatory)?->employee_information->full_name, 'user_id' => auth()->id()]);
            $this->travel_order->sidenotes()->create(['content' => 'Travel order Rejected for this/these reason/s : ' . $this->rejectionNote, 'user_id' => auth()->id()]);
        } else {
            $this->travel_order->signatories()->updateExistingPivot(auth()->id(), ['is_approved' => 2]);
            $this->travel_order->sidenotes()->create(['content' => 'Travel order Rejected for this/these reason/s : ' . $this->rejectionNote, 'user_id' => auth()->id()]);
        }
        $this->travel_order->refresh();
        $this->dialog()->success(
            $title = 'Operation Completed',
            $description = 'Travel order rejected!',
            $icon = 'success',
        );
    }
}
