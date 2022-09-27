<?php

namespace App\Http\Livewire\Signatory\TravelOrders;

use App\Models\TravelOrder;
use Livewire\Component;
use WireUi\Traits\Actions;

class TravelOrdersToSignView extends Component
{
    use Actions;
    public TravelOrder $travel_order;

    public $modal=false;
    public $limit = 2;
    public $note="";

    public function render()
    {
        return view('livewire.signatory.travel-orders.travel-orders-to-sign-view');
    }

    public function showDialog(){
    
    }

    public function showMore(){
        $this->limit+=5;    
    }

    public function addNote(){
    
        $this->validate(["note"=>"required|min:10"]);

        $notes = $this->travel_order->sidenotes()->create(['content'=>$this->note,'user_id'=>auth()->id()]);
        $this->modal = false;
        $this->note = "";
        $this->dialog()->success(
            $title = 'Note attached',
            $description = 'Your note has been attached and saved!',
            $icon = 'success',
        );
    
    }

    public function approve(){
    
        $temp=$this->travel_order->signatories()->wherePivot('user_id',auth()->id())->update(["is_approved" => true,]);
        $this->dialog()->success(
            $title = 'Approved',
            $description = 'Travel order approved!',
            $icon = 'success',
        );
        
    }
    
}
