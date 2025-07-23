<?php

namespace App\Http\Livewire\WFP;

use App\Models\CategoryItems;
use App\Models\CategoryItemBudget;
use App\Models\CostCenter;
use App\Models\FundClusterWFP;
use App\Models\Supply;
use Livewire\Component;
use WireUi\Traits\Actions;

class DevPage extends Component
{
    use Actions;
    public $account_titles;
    public $budget_account_titles;
    public $merged_titles;
    public $supplies;
    public $cost_centers;
    public $supply_code;
    public $category_item_budget_id = null;

    public $fund_cluster_id = null;

    public $category_items = [];

    public $fund_clusters = [];


    public function mount()
    {

        $this->cost_centers = [];


        $this->account_titles = CategoryItems::where('is_active', 1)
            ->where('uacs_code', 'like', '5%')
            ->get();

        $budget_account_titles = CategoryItemBudget::get();
        $this->category_items = $budget_account_titles;

        $this->fund_clusters = FundClusterWFP::where('id', '!=', 8)->get();

        $this->merged_titles = $this->account_titles->map(function ($item) use ($budget_account_titles) {
            $budget_item = $budget_account_titles->firstWhere('uacs_code', $item->uacs_code);
            return [
                'id' => $item->id,
                'budget_category_id' => $item->budget_category_id,
                'budget_category' => $item->budgetCategory->name,
                'category_item_uacs' => $item->uacs_code,
                'category_item_name' => $item->name,
                'budget_item_uacs' => $budget_item ? $budget_item->uacs_code : null,
                'budget_item_name' => $budget_item ? $budget_item->name : null,
            ];
        });


        $this->supplies = Supply::whereNull('category_item_budget_id')->get();
    }

    public function generateCostCenters()
    {
        if (!empty($this->supply_code) || !empty($this->category_item_budget_id)) {
            $this->cost_centers = CostCenter::whereHas('wfp', function ($query) {
                $query->whereHas('wfpDetails', function ($query) {
                    $query->whereHas('supply', function ($query) {
                        $query->where('supply_code', $this->supply_code)
                              ->orWhere('category_item_budget_id', $this->category_item_budget_id);
                    });
                });
            })
            ->whereHas('fundAllocations', function ($query) {
                $query->where('fund_cluster_w_f_p_s_id', $this->fund_cluster_id);
            }) // Add this line for the fundAllocations relationship
            ->get();
        }
    }

    public function addCategoryBudget()
    {
        $budget_account_titles = CategoryItemBudget::get();
        $missing_budget_categories = $this->account_titles->filter(function ($item) use ($budget_account_titles) {
            return !$budget_account_titles->contains('uacs_code', $item->uacs_code);
        });
        foreach ($missing_budget_categories as $missing_category) {
            CategoryItemBudget::create([
                'uacs_code' => $missing_category->uacs_code,
                'name' => $missing_category->name,
                'budget_category_id' => $missing_category->budget_category_id,
            ]);
        }

        // Refresh the budget account titles and merged titles
        $this->budget_account_titles = CategoryItemBudget::get();
        $this->merged_titles = $this->account_titles->map(function ($item) {
            $budget_item = $this->budget_account_titles->firstWhere('uacs_code', $item->uacs_code);
            return [
                'id' => $item->id,
                'budget_category_id' => $item->budget_category_id,
                'budget_category' => $item->budgetCategory->name,
                'category_item_uacs' => $item->uacs_code,
                'category_item_name' => $item->name,
                'budget_item_uacs' => $budget_item ? $budget_item->uacs_code : null,
                'budget_item_name' => $budget_item ? $budget_item->name : null,
            ];
        });
    }

    public function updateItems()
    {
        $supplies = Supply::whereNull('category_item_budget_id')->get();
        foreach ($supplies as $supply) {
            $category_item = CategoryItems::where('id', $supply->category_item_id)->first();
            if ($category_item) {
                $budget_item = CategoryItemBudget::where('uacs_code', $category_item->uacs_code)->first();
                if ($budget_item) {
                    $supply->category_item_budget_id = $budget_item->id;
                    $supply->save();
                }
            }
        }

        $this->dialog()->success(
            $title = 'Operation Success',
            $description = 'Updated successfully',
        );
    }

    public function updateUACS()
    {
        $uacs_map = [
            '1040499000' => '5020399000',
            '1040503000' => '5020321003',
            '1040599000' => '5020321099',
            '1040401000' => '5020301000',
            '1040512000' => '5020321012',
            '1040511000' => '5020321011',
            '1040519000' => '5020321099',
            '1040409000' => '5020310000',
            '1040501000' => '5020321001',
            '1040514000' => '5020321002',
            '1040405000' => '5020305000',
            '1040513000' => '5020321013',
            '1040601000' => '5020322001',
            '1040410000' => '5020311000',
            '1040602000' => '5020322002',
            '1040404000' => '5020304000',
            '1069899000' => '5021399000',
            '1060702000' => '5020322002',
            '1040413000' => '1060508000',
            '1040408000' => '5020309000',
            '1040407000' => '5020308000',
            '1040508000' => '5020321008',
            '1040502000' => '5020321002'
        ];

        $category_items = CategoryItems::whereIn('uacs_code', array_keys($uacs_map))
            ->where('is_active', 1)
            ->get();

        $budget_items = CategoryItemBudget::whereIn('uacs_code', array_values($uacs_map))
            ->get()
            ->keyBy('uacs_code');

        foreach ($category_items as $category_item) {
            $budget_uacs_code = $uacs_map[$category_item->uacs_code] ?? null;
            if ($budget_uacs_code && isset($budget_items[$budget_uacs_code])) {
                $category_item_budget_id = $budget_items[$budget_uacs_code]->id;
                Supply::where('category_item_id', $category_item->id)
                    ->update(['category_item_budget_id' => $category_item_budget_id]);
            }
        }

        $this->dialog()->success(
            $title = 'Operation Success',
            $description = 'Updated successfully',
        );
    }

    public function render()
    {
        return view('livewire.w-f-p.dev-page');
    }
}
