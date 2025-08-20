<?php

    namespace App\Console\Commands;

    use App\Models\PettyCashFund;
    use App\Models\PettyCashFundRecord;
    use Filament\Notifications\Notification;
    use Illuminate\Console\Command;

    class RecalculatePettyCashFundRecordRunningBalancesCommand extends Command
    {
        protected $signature = 'recalculate:pcfr-balances';

        protected $description = 'Recalculate running balances for specified petty cash fund records';

        public function handle()
        {
            $pcfs = PettyCashFund::all();
            $pcfs->each(function ($pcf) {
                $pcfrs = PettyCashFundRecord::where('petty_cash_fund_id', $pcf->id)->get();
                $balance = 0;
                $pcfrs->each(function ($pcfr) use (&$balance) {
                    if (in_array($pcfr->type, [PettyCashFundRecord::REPLENISHMENT, PettyCashFundRecord::REFUND])) {
                        $balance += $pcfr->amount;
                    } else {
                        if (in_array($pcfr->type,
                            [PettyCashFundRecord::DISBURSEMENT, PettyCashFundRecord::REIMBURSEMENT])) {
                            $balance -= $pcfr->amount;
                        } else {
                            Notification::make()->title('Error')->body('Unknown petty cash fund record type')->send();
                            return;
                        }
                    }
                    $pcfr->running_balance = $balance;
                    $pcfr->save();
                });
                if ($balance < 0) {
                    dump($pcf->campus->name, $balance);
                }
            });
            return Command::SUCCESS;
        }
    }
