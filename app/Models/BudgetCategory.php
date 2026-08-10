<?php

    namespace App\Models;

    use App\Enums\BudgetCategoryType;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class BudgetCategory extends Model
    {
        use HasFactory;

        protected $guarded = [];

        protected $casts = [
            'type' => BudgetCategoryType::class,
        ];

        public function categoryItems()
        {
            return $this->hasMany(CategoryItems::class);
        }

        public function wfpDetails()
        {
            return $this->hasMany(WfpDetail::class);
        }
    }
