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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_email');
            $table->string('subject');
            $table->text('body')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->string('message_id')->nullable(); // Provider message ID (if available)
            $table->integer('attempts')->default(0);
            $table->text('error_message')->nullable();
            $table->json('api_response')->nullable(); // Provider response / metadata
            $table->json('attachments')->nullable(); // What was attached: [{disk, path, as}]
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->string('context')->nullable(); // e.g., 'disbursement_voucher_returned'
            $table->unsignedBigInteger('user_id')->nullable(); // Who received the email
            $table->unsignedBigInteger('sender_id')->nullable(); // Who triggered the email
            $table->timestamps();

            // Indexes for better query performance
            $table->index('recipient_email');
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
        Schema::dropIfExists('email_logs');
    }
};
