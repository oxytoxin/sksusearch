<?php

namespace App\Http\Livewire\WFP;

use DB;
use Livewire\Component;
use WireUi\Traits\Actions;
use App\Models\WfpRequestedSupply;
use App\Models\WfpRequestTimeline;
use Filament\Notifications\Notification;

class ViewSupplyRequest extends Component
{
    public $record;
    public $assignSupplyCode = false;
    public $supply_code;
    use Actions;

    public function mount($record)
    {
        $this->record = WfpRequestedSupply::find($record);
    }

    public function forwardToSupply()
    {
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Forward this request to supply?',
            'acceptLabel' => 'Yes, forward it',
            'method'      => 'forwardRequestSupply',
            'params'      => $this->record->id,
        ]);
    }

    public function forwardToAccounting()
    {
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Forward this request to accounting?',
            'acceptLabel' => 'Yes, forward it',
            'method'      => 'forwardRequestAccounting',
            'params'      => $this->record->id,
        ]);
    }

    public function forwardRequestAccounting()
    {

    }

    public function forwardRequestSupply($record)
    {
       $this->record = WfpRequestedSupply::find($record);
       DB::beginTransaction();
       $this->record->update([
        'status' => 'Forwarded to Supply',
        'is_approved_supply' => 1,
        ]);

        WfpRequestTimeline::create([
            'wfp_request_id' => $this->record->id,
            'user_id' => auth()->id(),
            'activity' => 'Forwarded to Supply',
            'remarks' => 'Forwarded to Supply',
        ]);
        DB::commit();

        Notification::make()->title('Operation Success')->body('Request has been forwarder to supply and to be validated')->success()->send();
        return redirect()->route('wfp.request-supply-view', $record);
    }

    public function updateSupplyCode()
    {
        $this->validate([
            'supply_code' => 'required',
        ]);
        DB::beginTransaction();
        $this->record->update([
            'supply_code' => $this->supply_code,
        ]);
        $this->record->wfpRequestTimeline()->create([
            'user_id' => auth()->id(),
            'activity' => 'Forward to Accounting',
            'remarks' => 'Supply Code Assigned',
        ]);
        DB::commit();


        $this->dialog()->success(
            $title = 'Operation Successful',
            $description = 'Supply code has been successfully updated',
        );

        return redirect()->route('wfp.request-supply-view', $this->record->id);
    }

    public function render()
    {
        return view('livewire.w-f-p.view-supply-request');
    }
}
