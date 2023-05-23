<?php

namespace App\Console\Commands;

use App\Models\TravelOrder;
use DB;
use Illuminate\Console\Command;

class PatchTravelOrderSignatoriesRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:toroles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Update old travel order signatories' roles";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->output->writeln('Patching...');

        DB::beginTransaction();

        $ts = TravelOrder::with('signatories')->get();

        foreach ($ts as $t) {
            $t->signatories->firstWhere('id', 64)?->pivot->update(['role' => 'university_president']);

            $n = $t->signatories->where('id', '!=', 64)->count();
            $i = 1;

            foreach ($t->signatories->where('id', '!=', 64) as $s) {
                if ($i == $n && $n != 1) {
                    $s->pivot->update(['role' => 'recommending_approval']);
                } else {
                    $s->pivot->update(['role' => 'immediate_supervisor']);
                }
                $i++;
            }
        }
        $this->output->writeln('Finished...');
        DB::commit();
        return Command::SUCCESS;
    }
}
