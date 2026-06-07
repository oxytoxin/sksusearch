<?php

    use App\Models\TravelOrderSignatory;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::table('travel_order_signatories', function (Blueprint $table) {
                $table->string('heading')->nullable()->after('role');
                $table->string('designation')->nullable()->after('heading');
            });

            foreach (TravelOrderSignatory::get() as $signatory) {
                $signatory->heading = match ($signatory->role) {
                    default => null,
                    'immediate_supervisor' => 'Noted:',
                    'recommending_approval' => 'Recommending Approval:',
                    'university_president' => 'Approved:',
                };
                $signatory->designation = match ($signatory->role) {
                    default => null,
                    'immediate_supervisor' => 'Immediate Supervisor',
                    'recommending_approval' => 'VPAA / VPRDEX / VPFARG',
                    'university_president' => 'University President',
                };
                $signatory->save();
            }
        }
    };
