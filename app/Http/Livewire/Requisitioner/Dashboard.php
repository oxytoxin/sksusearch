<?php

namespace App\Http\Livewire\Requisitioner;

use Livewire\Component;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Models\CategoryItemBudget;
use App\Models\Supply;
use App\Models\CategoryGroup;
use App\Models\CategoryItems;
use DB;
use WireUi\Traits\Actions;

class Dashboard extends Component
{
    use Actions;
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

    public function uploadPricesFirst()
    {
        $rows = SimpleExcelReader::create(storage_path('csv/final_price_list_1.csv'))->getRows();
        $rows->each(function ($data) {
            DB::beginTransaction();
            $budget = Supply::create([
                'category_item_id' => $data['category_item_id'],
                'category_item_budget_id' => $data['category_item_budget_id'],
                'category_group_id' => $data['category_group_id'],
                'supply_code' => $data['supply_code'],
                'particulars' => $data['particulars'],
                'specifications' => $data['specifications'],
                'unit_cost' => $data['unit_cost'],
                'is_ppmp' => $data['is_ppmp'] === 'No' ? 0 : 1,
                'uom' => $data['uom'],
            ]);
            DB::commit();

            $this->dialog()->success(
                $title = 'Operation Success',
                $description = 'Price List Uploaded!'
            );   
        });
    }

    public function uploadPricesSecond()
    {
        $rows = SimpleExcelReader::create(storage_path('csv/final_price_list_2.csv'))->getRows();
        $rows->each(function ($data) {
            DB::beginTransaction();
            $budget = Supply::create([
                'category_item_id' => $data['category_item_id'],
                'category_item_budget_id' => $data['category_item_budget_id'],
                'category_group_id' => $data['category_group_id'],
                'supply_code' => $data['supply_code'],
                'particulars' => $data['particulars'],
                'specifications' => $data['specifications'],
                'unit_cost' => $data['unit_cost'],
                'is_ppmp' => $data['is_ppmp'] === 'No' ? 0 : 1,
                'uom' => $data['uom'],
            ]);
            DB::commit();

            $this->dialog()->success(
                $title = 'Operation Success',
                $description = 'Price List Uploaded!'
            );   
        });
    }

    public function uploadPricesThird()
    {
        $rows = SimpleExcelReader::create(storage_path('csv/final_price_list_3.csv'))->getRows();
        $rows->each(function ($data) {
            DB::beginTransaction();
            $budget = Supply::create([
                'category_item_id' => $data['category_item_id'],
                'category_item_budget_id' => $data['category_item_budget_id'],
                'category_group_id' => $data['category_group_id'],
                'supply_code' => $data['supply_code'],
                'particulars' => $data['particulars'],
                'specifications' => $data['specifications'],
                'unit_cost' => $data['unit_cost'],
                'is_ppmp' => $data['is_ppmp'] === 'No' ? 0 : 1,
                'uom' => $data['uom'],
            ]);
            DB::commit();

            $this->dialog()->success(
                $title = 'Operation Success',
                $description = 'Price List Uploaded!'
            );   
        });
    }

    public function uploadPricesFourth()
    {
        $rows = SimpleExcelReader::create(storage_path('csv/final_price_list_4.csv'))->getRows();
        $rows->each(function ($data) {
            DB::beginTransaction();

            $budget = Supply::create([
                'category_item_id' => $data['category_item_id'],
                'category_item_budget_id' => $data['category_item_budget_id'] == '' ? null : $data['category_item_budget_id'],
                'category_group_id' => $data['category_group_id'],
                'supply_code' => $data['supply_code'],
                'particulars' => $data['particulars'],
                'specifications' => $data['specifications'],
                'unit_cost' => $data['unit_cost'],
                'is_ppmp' => $data['is_ppmp'] === 'No' ? 0 : 1,
                'uom' => $data['uom'],
            ]);
            DB::commit();

            $this->dialog()->success(
                $title = 'Operation Success',
                $description = 'Price List Uploaded!'
            );   
        });
    }


    public function render()
    {
        return view('livewire.requisitioner.dashboard');
    }
}
