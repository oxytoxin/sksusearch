<?php

namespace App\Console\Commands;

use App\Models\Position;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:foreign-keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds foreign keys to tables';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Schema::table('employee_information', function (Blueprint $table) {
            $table->foreign('position_id')->references('id')->on('positions');
            $table->foreign('office_id')->references('id')->on('offices');
        });
        return Command::SUCCESS;
    }
}
