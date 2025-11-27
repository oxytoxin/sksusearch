<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->string('formatted_phone_number')->nullable();
            $table->text('message');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->string('message_id')->nullable(); // Semaphore message ID
            $table->integer('attempts')->default(0);
            $table->text('error_message')->nullable();
            $table->json('api_response')->nullable(); // Full API response
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->string('context')->nullable(); // e.g., 'FMR', 'FMD', 'SCO', etc.
            $table->unsignedBigInteger('user_id')->nullable(); // Who received the SMS
            $table->unsignedBigInteger('sender_id')->nullable(); // Who triggered the SMS
            $table->timestamps();

            // Indexes for better query performance
            $table->index('phone_number');
            $table->index('status');
            $table->index('created_at');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_logs');
    }
};
