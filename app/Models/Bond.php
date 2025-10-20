<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Casts\Attribute;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    /**
     * @mixin IdeHelperBond
     */
    class Bond extends Model
    {
        use HasFactory;

        protected $casts = [
            'validity_date' => 'immutable_date',
        ];

        public function user()
        {
            return $this->belongsTo(User::class);
        }

        public function employee_information()
        {
            return $this->hasOne(EmployeeInformation::class);
        }
    }
