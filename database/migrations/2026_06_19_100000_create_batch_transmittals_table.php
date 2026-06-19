<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('batch_transmittals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('office_group_id')->constrained('office_groups');
            $table->unsignedInteger('serial_number');
            $table->string('from_office_name');
            $table->string('to_office_name');
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('forwarded_by')->nullable()->constrained('users');
            $table->timestamp('forwarded_at')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users');
            $table->timestamp('received_at')->nullable();
            $table->timestamps();

            $table->unique(['office_group_id', 'serial_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('batch_transmittals');
    }
};
