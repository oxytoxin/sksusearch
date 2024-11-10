<?php

namespace App\Http\Livewire\Requisitioner;

use Livewire\Component;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Models\CategoryItemBudget;
use DB;

class Dashboard extends Component
{

    public function test()
    {
        $rows = SimpleExcelReader::create(storage_path('csv/category_items_budget.csv'))->getRows();
        $rows->each(function ($data) {
            DB::beginTransaction();
            $budget = CategoryItemBudget::create([
                'budget_category_id' => $data['budget_category_id'],
                'name' => $data['name'],
                'uacs_code' => $data['uacs_code'],
            ]);
            DB::commit();
        });
    }

    public function render()
    {
        return view('livewire.requisitioner.dashboard');
    }
}
