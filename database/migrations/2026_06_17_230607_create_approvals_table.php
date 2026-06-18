<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            // Polymorphic relation owner. Stores morph-map aliases ('dv'/'lr')
            // rather than full class names (see AppServiceProvider morph map).
            $table->string('approvable_type');
            $table->unsignedBigInteger('approvable_id');
            $table->index(['approvable_type', 'approvable_id']);

            // Which sign-off slot this row captures: signatory | accountant | president.
            $table->string('role');

            // The slot owner whose name belongs on the signature block. Freezes
            // accountant/president at sign-off rather than resolving at print time.
            $table->foreignId('user_id')->constrained('users');

            $table->timestamp('approved_at');

            // The OIC who actually signed on the slot owner's behalf; null = direct sign-off.
            $table->foreignId('approved_by_oic_id')->nullable()->constrained('users');

            // Each slot signs once per approvable.
            $table->unique(['approvable_type', 'approvable_id', 'role']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('approvals');
    }
};
