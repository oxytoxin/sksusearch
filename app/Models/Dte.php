<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Casts\Attribute;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    /**
     * @mixin IdeHelperDte
     */
    class Dte extends Model
    {
        use HasFactory;


        public function philippine_region()
        {
            return $this->belongsTo(PhilippineRegion::class);
        }
    }
