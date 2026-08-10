<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Casts\Attribute;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Designation extends Model
    {
        use HasFactory;

        protected $with = [
            'office',
            'campus',
            'position',
        ];

        protected $appends = [
            'full_designation',
        ];

        public function fullDesignation(): Attribute
        {
            return Attribute::make(
                get: fn($value) => implode(', ', [$this->position?->description, $this->office?->name, $this->campus?->name])
            );
        }

        public function office()
        {
            return $this->belongsTo(Office::class);
        }

        public function position()
        {
            return $this->belongsTo(Position::class);
        }

        public function campus()
        {
            return $this->belongsTo(Campus::class);
        }

        public function employee_information()
        {
            return $this->belongsTo(EmployeeInformation::class);
        }
    }
