<?php

namespace Database\Factories;

use App\Models\FundCluster;
use App\Models\PettyCashFund;
use App\Models\PettyCashVoucher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PettyCashVoucher>
 */
class PettyCashVoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $created = $this->faker->dateTimeBetween('-3 months', 'now');
        $particulars = collect();
        for ($i = 0; $i < $this->faker->numberBetween(1, 5); $i++) {
            $particulars->push([
                'name' => $this->faker->words(5, true),
                'amount' => $this->faker->numberBetween(100, 500),
            ]);
        }
        $total = $particulars->sum('amount');
        $paid = $this->faker->numberBetween($total - 300, $total + 300);
        $tn = PettyCashVoucher::generateTrackingNumber();
        return [
            'tracking_number' => $tn,
            'entity_name' => $this->faker->name,
            'fund_cluster_id' => $this->faker->randomElement(FundCluster::pluck('id')->toArray()),
            'petty_cash_fund_id' => $this->faker->randomElement(PettyCashFund::pluck('id')->toArray()),
            'pcv_number' => $tn,
            'pcv_date' => $created,
            'payee' => "dawdwa",
            'custodian_id' => 19,
            'requisitioner_id' => $this->faker->randomElement(User::pluck('id')->toArray()),
            'signatory_id' => $this->faker->randomElement(User::pluck('id')->toArray()),
            'responsibility_center' => "dawd",
            'particulars' => $particulars->toArray(),
            'amount_granted' => $total,
            'amount_paid' => $paid < 0 ? 0 : $paid,
            'is_liquidated' => $this->faker->randomElement([true, false]),
            'created_at' => $created,
        ];
    }
}
