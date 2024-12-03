<?php

namespace App\Http\Livewire\WFP;

use App\Models\BudgetCategory;
use App\Models\CategoryGroup;
use App\Models\CategoryItems;
use App\Models\Supply;
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
    public $forwardRequestToSupply = false;
    public $modifySupplyCode = false;
    public $accountingRejectRequestModal = false;
    public $accounting_reject_request_remarks;
    public $accounting_modify_request_remarks;
    public $supply_code;
    public $modify_supply_code;
    public $uacs_code;
    public $account_titles;
    public $title_groups;
    public $budget_categories;
    public $requested_budget_category;
    public $requested_account_title;
    public $requested_category_group;

    use Actions;

    public function mount($record)
    {
        $this->record = WfpRequestedSupply::find($record);
        $this->budget_categories = BudgetCategory::all();
        $this->account_titles = CategoryItems::where('budget_category_id', $this->requested_budget_category)->get();
        $this->title_groups = CategoryGroup::all();
    }

    public function updatedRequestedBudgetCategory($value)
    {
        if($value == null)
        {
            $this->account_titles = null;
        }else{
            $this->account_titles = CategoryItems::where('budget_category_id', $value)->get();
        }
    }

    public function updatedRequestedAccountTitle($value)
    {
        if($value == null)
        {
            $this->uacs_code = null;
        }else{
            $this->uacs_code = CategoryItems::find($value)->uacs_code;
        }
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
         $this->record->save();
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
        $this->record->save();
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
        $this->record->save();
        DB::commit();

        Notification::make()->title('Operation Successful')->body('Supply code has been successfully updated')->success()->send();
        return redirect()->route('wfp.request-supply-view', $this->record->id);
    }

    public function modifySupplyCodeX()
    {
        $this->validate([
            'modify_supply_code' => 'required',
        ]);
        DB::beginTransaction();
        $this->record->update([
            'supply_code' => $this->modify_supply_code,
            'status' => 'Supply Code Assigned',
            'is_approved_supply' => 1,
        ]);
        $this->record->wfpRequestTimeline()->create([
            'user_id' => auth()->id(),
            'activity' => 'Forward to Accounting',
            'remarks' => 'Supply Code Assigned',
        ]);
        $this->record->save();
        DB::commit();

        Notification::make()->title('Operation Successful')->body('Supply code has been successfully updated')->success()->send();
        return redirect()->route('wfp.request-supply-view', $this->record->id);
    }

    public function modifyRequest()
    {
        $this->modifyRequestModal = true;
    }

    public function openModifySupplyCodeModal()
    {
        $this->modifySupplyCode = true;
        $this->modify_supply_code = $this->record->supply_code;
    }

    public function modifyRequestSupply($record)
    {
        $this->record = WfpRequestedSupply::find($record);
        $this->record->update([
            'status' => 'Request Modification',
        ]);
        $this->record->save();
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
        $this->record->status = 'Request Rejected by Supply';
        $this->record->save();
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

    public function updateAccountingAssign()
    {
        $this->validate([
            'requested_budget_category' => 'required',
            'requested_account_title' => 'required',
            'requested_category_group' => 'required',
        ],
        [
            'requested_budget_category.required' => 'The Budget Category field is required',
            'requested_account_title.required' => 'The Account Title field is required',
            'requested_category_group.required' => 'The Category Group field is required',
        ]);

        DB::beginTransaction();
        $this->record->update([
            'category_item_id' => $this->requested_account_title,
            'category_group_id' => $this->requested_category_group,
            'status' => 'Accounting Assigned Data',
            'is_approved_finance' => 1,
        ]);
        $this->record->save();
        WfpRequestTimeline::create([
            'wfp_request_id' => $this->record->id,
            'user_id' => auth()->id(),
            'activity' => 'Accounting Assigned Data',
            'remarks' => 'Accounting Assigned Data',
        ]);

        Supply::create([
            'category_item_id' => $this->record->category_item_id,
            'category_group_id' => $this->record->category_group_id,
            'supply_code' => $this->record->supply_code,
            'particulars' => $this->record->particulars,
            'specifications' => $this->record->specification,
            'unit_cost' => $this->record->unit_cost,
            'uom' => $this->record->uom,
            'is_ppmp' => $this->record->is_ppmp,
        ]);

        DB::commit();

        $this->dialog()->success(
            $title = 'Operation Successful',
            $description = 'Accounting has been successfully assigned the data and the supply is added to the database',
        );

        return redirect()->route('wfp.supply-requested-suppluies');
    }

    public function accountingModifyRequestSupply($record)
    {
        $this->validate([
            'accounting_modify_request_remarks' => 'required',
        ],
        [
            'accounting_modify_request_remarks.required' => 'The Remarks field is required',
        ]);

        DB::beginTransaction();
        $this->record = WfpRequestedSupply::find($record);
        $this->record->update([
            'status' => 'Accounting Request Modification',
        ]);
        $this->record->save();
        WfpRequestTimeline::create([
            'wfp_request_id' => $this->record->id,
            'user_id' => auth()->id(),
            'activity' => 'Accounting Request Modification',
            'remarks' => $this->accounting_modify_request_remarks,
        ]);
        DB::commit();
        Notification::make()->title('Operation Success')->body('Request has been forwarded to supply to be modified')->success()->send();
        return redirect()->route('wfp.accounting-requested-suppluies', $record);
    }

    public function rejectRequestAccountingModal()
    {
        $this->accountingRejectRequestModal = true;
    }


    public function rejectRequestAccounting($record)
    {
        $this->record = WfpRequestedSupply::find($record);
        $this->record->status = 'Request Rejected by Accounting';
        $this->record->save();

        WfpRequestTimeline::create([
            'wfp_request_id' => $this->record->id,
            'user_id' => auth()->id(),
            'activity' => 'Request Rejected by Accounting',
            'remarks' => $this->accounting_reject_request_remarks,
        ]);

        Notification::make()->title('Operation Success')->body('Request has been rejected')->success()->send();
        return redirect()->route('wfp.accounting-requested-suppluies', $record);
    }

    public function render()
    {
        return view('livewire.w-f-p.view-supply-request');
    }
}
