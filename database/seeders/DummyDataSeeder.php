<?php

namespace Database\Seeders;

use App\Models\DisbursementVoucher;
use App\Models\Itinerary;
use App\Models\TravelOrder;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::find(1);
        $faker = \Faker\Factory::create();
        $to = TravelOrder::create([
            'tracking_code' => TravelOrder::generateTrackingCode(),
            'travel_order_type_id' => 1,
            'date_from' => today()->addDays(2),
            'date_to' => today()->addDays(4),
            'purpose' => $faker->sentence,
            'has_registration' => false,
            'registration_amount' => 0,
            'philippine_region_id' => 1,
            'philippine_province_id' => 1,
            'philippine_city_id' => 1,
            'other_details' => '',
        ]);
        $to->applicants()->sync([1]);
        $to->signatories()->sync([1 => ['is_approved' => true]]);

        $days = CarbonPeriod::between($to->date_from, $to->date_to)->toArray();
        $entries = [];
        foreach ($days as  $day) {
            if ($to->travel_order_type_id == 1) {
                if ($day != $to->date_to) {
                    $per_diem = $to->philippine_region->dte->amount;
                } else {
                    $per_diem = $to->philippine_region->dte->amount / 2;
                }
            } else {
                $per_diem = 0;
            }

            $entries[Str::uuid()->toString()] = [
                'date' => $day->toDateString(),
                'per_diem' => $per_diem,
                'original_per_diem' => $per_diem,
                'total_expenses' => 0,
                'breakfast' => false,
                'lunch' => false,
                'dinner' => false,
                'lodging' => false,
                'itinerary_entries' => [],
            ];
        }
        $itinerary = Itinerary::create([
            'user_id' => 1,
            'travel_order_id' => $to->id,
            'coverage' => $entries,
        ]);

        $dv = DisbursementVoucher::create([
            'voucher_subtype_id' => 1,
            'user_id' => 1,
            'signatory_id' => 1,
            'mop_id' => 1,
            'payee' => $user->employee_information->full_name,
            'travel_order_id' => $to->id,
            'tracking_number' => 'DV_' . now()->format('Y') . '-' . now()->format('m') . '-' . rand(1, 999),
            'submitted_at' => now(),
            'current_step_id' => 3000,
            'previous_step_id' => null,
        ]);
        $dv->disbursement_voucher_particulars()->create([
            'purpose' => $to->purpose,
            'responsibility_center' => $faker->company,
            'mfo_pap' => 'mfo',
            'amount' => 1000,
        ]);
        $dv->activity_logs()->create([
            'description' => $dv->current_step->process . ' ' . $dv->signatory->employee_information->full_name . ' ' . $dv->current_step->sender,
        ]);
    }
}
