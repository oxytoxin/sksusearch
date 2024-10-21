<?php

namespace App\Http\Livewire\WFP;

use App\Models\CategoryGroup;
use App\Models\CategoryItems;
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
    public $modifyRequestModal = false;
    public $modify_request_remarks;
    public $rejectRequestModal = false;
    public $reject_request_remarks;
    public $accountingAssignModal = false;
    public $supply_code;
    public $account_titles;
    public $title_groups;
    public $requested_account_title;
    public $requested_category_group;

    use Actions;

    public function mount($record)
    {
        $this->record = WfpRequestedSupply::find($record);
        $this->account_titles = CategoryItems::all();
        $this->title_groups = CategoryGroup::all();
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

    public function forwardRequestAccounting($record)
    {
        $this->record = WfpRequestedSupply::find($record);
        DB::beginTransaction();
        $this->record->update([
         'status' => 'Forwarded to Accounting',
         ]);

         WfpRequestTimeline::create([
             'wfp_request_id' => $this->record->id,
             'user_id' => auth()->id(),
             'activity' => 'Forwarded to Accounting',
             'remarks' => 'Forwarded to Accounting',
         ]);
         DB::commit();

         Notification::make()->title('Operation Success')->body('Request has been forwarded to accounting and to be validated')->success()->send();
         return redirect()->route('wfp.supply-requested-suppluies', $record);
    }

    public function forwardRequestSupply($record)
    {
       $this->record = WfpRequestedSupply::find($record);
       DB::beginTransaction();
       $this->record->update([
        'status' => 'Forwarded to Supply',
        ]);

        WfpRequestTimeline::create([
            'wfp_request_id' => $this->record->id,
            'user_id' => auth()->id(),
            'activity' => 'Forwarded to Supply',
            'remarks' => 'Forwarded to Supply',
        ]);
        DB::commit();

        Notification::make()->title('Operation Success')->body('Request has been forwarded to supply and to be validated')->success()->send();
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
            'status' => 'Supply Code Assigned',
            'is_approved_supply' => 1,
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

    public function modifyRequest()
    {
        $this->modifyRequestModal = true;
    }

    public function modifyRequestSupply($record)
    {
        $this->record = WfpRequestedSupply::find($record);
        $this->record->update([
            'status' => 'Request Modification',
        ]);

        WfpRequestTimeline::create([
            'wfp_request_id' => $this->record->id,
            'user_id' => auth()->id(),
            'activity' => 'Request Modification',
            'remarks' => $this->modify_request_remarks,
        ]);

        Notification::make()->title('Operation Success')->body('Request has been forwarded to user be modified')->success()->send();
        return redirect()->route('wfp.supply-requested-suppluies', $record);
    }

    public function rejectRequest()
    {
        $this->rejectRequestModal = true;
    }

    public function rejectRequestSupply($record)
    {
        $this->record = WfpRequestedSupply::find($record);
        $this->record->update([
            'status' => 'Request Rejected by Supply',
        ]);

        WfpRequestTimeline::create([
            'wfp_request_id' => $this->record->id,
            'user_id' => auth()->id(),
            'activity' => 'Request Rejected by Supply',
            'remarks' => $this->reject_request_remarks,
        ]);

        Notification::make()->title('Operation Success')->body('Request has been rejected')->success()->send();
        return redirect()->route('wfp.supply-requested-suppluies', $record);
    }

    public function accountingAssign()
    {
        $this->accountingAssignModal = true;
    }

    public function render()
    {
        return view('livewire.w-f-p.view-supply-request');
    }
}
