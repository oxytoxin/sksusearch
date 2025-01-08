<?php

namespace App\Http\Livewire\WFP;

use Livewire\Component;
use App\Models\Wfp;
use App\Models\CategoryGroup;
use App\Models\CostCenter;
use App\Models\FundDraftAmount;
use App\Models\FundDraftItem;
use App\Models\FundDraft;
use App\Models\FundAllocation;
use WireUi\Traits\Actions;
use DB;

class DeactivatedPricelists extends Component
{
    use Actions;
    public $record;
    public $costCenters;
    public $filteredCostCenters;

    public function mount()
    {
        $this->record =  Wfp::with([
            'costCenter',
            'user',
            'wfpDetails'
        ])->whereHas('wfpDetails.supply', function ($query) {
            $query->where('is_active', 0);
        })->get();

        $this->costCenters = CostCenter::whereHas('fundAllocations',function ($query) {
            $query->whereNotIn('fund_cluster_w_f_p_s_id', [1, 3]);
        })
        ->whereHas('fundAllocations.fundDrafts', function ($query) {
            $query->whereNotNull('id');
        })->with([
            // Aggregate draft_items by title_group
            'fundAllocations.fundDrafts.draft_items' => function ($query) {
                $query->select('fund_draft_id', 'title_group', DB::raw('SUM(estimated_budget) as total_budget'))
                    ->groupBy('fund_draft_id', 'title_group');
            },
            // Aggregate draft_amounts by category_item_id
            'fundAllocations.fundDrafts.draft_amounts' => function ($query) {
                $query->select('fund_draft_id', 'category_group_id', DB::raw('SUM(current_total) as total_amount'))
                    ->groupBy('fund_draft_id', 'category_group_id');
            },
        ])->get();

        // $this->costCenters = CostCenter::whereHas('fundAllocations.fundDrafts', function ($query) {
        //     $query->whereNotNull('id'); // Ensure that fundDrafts exist
        // })->with([
        //     'fundAllocations.fundDrafts' => function ($query) {
        //         $query->with(['draft_items', 'draft_amounts']);
        //     }
        // ])->get();
        // $this->filteredCostCenters = $this->costCenters->filter(function ($costCenter) {
        //     foreach ($costCenter->fundAllocations as $fundAllocation) {
        //         foreach ($fundAllocation->fundDrafts as $fundDraft) {
        //             $estimatedBudgetSum = $fundDraft->draft_items->sum('estimated_budget');
        //             $currentTotalSum = $fundDraft->draft_amounts->sum('current_total');

        //             if ($estimatedBudgetSum != $currentTotalSum) {
        //                 return true; // Include this cost center
        //             }
        //         }
        //     }
        //     return false; // Exclude this cost center
        // });


        // $this->record = Wfp::whereHas('wfpDetails', function($query) {
        //     $query->whereHas('supply', function ($query) {
        //         $query->where('is_active', 0);
        //     });
        // })->get();
    }

    public function updateAmounts()
    {
       
        // Step 1: Identify and remove duplicates in fund_draft_amounts
    $duplicates = DB::table('fund_draft_amounts')
    ->select('fund_draft_id', 'category_group_id', DB::raw('COUNT(*) as count'))
    ->groupBy('fund_draft_id', 'category_group_id')
    ->having('count', '>', 1)
    ->get();

foreach ($duplicates as $duplicate) {
    // Keep only one entry and delete the rest
    DB::table('fund_draft_amounts')
        ->where('fund_draft_id', $duplicate->fund_draft_id)
        ->where('category_group_id', $duplicate->category_group_id)
        ->orderBy('id') // Keep the first record by ID
        ->skip(1) // Skip the first record
        ->take(PHP_INT_MAX) // Remove all remaining duplicates
        ->delete();
}

// Step 2: Process draft items and update or insert into fund_draft_amounts
$draftItems = FundDraftItem::with('fundDraft') // Load the related fundDraft
    ->select(
        'fund_draft_id',
        'title_group',
        DB::raw('SUM(CAST(estimated_budget AS DECIMAL(15,2))) as total_budget') // Cast to DECIMAL
    )
    ->groupBy('fund_draft_id', 'title_group')
    ->get();

foreach ($draftItems as $item) {
    $existing = DB::table('fund_draft_amounts')
        ->where('fund_draft_id', $item->fund_draft_id)
        ->where('category_group_id', $item->title_group)
        ->first();

    if ($existing) {
        // Update if the record exists
        DB::table('fund_draft_amounts')
            ->where('fund_draft_id', $item->fund_draft_id)
            ->where('category_group_id', $item->title_group)
            ->update(['current_total' => $item->total_budget]);
    } else {
        // Create a new record if it does not exist
        $category = CategoryGroup::find($item->title_group);
        $draft = FundDraft::find($item->fund_draft_id);
        $allocation = FundAllocation::find($draft->fund_allocation_id);
        if($allocation->category_group_id === null)
        {
            // $initial_amount = $allocation->initial_amount;
            FundDraftAmount::create([
                'fund_draft_id' => $item->fund_draft_id,
                'category_group_id' => $item->title_group,
                'category_group' => $category->name,
                'initial_amount' => 0,
                'current_total' => $item->total_budget,
                'balance' => 0,
            ]);
        }else{
            //$allocation = $allocation->where('category_group_id', $item->title_group);
            // dd($allocation);
            $initial_amount = $allocation->initial_amount;
            FundDraftAmount::create([
                'fund_draft_id' => $item->fund_draft_id,
                'category_group_id' => $item->title_group,
                'category_group' => $category->name,
                'initial_amount' => $initial_amount,
                'current_total' => $item->total_budget,
                'balance' => $initial_amount == 0 ? 0 : $initial_amount - $item->total_budget,
            ]);
        }

    }
}


$this->dialog()->success(
    $title = 'Operation Success',
    $description = 'Updated successfully',
);
    }

    public function removeAmounts()
    {
          // Get all valid combinations of fund_draft_id and title_group from fund_draft_items
    $validTitleGroups = DB::table('fund_draft_items')
    ->select('fund_draft_id', 'title_group')
    ->distinct()
    ->get()
    ->map(function ($item) {
        return "{$item->fund_draft_id}-{$item->title_group}";
    })
    ->toArray();

// Delete fund_draft_amounts where category_group_id does not match any title_group
DB::table('fund_draft_amounts')
    ->whereNotIn(
        DB::raw("CONCAT(fund_draft_id, '-', category_group_id)"),
        $validTitleGroups
    )
    ->delete();

    $this->dialog()->success(
        $title = 'Operation Success',
        $description = 'removed successfully',
    );

    }

    public function deleteItems()
    {
        foreach ($this->costCenters as $costCenter) {
            foreach ($costCenter->fundAllocations as $allocation) {
                // Ensure the allocation has a `category_group_id` and check if the allocation value is not zero

                if (($allocation->fund_cluster_w_f_p_s_id === 1 || $allocation->fund_cluster_w_f_p_s_id === 3)) {

                    $categories = FundAllocation::where('cost_center_id', $costCenter->id)
                        ->where('wpf_type_id', $allocation->wpf_type_id)
                        ->where('initial_amount', '!=', '0.00')
                        ->pluck('category_group_id')
                        ->toArray();
                        $drafts = FundDraft::where('fund_allocation_id', $allocation->id)->get();
                        foreach ($drafts as $draft) {
                        if(!$draft->draft_items()->whereIn('title_group', $categories)->exists())
                        {
                            $draft->draft_items()->delete();
                        }

                        if(!$draft->draft_amounts()->whereIn('category_group_id', $categories)->exists())
                        {
                            $draft->draft_amounts()->delete();
                        }
                    }
                }
            }
        }

        $this->dialog()->success(
            $title = 'Operation Success',
            $description = 'Removed successfully',
        );
    }

    public function render()
    {
        return view('livewire.w-f-p.deactivated-pricelists');
    }
}
