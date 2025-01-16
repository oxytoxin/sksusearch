<?php

namespace App\Http\Livewire\WFP;

use App\Models\CategoryItems;
use App\Models\CategoryItemBudget;
use Livewire\Component;

class DevPage extends Component
{
    public $account_titles;
    public $budget_account_titles;
    public $merged_titles;

    public function mount()
    {
        $this->account_titles = CategoryItems::where('is_active', 1)
            ->where('uacs_code', 'like', '5%')
            ->get();

        $budget_account_titles = CategoryItemBudget::get();

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
    }

    public function addCategoryBudget()
    {
        $budget_account_titles = CategoryItemBudget::get();
        $missing_budget_categories = $this->account_titles->filter(function ($item) use ($budget_account_titles) {
            return !$budget_account_titles->contains('uacs_code', $item->uacs_code);
        });
        dd($missing_budget_categories);
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

    public function render()
    {
        return view('livewire.w-f-p.dev-page');
    }
}
