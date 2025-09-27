<?php

namespace App\Http\Livewire\WFP;

use App\Models\WfpDetail;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use WireUi\Traits\Actions;

class UnmatchedWfpDetails extends Component
{
    use Actions;
    public $details;
    public function render()
    {
        return view('livewire.w-f-p.unmatched-wfp-details');
    }

    public function mount()
    {
        // In your Livewire component
        $this->details = WfpDetail::query()
        ->join('supplies', 'supplies.id', '=', 'wfp_details.supply_id')
        ->leftJoin('category_items', 'category_items.id', '=', 'supplies.category_item_id')
        // join budget categories for both sides
        ->leftJoin('budget_categories as wfp_bc', 'wfp_bc.id', '=', 'wfp_details.budget_category_id')
        ->leftJoin('budget_categories as actual_bc', 'actual_bc.id', '=', 'category_items.budget_category_id')
        ->whereColumn('wfp_details.budget_category_id', '!=', 'category_items.budget_category_id')
        ->select([
            'wfp_details.*',
            'supplies.particulars',
            'supplies.supply_code',
            'category_items.name as category_item_name',
            'category_items.uacs_code',
            'wfp_bc.name as wfp_budget_category_name',
            'actual_bc.name as actual_budget_category_name',
        ])
        ->get();

    }

    public function updateWfpDetails()
    {
        DB::beginTransaction();
        try {
            // If the *authoritative* budget category is from CategoryItem
            $affected = DB::table('wfp_details as w')
                ->join('supplies as s', 's.id', '=', 'w.supply_id')
                ->join('category_items as ci', 'ci.id', '=', 's.category_item_id')
                ->where(function ($q) {
                    $q->whereNull('w.budget_category_id')
                    ->orWhereNull('ci.budget_category_id')
                    ->orWhereColumn('w.budget_category_id', '!=', 'ci.budget_category_id');
                })
                // Set wfp to the actual (from category_items)
                ->update(['w.budget_category_id' => DB::raw('ci.budget_category_id')]);
            DB::commit();

                $this->dialog()->success(
                    $title = 'Operation Success',
                    $description = 'WFP details updated successfully!'
                );
            $this->mount();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dialog()->error(
                $title = 'Update Failed',
                $description = 'Update failed: '.$e->getMessage()
            );
        }

    }
}
