<?php

namespace App\Http\Livewire\WFP;

use App\Models\Supply;
use Livewire\Component;
use WireUi\Traits\Actions;
use App\Models\CategoryGroup;
use App\Models\CategoryItems;
use App\Models\ReportedSupply;
use Filament\Notifications\Notification;

class ViewReportSupplyDetails extends Component
{
    public $record;
    public $newReply;
    public $modifySupplyModal = false;
    public $account_titles;
    public $title_groups;

    //modal details
    public $supply_particular;
    public $supply_specification;
    public $supply_account_title;
    public $supply_title_group;
    public $supply_uom;
    public $supply_unit_cost;



    use Actions;

    public function mount($record)
    {
        $this->record = ReportedSupply::find($record);
        $this->account_titles = CategoryItems::where('budget_category_id', $this->record->supply->categoryItems->budget_category_id)->get();
        $this->title_groups = CategoryGroup::all();

        $this->supply_particular = $this->record->supply->particulars;
        $this->supply_specification = $this->record->supply->specifications;
        $this->supply_account_title = $this->record->supply->categoryItems->id;
        $this->supply_title_group = $this->record->supply->categoryGroups->id;
        $this->supply_uom = $this->record->supply->uom;
        $this->supply_unit_cost = $this->record->supply->unit_cost;

    }

    public function addReply()
    {
        $this->validate([
            'newReply' => 'required'
        ], [
            'newReply.required' => 'Reply field is required'
        ]);

        $this->record->replies()->create([
            'user_id' => auth()->id(),
            'reported_supply_id' => $this->record->id,
            'content' => $this->newReply
        ]);

        $this->newReply = '';
        $this->record = ReportedSupply::find($this->record->id);
    }

    public function modifySupply()
    {
        $this->modifySupplyModal = true;
    }

    public function updateSupply()
    {
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Do you really want to modify this supply?',
            'acceptLabel' => 'Yes, modify it',
            'method'      => 'updateSupplyConfirm',
            'params'      => 'Saved',
        ]);
    }

    public function updateSupplyConfirm()
    {
        $supply = Supply::find($this->record->supply_id);
        $supply->particulars = $this->supply_particular;
        $supply->specifications = $this->supply_specification;
        $supply->category_item_id = $this->supply_account_title;
        $supply->category_group_id = $this->supply_title_group;
        $supply->uom = $this->supply_uom;
        $supply->unit_cost = $this->supply_unit_cost;
        $supply->save();

        Notification::make()->title('Operation Success')->body('Supply details are successfully modified')->success()->send();
        return redirect()->route('wfp.report-supply-view-details', $this->record->id);

    }

    public function resolveReport()
    {
        $this->dialog()->confirm([
            'title'       => 'Are you Sure?',
            'description' => 'Do you really want to resolve this report?',
            'acceptLabel' => 'Yes, resolve it',
            'method'      => 'resolveReportConfirm',
            'params'      => 'Saved',
        ]);
    }

    public function resolveReportConfirm()
    {
        $this->record->status = 'Resolved';
        $this->record->save();

        Notification::make()->title('Operation Success')->body('Report is successfully resolved')->success()->send();
        return redirect()->route('wfp.reported-supply-list');
    }

    public function render()
    {
        return view('livewire.w-f-p.view-report-supply-details');
    }
}
