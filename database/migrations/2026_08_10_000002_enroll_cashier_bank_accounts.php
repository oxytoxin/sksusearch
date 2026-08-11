<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        foreach ($this->accounts() as $account) {
            $bankId = DB::table('banks')
                ->where('name', $account['bank'])
                ->value('id');

            if (! $bankId) {
                $bankId = DB::table('banks')->insertGetId([
                    'name' => $account['bank'],
                    'branch' => '',
                    'address' => '',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $bankAccountId = DB::table('bank_accounts')
                ->where('bank_id', $bankId)
                ->where('number', $account['number'])
                ->value('id');

            if (! $bankAccountId) {
                $bankAccountId = DB::table('bank_accounts')->insertGetId([
                    'bank_id' => $bankId,
                    'number' => $account['number'],
                    'balance' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $fundClusterGroupId = DB::table('fund_cluster_groups')
                ->where('name', $account['fund_cluster_group'])
                ->value('id');

            if (! $fundClusterGroupId) {
                continue;
            }

            DB::table('bank_account_fund_cluster_group')->updateOrInsert(
                [
                    'bank_account_id' => $bankAccountId,
                    'fund_cluster_group_id' => $fundClusterGroupId,
                ],
                ['created_at' => $now, 'updated_at' => $now],
            );
        }
    }

    public function down(): void
    {
        foreach ($this->accounts() as $account) {
            $bankId = DB::table('banks')
                ->where('name', $account['bank'])
                ->value('id');

            $fundClusterGroupId = DB::table('fund_cluster_groups')
                ->where('name', $account['fund_cluster_group'])
                ->value('id');

            if (! $bankId || ! $fundClusterGroupId) {
                continue;
            }

            $bankAccountId = DB::table('bank_accounts')
                ->where('bank_id', $bankId)
                ->where('number', $account['number'])
                ->value('id');

            if (! $bankAccountId) {
                continue;
            }

            DB::table('bank_account_fund_cluster_group')
                ->where('bank_account_id', $bankAccountId)
                ->where('fund_cluster_group_id', $fundClusterGroupId)
                ->delete();
        }
    }

    private function accounts(): array
    {
        return [
            ['bank' => 'DBP', 'fund_cluster_group' => '164', 'number' => '0945-009976-030'],
            ['bank' => 'DBP', 'fund_cluster_group' => '101', 'number' => '200-003945-7'],
            ['bank' => 'LBP', 'fund_cluster_group' => '164', 'number' => '2452-1039-85'],
            ['bank' => 'DBP', 'fund_cluster_group' => '161', 'number' => '0945-009982-030'],
            ['bank' => 'LBP', 'fund_cluster_group' => '161', 'number' => '2452-1084-05'],
            ['bank' => 'LBP', 'fund_cluster_group' => '161', 'number' => '2452-1083-91'],
            ['bank' => 'LBP', 'fund_cluster_group' => '164', 'number' => '2112-1018-80'],
            ['bank' => 'LBP', 'fund_cluster_group' => '164', 'number' => '2452-1076-70'],
            ['bank' => 'DBP', 'fund_cluster_group' => '163', 'number' => '0945-03923F-030'],
            ['bank' => 'LBP', 'fund_cluster_group' => '164', 'number' => '2452-1098-19'],
        ];
    }
};
